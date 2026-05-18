<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\User;
use App\Models\Admin;
use App\Models\Tender;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TenderControllerTest extends TestCase
{
    // RefreshDatabase akan mereset database otomatis setiap kali test dijalankan
    // sehingga data test tidak akan mengotori database asli Anda
    use RefreshDatabase; 

    protected function setUp(): void
    {
        parent::setUp();
        
        // Memalsukan folder storage agar file PDF buatan test tidak 
        // menumpuk di folder public/storage asli di laptop Anda
        Storage::fake('public'); 
    }

    /**
     * Helper untuk membuat akun Admin bayangan untuk pengujian
     */
    private function createDummyAdmin()
    {
        $user = User::create([
            'email' => 'admin_test@test.com',
            'password' => bcrypt('password123'),
        ]);

        $admin = Admin::create([
            'user_id' => $user->id,
            'nip' => '123456789',
            'phone' => '08123456789',
        ]);

        return $user;
    }

    /**
     * TEST 1: Memastikan Admin bisa membuat Tender beserta Dokumennya
     */
    public function test_admin_can_create_tender_with_base64_document()
    {
        // 1. Siapkan data Admin dan autentikasi (seolah-olah sedang login)
        $adminUser = $this->createDummyAdmin();

        // 2. Siapkan data tiruan Base64 (string Base64 sederhana mewakili file)
        $dummyBase64 = base64_encode('Ini adalah isi file PDF tiruan');

        // 3. Siapkan JSON payload yang akan dikirim ke API
        $payload = [
            'title' => 'Pengadaan Server E-Procurement 2026',
            'description' => 'Pengadaan server spesifikasi tinggi',
            'specification' => 'RAM 128GB, SSD 2TB',
            'budget' => 500000000,
            'registration_start' => '2026-06-01 08:00:00',
            'registration_end' => '2026-06-05 15:00:00',
            'aanwijzing_at' => '2026-06-06 10:00:00',
            'bidding_start' => '2026-06-07 08:00:00',
            'bidding_end' => '2026-06-12 15:00:00',
            'document_base64' => $dummyBase64
        ];

        // 4. Lakukan penembakan API dengan metode POST
        $response = $this->actingAs($adminUser, 'sanctum')
                         ->postJson('/api/admin/tenders', $payload);

        // 5. PASTIKAN HASILNYA SESUAI EKSPEKTASI (ASSERTIONS)
        
        // Memastikan API merespon dengan status 201 Created
        $response->assertStatus(201);

        // Memastikan data benar-benar masuk ke database tiruan
        $this->assertDatabaseHas('tenders', [
            'title' => 'Pengadaan Server E-Procurement 2026',
            'budget' => 500000000,
            'status' => 'draft'
        ]);

        // Memastikan file PDF berhasil dibuat di folder Storage tiruan
        $tender = Tender::first();
        $this->assertNotNull($tender->document_path);
        Storage::disk('public')->assertExists($tender->document_path);
    }

    /**
     * TEST 2: Memastikan File ikut terhapus saat Tender dihapus
     */
    public function test_document_is_deleted_when_tender_is_deleted()
    {
        $adminUser = $this->createDummyAdmin();
        
        // Buat file bohongan di Storage
        $dummyFilePath = 'tenders/documents/test_file.pdf';
        Storage::disk('public')->put($dummyFilePath, 'Isi file');

        // Buat data tender bohongan di DB
        $tender = Tender::create([
            'title' => 'Tender Hapus',
            'description' => 'Akan dihapus',
            'specification' => '-',
            'budget' => 1000,
            'document_path' => $dummyFilePath,
            'status' => 'draft',
            'created_by' => $adminUser->admin->id
        ]);

        // Tembak API Delete
        $response = $this->actingAs($adminUser, 'sanctum')
                         ->deleteJson('/api/admin/tenders/' . $tender->id);

        $response->assertStatus(200);

        // Pastikan datanya hilang dari DB
        $this->assertDatabaseMissing('tenders', ['id' => $tender->id]);

        // Pastikan file fisiknya ikut hilang dari Storage
        Storage::disk('public')->assertMissing($dummyFilePath);
    }
}
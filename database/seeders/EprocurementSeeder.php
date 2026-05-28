<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Admin;
use App\Models\Vendor;
use App\Models\Tender;
use App\Models\TenderTimeline;
use App\Models\TenderParticipant;
use App\Models\Bid;
use App\Models\TenderResult;
use App\Models\PurchaseOrder;
use Carbon\Carbon;

class EprocurementSeeder extends Seeder
{
    public function run()
    {
        $adminUser = User::where('role', 'admin')->first();
        $adminData = Admin::where('user_id', $adminUser->id)->first();

        if (!$adminUser) {
            $this->command->error('Gagal: Tidak ada akun Admin di database! Silakan buat admin dulu.');
            return;
        }

        // Vendor 1 (Approved)
        $userV1 = User::firstOrCreate(
            ['email' => 'vendor1@mail.com'],
            ['name' => 'PT Teknologi Maju', 'password' => Hash::make('password123'), 'role' => 'vendor']
        );
        // TANGKAP HASILNYA KE VARIABEL $vendor1
        $vendor1 = Vendor::firstOrCreate(
            ['user_id' => $userV1->id],
            [
                'name' => 'Budi Santoso', 
                'company_name' => 'PT Teknologi Maju', 
                'address' => 'Jl. Sudirman No 1', 
                'phone' => '081234567890', 
                'status' => 'approved'
            ]
        );

        // Vendor 2 (Approved)
        $userV2 = User::firstOrCreate(
            ['email' => 'vendor2@mail.com'],
            ['name' => 'CV Sukses Bersama', 'password' => Hash::make('password123'), 'role' => 'vendor']
        );
        // TANGKAP HASILNYA KE VARIABEL $vendor2
        $vendor2 = Vendor::firstOrCreate(
            ['user_id' => $userV2->id],
            [
                'name' => 'Andi Wijaya', 
                'company_name' => 'CV Sukses Bersama', 
                'address' => 'Jl. Merdeka No 2', 
                'phone' => '081298765432', 
                'status' => 'approved'
            ]
        );

        // Vendor 3 (Pending - Belum diverifikasi Admin)
        $userV3 = User::firstOrCreate(
            ['email' => 'vendor3@mail.com'],
            ['name' => 'PT Angin Ribut', 'password' => Hash::make('password123'), 'role' => 'vendor']
        );
        // TANGKAP HASILNYA KE VARIABEL $vendor3
        $vendor3 = Vendor::firstOrCreate(
            ['user_id' => $userV3->id],
            [
                'name' => 'Citra Lestari', 
                'company_name' => 'PT Angin Ribut', 
                'address' => 'Jl. Pahlawan No 3', 
                'phone' => '081333444555', 
                'status' => 'pending'
            ]
        );

        // ---------------------------------------------------
        // 3. TENDER 1: SEDANG BERJALAN (Bidding Active)
        // ---------------------------------------------------
        $tender1 = Tender::create([
            'title' => 'Pengadaan 50 Unit Laptop Server',
            'description' => 'Spesifikasi: Core i7, 16GB RAM, 1TB SSD.',
            'budget' => 750000000,
            'status' => 'bidding',
            'created_by' => $adminData->id,
        ]);
        
        TenderTimeline::create([
            'tender_id' => $tender1->id,
            'bidding_start' => Carbon::now()->subDays(2),
            'bidding_end' => Carbon::now()->addDays(1)
        ]);

        // GUNAKAN $vendor1->id dan $vendor2->id DI SINI
        TenderParticipant::create(['tender_id' => $tender1->id, 'vendor_id' => $vendor1->id]);
        TenderParticipant::create(['tender_id' => $tender1->id, 'vendor_id' => $vendor2->id]);

        Bid::create([
            'tender_id' => $tender1->id, 
            'vendor_id' => $vendor1->id, 
            'bid_amount' => 740000000,
            'submitted_at' => Carbon::now()->subDay() 
        ]);
        Bid::create([
            'tender_id' => $tender1->id, 
            'vendor_id' => $vendor2->id, 
            'bid_amount' => 725000000,
            'submitted_at' => Carbon::now()
        ]);

        // ---------------------------------------------------
        // 4. TENDER 2: SUDAH SELESAI (Menghasilkan PO)
        // ---------------------------------------------------
        $tender2 = Tender::create([
            'title' => 'Pengadaan Lisensi Antivirus Enterprise',
            'description' => 'Lisensi 1 tahun untuk 500 endpoint.',
            'budget' => 150000000,
            'status' => 'closed',
            'created_by' => $adminData->id,
        ]);

        // GUNAKAN $vendor1->id
        TenderParticipant::create(['tender_id' => $tender2->id, 'vendor_id' => $vendor1->id]);
        
        $winningBid = Bid::create([
            'tender_id' => $tender2->id, 
            'vendor_id' => $vendor1->id, 
            'bid_amount' => 145000000,
            'submitted_at' => Carbon::now()
        ]);

        TenderResult::create([
            'tender_id'        => $tender2->id,
            'winner_vendor_id' => $vendor1->id, // PASTIKAN NAMA KOLOM INI BENAR DI MIGRATION ANDA
            'winning_bid'      => $winningBid->bid_amount, 
            'notes'            => 'Pemenang dipilih karena dokumen lengkap dan harga efisien.',
            'selected_by'      => $adminData->id, 
            'selected_at'      => Carbon::now() 
        ]);

        PurchaseOrder::create([
            'tender_id' => $tender2->id,
            'vendor_id' => $vendor1->id,
            'po_number'    => 'PO-DUMMY-' . $tender2->id, 
            'total_amount' => $winningBid->bid_amount,
            'status' => 'draft'
        ]);

        $this->command->info('E-Procurement Dummy Data successfully seeded! 🚀');
    }
}
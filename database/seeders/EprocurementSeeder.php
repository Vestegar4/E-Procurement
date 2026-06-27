<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Vendor;
use App\Models\Tender;
use App\Models\Aanwijzing;
use Carbon\Carbon;

class EprocurementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ==========================================
        // 1. BUAT AKUN ADMIN
        // ==========================================
        $adminUser = User::create([
            'name' => 'Admin Proculus',
            'email' => 'admin@proculus.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // ==========================================
        // 2. BUAT AKUN VENDOR (APPROVED, REJECTED, PENDING)
        // ==========================================
        $vendorsData = [
            [
                'name' => 'Budi Santoso',
                'email' => 'approved1@vendor.com',
                'company_name' => 'PT Maju Terus (Approved)',
                'status' => 'approved'
            ],
            [
                'name' => 'Siti Aminah',
                'email' => 'approved2@vendor.com',
                'company_name' => 'CV Sukses Makmur (Approved)',
                'status' => 'approved'
            ],
            [
                'name' => 'Andi Gagal',
                'email' => 'rejected@vendor.com',
                'company_name' => 'PT Kurang Syarat (Rejected)',
                'status' => 'rejected'
            ],
            [
                'name' => 'Joko Menunggu',
                'email' => 'pending@vendor.com',
                'company_name' => 'PT Menunggu Validasi (Pending)',
                'status' => 'pending'
            ]
        ];

        $vendorModels = [];

        foreach ($vendorsData as $v) {
            // Buat User untuk Login Vendor
            $user = User::create([
                'name' => $v['name'],
                'email' => $v['email'],
                'password' => Hash::make('password123'),
                'role' => 'vendor',
            ]);

            // Buat Profil Vendor-nya
            $vendorModels[] = Vendor::create([
                'user_id' => $user->id,
                'name' => $v['name'],
                'company_name' => $v['company_name'],
                'address' => 'Jl. Sudirman No. ' . rand(1, 100) . ', Jakarta',
                'phone' => '0812' . rand(10000000, 99999999),
                'npwp' => rand(10, 99) . '.' . rand(100, 999) . '.' . rand(100, 999) . '.' . rand(1, 9) . '-' . rand(100, 999) . '.000',
                'status' => $v['status']
            ]);
        }

        // ==========================================
        // 3. BUAT DATA TENDER
        // ==========================================
        $tender = Tender::create([
            'title' => 'Pengadaan Perangkat Server dan Laptop IT 2026',
            'description' => 'Pengadaan perangkat keras IT untuk menunjang kebutuhan operasional perusahaan tahun 2026.',
            'budget' => 450000000, // 450 Juta
            'status' => 'open', // Bisa open, bidding, closed
        ]);

        // (Opsional) Jika Anda punya tabel timeline, tambahkan di sini
        // TenderTimeline::create([...]) 

        // ==========================================
        // 4. BUAT DATA AANWIJZING (TANYA JAWAB TENDER)
        // ==========================================
        
        // Skenario 1: Vendor Approved 1 bertanya, dan SUDAH dijawab Admin
        Aanwijzing::create([
            'tender_id' => $tender->id,
            'vendor_id' => $vendorModels[0]->id, // PT Maju Terus
            'question' => 'Apakah spesifikasi prosesor laptop diperbolehkan menggunakan AMD Ryzen, atau diwajibkan Intel Core?',
            'answer' => 'Spesifikasi dibebaskan selama setara dengan minimal Intel Core i5 generasi 12 atau AMD Ryzen 5 seri 6000.',
            'created_at' => Carbon::now()->subHours(5),
            'updated_at' => Carbon::now()->subHours(4),
        ]);

        // Skenario 2: Vendor Approved 2 bertanya, tapi BELUM dijawab Admin
        Aanwijzing::create([
            'tender_id' => $tender->id,
            'vendor_id' => $vendorModels[1]->id, // CV Sukses Makmur
            'question' => 'Apakah nilai penawaran (bidding) yang dimasukkan nanti sudah harus termasuk PPN 11%?',
            'answer' => null, // Belum dijawab
            'created_at' => Carbon::now()->subHours(2),
            'updated_at' => Carbon::now()->subHours(2),
        ]);

        // Skenario 3: Vendor Pending bertanya 
        Aanwijzing::create([
            'tender_id' => $tender->id,
            'vendor_id' => $vendorModels[3]->id, // PT Menunggu Validasi
            'question' => 'Apakah akun vendor yang statusnya masih pending bisa ikut melakukan bidding di tender ini?',
            'answer' => 'Tidak bisa, vendor harus mengunggah dokumen NIB dan NPWP agar statusnya disetujui (Approved) oleh Admin sebelum batas waktu registrasi habis.',
            'created_at' => Carbon::now()->subHours(1),
            'updated_at' => Carbon::now()->subHours(1),
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat User dengan role Admin
        $user = User::create([
            'name' => 'Super Admin E-Proc',
            'email' => 'admin@eproc.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // 2. Buat Profil Admin-nya (Sesuaikan dengan $fillable di Admin.php)
        Admin::create([
            'user_id' => $user->id,
            'name' => 'Super Admin E-Proc',
            'role' => 'Super Admin', // Gunakan 'role' bukan 'employee_id'
            'is_active' => true,
        ]);
        
        $this->command->info('Akun Admin berhasil dibuat: admin@eproc.com | password: password123');
    }
}
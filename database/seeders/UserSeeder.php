<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // ------------------------------------------------------------------
        // Buat Akun Dasar untuk Sistem (Default Users)
        // ------------------------------------------------------------------

        // Akun 1: Admin (Akses Penuh)
        User::create([
            'name'     => 'Admin',
            'password' => Hash::make('password123'), // Password harus di-hash (enkripsi)
            'role'     => 'admin',
        ]);

        // Akun 2: Petugas (Bisa membuat aduan dan melihat aduannya sendiri)
        User::create([
            'name'     => 'Petugas',
            'password' => Hash::make('password123'),
            'role'     => 'petugas',
        ]);

        // Akun 3: Pimpinan (Bisa memantau dan merespon aduan)
        User::create([
            'name'     => 'Pimpinan',
            'password' => Hash::make('password123'),
            'role'     => 'pimpinan',
        ]);
    }
}
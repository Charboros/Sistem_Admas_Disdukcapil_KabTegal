<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * File utama yang akan dieksekusi saat kita menjalankan perintah:
     * php artisan db:seed
     */
    public function run(): void
    {
        // Panggil seeder-seeder lain agar data diisi secara berurutan
        // Urutan pemanggilan ini penting, misalnya User harus dibuat lebih dulu 
        // sebelum Aduan karena Aduan butuh data pembuat (created_by)
        $this->call([
            UserSeeder::class,   // 1. Buat data pengguna (admin, petugas, pimpinan)
            AduanSeeder::class,  // 2. Buat data aduan palsu (dummy) dan responnya
            KonfigurasiSeeder::class, // 3. Buat data awal Kanal & Klasifikasi
        ]);
    }
}

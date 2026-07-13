<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KonfigurasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kanals = ['Instagram', 'Facebook', 'TikTok', 'Gmaps Review', 'Lainnya'];
        foreach ($kanals as $k) {
            \App\Models\Kanal::firstOrCreate(['nama' => $k]);
        }

        $klasifikasis = [
            'Pelayanan Pencatatan Sipil',
            'Pelayanan Pendaftaran Penduduk',
            'Rekam/Cetak/KTP/KIA',
            'Infrastruktur',
            'Lainnya',
        ];
        foreach ($klasifikasis as $kl) {
            \App\Models\Klasifikasi::firstOrCreate(['nama' => $kl]);
        }
    }
}

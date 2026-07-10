<?php

namespace Database\Seeders;

use App\Models\Aduan;
use App\Models\User;
use App\Models\ResponAduan;
use Illuminate\Database\Seeder;

class AduanSeeder extends Seeder
{
    public function run(): void
    {
        $petugas = User::where('role', 'petugas')->first() ?? User::first();
        $admin   = User::where('role', 'admin')->first() ?? User::first();
        $kabid   = User::where('role', 'kabid')->first() ?? User::first();

        // Siapkan array berisi contoh-contoh data aduan palsu (dummy data)
        // Data ini nantinya akan dilooping (diulang) dan disimpan ke database
        $data = [
            [
                'kanal'          => 'Instagram',
                'klasifikasi'    => 'Pelayanan Pencatatan Sipil',
                'nama_akun'      => 'Siti Aminah',
                'isi_aduan'      => 'Warga atas nama Siti Aminah melaporkan kesulitan mengurus akta kelahiran anaknya karena dokumen persyaratan kurang jelas.',

                'sudah_direspon' => true,
                'tanggal_aduan'  => now()->subDays(3)->toDateString(),
                'waktu_aduan'    => '09:30',
                'screenshot'     => null,
                'created_by'     => $petugas->id,
                'respon'         => [
                    [
                        'respon_by' => $petugas->id,
                        'isi_respon' => 'Terima kasih atas laporan Anda. Dokumen persyaratan sudah kami kirimkan melalui pesan langsung.',
                        'tanggal_respon' => now()->subDays(3)->addHours(2),
                    ]
                ]
            ],
            [
                'kanal'          => 'Facebook',
                'klasifikasi'    => 'Rekam/Cetak/KTP/KIA',
                'nama_akun'      => 'Budi Santoso',
                'isi_aduan'      => 'Saya mengeluhkan proses perekaman KTP yang terlalu lama, sudah 2 minggu belum selesai.',

                'sudah_direspon' => false,
                'tanggal_aduan'  => now()->subDays(1)->toDateString(),
                'waktu_aduan'    => '14:15',
                'screenshot'     => null,
                'created_by'     => $petugas->id,
            ],
            [
                'kanal'          => 'Gmaps Review',
                'klasifikasi'    => 'Pelayanan Pendaftaran Penduduk',
                'nama_akun'      => 'Joko Tingkir',
                'isi_aduan'      => 'Antrian panjang dan sistem nomor antrian yang tidak tertib. Tolong diperbaiki fasilitasnya.',

                'sudah_direspon' => true,
                'tanggal_aduan'  => now()->subDays(7)->toDateString(),
                'waktu_aduan'    => '10:00',
                'screenshot'     => null,
                'created_by'     => $admin->id,
                'respon'         => [
                    [
                        'respon_by' => $kabid->id,
                        'isi_respon' => 'Kami mohon maaf atas ketidaknyamanan ini. Kami sedang memperbaiki sistem antrian dan memperluas ruang tunggu.',
                        'tanggal_respon' => now()->subDays(6)->addHours(1),
                    ]
                ]
            ],
            [
                'kanal'          => 'Instagram',
                'klasifikasi'    => 'Infrastruktur',
                'nama_akun'      => 'Rina Wijaya',
                'isi_aduan'      => 'Kondisi toilet di kantor pelayanan kurang bersih dan perlu perhatian segera.',

                'sudah_direspon' => false,
                'tanggal_aduan'  => now()->subDays(10)->toDateString(),
                'waktu_aduan'    => '11:30',
                'screenshot'     => null,
                'created_by'     => $petugas->id,
            ],
            [
                'kanal'          => 'Lainnya',
                'klasifikasi'    => 'Lainnya: Pertanyaan Umum',
                'nama_akun'      => 'Andi Prasetyo',
                'isi_aduan'      => 'Apakah besok buka melayani legalisir KK?',

                'sudah_direspon' => true,
                'tanggal_aduan'  => now()->subDays(12)->toDateString(),
                'waktu_aduan'    => '08:45',
                'screenshot'     => null,
                'created_by'     => $petugas->id,
                'respon'         => [
                    [
                        'respon_by' => $petugas->id,
                        'isi_respon' => 'Halo Bapak Andi, untuk besok kami tetap buka melayani legalisir KK dari jam 08.00 sampai 14.00. Terima kasih.',
                        'tanggal_respon' => now()->subDays(12)->addMinutes(30),
                    ]
                ]
            ],
        ];

        // ------------------------------------------------------------------
        // Looping untuk Menyimpan Data Dummy ke Database
        // ------------------------------------------------------------------
        foreach ($data as $item) {
            // Pisahkan data respon dari array utama agar tidak error saat aduan disimpan
            // karena tabel 'aduans' tidak punya kolom 'respon'
            $responData = $item['respon'] ?? [];
            unset($item['respon']);

            // Simpan atau perbarui aduan berdasarkan isi_aduan
            $aduan = Aduan::updateOrCreate(
                ['isi_aduan' => $item['isi_aduan']],
                $item
            );

            // Jika aduan ini statusnya 'sudah_direspon' dan ada data responnya,
            // maka simpan juga data respon tersebut ke tabel 'respon_aduans'
            if ($aduan->sudah_direspon && count($responData) > 0 && $aduan->respon()->count() == 0) {
                foreach ($responData as $r) {
                    $aduan->respon()->create($r);
                }
            }
        }
    }
}

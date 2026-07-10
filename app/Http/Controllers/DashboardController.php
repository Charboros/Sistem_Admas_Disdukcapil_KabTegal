<?php

namespace App\Http\Controllers;

use App\Models\Aduan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // ------------------------------------------------------------------
        // 1. Inisialisasi Base Query
        // ------------------------------------------------------------------
        // Kita menggunakan scope 'forUser' agar data yang diambil 
        // hanya yang diizinkan untuk dilihat oleh user yang sedang login.
        $base = Aduan::forUser($user);

        // ------------------------------------------------------------------
        // 2. Statistik Keseluruhan (Sepanjang Waktu)
        // ------------------------------------------------------------------
        // clone $base digunakan agar query dasarnya (yang difilter forUser)
        // tidak tertimpa/berubah saat kita menjalankan query ini.
        $statsGlobal = (clone $base)->selectRaw('
            count(*) as total,
            sum(case when sudah_direspon = 1 then 1 else 0 end) as sudah,
            sum(case when sudah_direspon = 0 then 1 else 0 end) as belum
        ')->first();

        $totalAduan         = (int) ($statsGlobal->total ?? 0);
        $aduanSudahDirespon = (int) ($statsGlobal->sudah ?? 0);
        $aduanBelumDirespon = (int) ($statsGlobal->belum ?? 0);

        // ------------------------------------------------------------------
        // 3. Persiapan Filter Tahun
        // ------------------------------------------------------------------
        // Ambil tahun dari request (URL), jika tidak ada gunakan tahun berjalan
        $tahunDipilih = (int) $request->get('tahun', date('Y'));
        
        // Ambil daftar tahun apa saja yang ada di database aduan
        $daftarTahun = Aduan::getDaftarTahun(clone $base);

        // Jika tabel aduan masih kosong, defaultkan ke tahun saat ini
        if (empty($daftarTahun)) {
            $daftarTahun = [(int) date('Y')];
        }

        // ------------------------------------------------------------------
        // 4. Statistik Spesifik Untuk Tahun yang Dipilih
        // ------------------------------------------------------------------
        $statsTahunIni = (clone $base)->inYear($tahunDipilih)->selectRaw('
            count(*) as total,
            sum(case when sudah_direspon = 1 then 1 else 0 end) as sudah,
            sum(case when sudah_direspon = 0 then 1 else 0 end) as belum
        ')->first();

        $totalTahunIni         = (int) ($statsTahunIni->total ?? 0);
        $sudahDiresponTahunIni = (int) ($statsTahunIni->sudah ?? 0);
        $belumDiresponTahunIni = (int) ($statsTahunIni->belum ?? 0);

        // ------------------------------------------------------------------
        // 5. Data Chart & Grafik
        // ------------------------------------------------------------------
        $perKanal       = (clone $base)->perKanal($tahunDipilih)->get();
        $perKlasifikasi = (clone $base)->perKlasifikasi($tahunDipilih)->get();
        
        // Data format bulan untuk grafik garis (line chart)
        $dataBulan      = Aduan::dataBulanFormatted(clone $base, $tahunDipilih);
        
        // Tren keseluruhan tahun per tahun
        $trenTahunan    = Aduan::getTrenTahunan(clone $base);

        return view('dashboard', compact(
            'totalAduan',
            'aduanSudahDirespon',
            'aduanBelumDirespon',
            'tahunDipilih',
            'daftarTahun',
            'totalTahunIni',
            'sudahDiresponTahunIni',
            'belumDiresponTahunIni',
            'perKanal',
            'perKlasifikasi',
            'dataBulan',
            'trenTahunan',
        ));
    }
}

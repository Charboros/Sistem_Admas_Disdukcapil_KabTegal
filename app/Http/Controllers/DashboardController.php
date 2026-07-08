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

        // === STATISTIK KESELURUHAN ===
        if ($user->role === 'petugas') {
            $query = Aduan::where('created_by', $user->id);
        } else {
            $query = Aduan::query();
        }

        $totalAduan         = (clone $query)->count();
        $aduanSudahDirespon = (clone $query)->where('sudah_direspon', true)->count();
        $aduanBelumDirespon = (clone $query)->where('sudah_direspon', false)->count();

        // === FILTER TAHUN ===
        $tahunDipilih = $request->get('tahun', date('Y'));

        $daftarTahun = (clone $query)
            ->selectRaw('YEAR(tanggal_aduan) as tahun')
            ->distinct()
            ->whereNotNull('tanggal_aduan')
            ->orderBy('tahun', 'desc')
            ->pluck('tahun')
            ->toArray();

        if (empty($daftarTahun)) {
            $daftarTahun = [(int) date('Y')];
        }

        // === TOTAL TAHUN INI ===
        $totalTahunIni         = (clone $query)->whereYear('tanggal_aduan', $tahunDipilih)->count();
        $sudahDiresponTahunIni = (clone $query)->whereYear('tanggal_aduan', $tahunDipilih)->where('sudah_direspon', true)->count();
        $belumDiresponTahunIni = (clone $query)->whereYear('tanggal_aduan', $tahunDipilih)->where('sudah_direspon', false)->count();

        // === PER KANAL ===
        $perKanal = (clone $query)
            ->selectRaw('COALESCE(kanal, "Tidak Diketahui") as kanal, COUNT(*) as jumlah')
            ->whereYear('tanggal_aduan', $tahunDipilih)
            ->groupBy('kanal')
            ->orderBy('jumlah', 'desc')
            ->get();

        // === PER KLASIFIKASI ===
        $perKlasifikasi = (clone $query)
            ->selectRaw('COALESCE(klasifikasi, "Tidak Diketahui") as klasifikasi, COUNT(*) as jumlah')
            ->whereYear('tanggal_aduan', $tahunDipilih)
            ->groupBy('klasifikasi')
            ->orderBy('jumlah', 'desc')
            ->get();

        // === PER BULAN ===
        $perBulanRaw = (clone $query)
            ->selectRaw('MONTH(tanggal_aduan) as bulan, COUNT(*) as jumlah')
            ->whereYear('tanggal_aduan', $tahunDipilih)
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get()
            ->keyBy('bulan');

        $namaBulan = [
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
            5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Agt',
            9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des',
        ];

        $dataBulan = [];
        for ($b = 1; $b <= 12; $b++) {
            $dataBulan[] = [
                'bulan'  => $namaBulan[$b],
                'jumlah' => isset($perBulanRaw[$b]) ? (int) $perBulanRaw[$b]->jumlah : 0,
            ];
        }

        // === TREN TAHUNAN ===
        $trenTahunan = (clone $query)
            ->selectRaw('YEAR(tanggal_aduan) as tahun, COUNT(*) as jumlah')
            ->whereNotNull('tanggal_aduan')
            ->groupBy('tahun')
            ->orderBy('tahun')
            ->get();

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
            'trenTahunan'
        ));
    }
}

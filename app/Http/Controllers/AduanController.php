<?php

namespace App\Http\Controllers;

use App\Models\Aduan;
use App\Exports\AduanExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class AduanController extends Controller
{
    // =========================================================
    // Referensi kanal & klasifikasi
    // =========================================================

    public static function listKanal(): array
    {
        return ['IG', 'FB', 'Gmaps Review', 'Lainnya'];
    }

    public static function listKlasifikasi(): array
    {
        return [
            'Pelayanan Pencatatan Sipil',
            'Pelayanan Pendaftaran Penduduk',
            'Rekam/Cetak/KTP/KIA',
            'Infrastruktur',
            'Lainnya',
        ];
    }

    // =========================================================
    // Halaman Input Aduan (Form)
    // =========================================================

    public function create()
    {
        $listKanal       = self::listKanal();
        $listKlasifikasi = self::listKlasifikasi();

        return view('aduan.create', compact('listKanal', 'listKlasifikasi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kanal'          => 'required|string',
            'klasifikasi'    => 'required|string',
            'isi_aduan'      => 'required|string',
            'caption'        => 'nullable|string|max:500',
            'tanggal_aduan'  => 'required|date',
            'waktu_aduan'    => 'nullable|date_format:H:i',
            'screenshot'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $nextId     = (Aduan::max('id') ?? 0) + 1;
        $nomorAduan = 'ADU-' . date('Y') . '-' . str_pad($nextId, 3, '0', STR_PAD_LEFT);

        $screenshotPath = null;
        if ($request->hasFile('screenshot')) {
            $screenshotPath = $request->file('screenshot')->store('screenshots', 'public');
        }

        // Ambil klasifikasi: jika "Lainnya", gabung dengan keterangan
        $klasifikasi = $request->klasifikasi;
        if ($klasifikasi === 'Lainnya' && $request->filled('klasifikasi_lainnya')) {
            $klasifikasi = 'Lainnya: ' . $request->klasifikasi_lainnya;
        }

        $kanal = $request->kanal;
        if ($kanal === 'Lainnya' && $request->filled('kanal_lainnya')) {
            $kanal = 'Lainnya: ' . $request->kanal_lainnya;
        }

        Aduan::create([
            'nomor_aduan'    => $nomorAduan,
            'kanal'          => $kanal,
            'klasifikasi'    => $klasifikasi,
            'nama_akun'      => $request->nama_akun,
            'isi_aduan'      => $request->isi_aduan,
            'caption'        => $request->caption,
            'tanggal_aduan'  => $request->tanggal_aduan,
            'waktu_aduan'    => $request->waktu_aduan,
            'screenshot_path'=> $screenshotPath,
            'sudah_direspon' => false,
            'created_by'     => Auth::id(),
        ]);

        return back()->with('success', 'Aduan berhasil disimpan dengan nomor ' . $nomorAduan . '.');
    }

    // =========================================================
    // Halaman Data Aduan (Daftar + Respon)
    // =========================================================

    public function data(Request $request)
    {
        $query = Aduan::with(['petugas', 'respon.user'])->forUser(Auth::user());

        if ($request->filled('status')) {
            $status = $request->status === 'sudah' ? true : false;
            $query->where('sudah_direspon', $status);
        }
        if ($request->filled('kanal')) {
            $query->where('kanal', $request->kanal);
        }
        if ($request->filled('tahun')) {
            $query->whereYear('tanggal_aduan', $request->tahun);
        }

        $aduans = $query->orderBy('created_at', 'desc')->get();
        $listKanal = self::listKanal();
        $listTahun = Aduan::daftarTahun()->pluck('tahun');

        return view('aduan.data', compact('aduans', 'listKanal', 'listTahun'));
    }

    // =========================================================
    // Halaman Detail Aduan (Read-only)
    // =========================================================

    public function show(Aduan $aduan)
    {
        $aduan->load(['petugas', 'respon.user']);
        return view('aduan.show', compact('aduan'));
    }

    // =========================================================
    // Hapus (Admin only)
    // =========================================================

    public function destroy(Aduan $aduan)
    {
        if (! Auth::user()->isAdmin()) {
            return back()->with('error', 'Hanya admin yang dapat menghapus aduan.');
        }

        if ($aduan->screenshot_path) {
            Storage::disk('public')->delete($aduan->screenshot_path);
        }

        $nomor = $aduan->nomor_aduan;
        $aduan->delete();

        return back()->with('success', 'Aduan ' . $nomor . ' berhasil dihapus.');
    }

    // =========================================================
    // Export Excel
    // =========================================================

    public function export(Request $request)
    {
        $filename = 'Rekap_Aduan_Disdukcapil_' . date('Ymd_His') . '.xlsx';
        return Excel::download(new AduanExport($request), $filename);
    }
}

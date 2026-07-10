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
        return ['Instagram', 'Facebook', 'TikTok', 'Gmaps Review', 'Lainnya'];
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

            'tanggal_aduan'  => 'required|date',
            'waktu_aduan'    => 'nullable|date_format:H:i',
            'screenshot'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $screenshotData = null;
        if ($request->hasFile('screenshot')) {
            $screenshotData = file_get_contents($request->file('screenshot')->getRealPath());
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

        // ------------------------------------------------------------------
        // Simpan Data Aduan Baru ke Database
        // ------------------------------------------------------------------
        // Menggunakan method create() dari Eloquent ORM. Pastikan field
        // yang diisi sudah terdaftar di property $fillable pada model Aduan.
        $aduan = Aduan::create([
            'kanal'          => $kanal,
            'klasifikasi'    => $klasifikasi,
            'nama_akun'      => $request->nama_akun,
            'isi_aduan'      => $request->isi_aduan,

            'tanggal_aduan'  => $request->tanggal_aduan,
            'waktu_aduan'    => $request->waktu_aduan,
            'screenshot'     => $screenshotData,
            'sudah_direspon' => false,      // Nilai awal saat aduan baru dibuat selalu false (belum direspon)
            'created_by'     => Auth::id(), // ID dari user yang sedang login saat menyimpan data
        ]);

        return back()->with('success', 'Aduan berhasil disimpan dengan ID ' . $aduan->id . '.');
    }

    // =========================================================
    // Halaman Data Aduan (Daftar + Respon)
    // =========================================================

    public function data(Request $request)
    {
        $aduans = Aduan::with(['petugas', 'respon.user'])
            ->forUser(Auth::user())
            ->filterAduan($request)
            ->orderBy('created_at', 'desc')
            ->get();

        $listKanal = self::listKanal();
        $listTahun = Aduan::getDaftarTahun(Aduan::query());

        return view('aduan.data', compact('aduans', 'listKanal', 'listTahun'));
    }

    // =========================================================
    // Halaman Detail Aduan (Read-only) & Gambar
    // =========================================================

    public function image(Aduan $aduan)
    {
        if (!$aduan->screenshot) {
            abort(404);
        }

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->buffer($aduan->screenshot) ?: 'image/jpeg';

        return response($aduan->screenshot)
            ->header('Content-Type', $mime)
            ->header('Cache-Control', 'public, max-age=31536000'); // Cache 1 tahun di browser
    }

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

        $id = $aduan->id;
        $aduan->delete();

        return back()->with('success', 'Aduan #' . $id . ' berhasil dihapus.');
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

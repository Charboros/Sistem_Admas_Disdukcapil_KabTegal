<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Aduan extends Model
{
    // Daftar kolom tabel `aduans` yang diizinkan untuk diisi secara massal (Mass Assignment)
    // Field yang tidak ada di sini tidak akan bisa disimpan via fungsi create()
    protected $fillable = [
        'kanal',
        'klasifikasi',
        'nama_akun',
        'isi_aduan',

        'tanggal_aduan',
        'waktu_aduan',
        'screenshot',
        'sudah_direspon',
        'isi_respon_awal',
        'created_by',
    ];

    protected $casts = [
        'sudah_direspon' => 'boolean',
        'tanggal_aduan'  => 'date',
    ];

    // =========================================================
    // Helpers
    // =========================================================

    // datePartExpression sudah tidak digunakan karena kita pakai whereBetween untuk optimasi index

    // =========================================================
    // 1. Relasi Antar Tabel (Relationships)
    // =========================================================

    // Menghubungkan aduan ini ke tabel User (Petugas yang mencatat aduan)
    // 1 Aduan hanya dimiliki/dicatat oleh 1 Petugas (belongsTo)
    public function petugas()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Menghubungkan aduan ini ke tabel ResponAduan
    // 1 Aduan bisa memiliki banyak Respon (hasMany)
    public function respon()
    {
        return $this->hasMany(ResponAduan::class);
    }

    // =========================================================
    // 2. Query Scopes (Fungsi Bantuan Pencarian)
    // =========================================================
    // Scope mempermudah kita membuat kondisi pencarian yang sering dipakai
    // sehingga controller kita terlihat lebih rapi. (Ditandai dengan awalan 'scope')

    /** 
     * Membatasi agar akun petugas hanya melihat aduan yang ia input sendiri. 
     * Penggunaan di Controller: Aduan::forUser($user)
     */
    public function scopeForUser(Builder $query, User $user): Builder
    {
        // Menampilkan semua data ke semua user (termasuk petugas)
        // agar perhitungan total (dashboard) & data tabel sinkron antar akun.
        return $query;
    }

    public function scopePerKanal(Builder $query, int $tahun): Builder
    {
        return $query
            ->selectRaw('COALESCE(kanal, "Tidak Diketahui") as kanal, COUNT(*) as jumlah')
            ->whereBetween('tanggal_aduan', ["{$tahun}-01-01", "{$tahun}-12-31"])
            ->groupBy('kanal')
            ->orderBy('jumlah', 'desc');
    }

    public function scopePerKlasifikasi(Builder $query, int $tahun): Builder
    {
        return $query
            ->selectRaw('COALESCE(klasifikasi, "Tidak Diketahui") as klasifikasi, COUNT(*) as jumlah')
            ->whereBetween('tanggal_aduan', ["{$tahun}-01-01", "{$tahun}-12-31"])
            ->groupBy('klasifikasi')
            ->orderBy('jumlah', 'desc');
    }

    public static function dataBulanFormatted(Builder $query, int $tahun): array
    {
        $driver = \Illuminate\Support\Facades\DB::connection()->getDriverName();
        $monthSql = $driver === 'sqlite' ? "cast(strftime('%m', tanggal_aduan) as integer)" : "MONTH(tanggal_aduan)";

        $rawAduans = (clone $query)
            ->selectRaw("{$monthSql} as bulan, COUNT(*) as jumlah")
            ->whereBetween('tanggal_aduan', ["{$tahun}-01-01", "{$tahun}-12-31"])
            ->groupBy(\Illuminate\Support\Facades\DB::raw($monthSql))
            ->pluck('jumlah', 'bulan')
            ->toArray();

        $namaBulan = [
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4  => 'Apr',
            5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8  => 'Agt',
            9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des',
        ];

        $result = [];
        for ($b = 1; $b <= 12; $b++) {
            $result[] = [
                'bulan'  => $namaBulan[$b],
                'jumlah' => $rawAduans[$b] ?? 0,
            ];
        }
        return $result;
    }

    public static function getTrenTahunan(Builder $query): array
    {
        $driver = \Illuminate\Support\Facades\DB::connection()->getDriverName();
        $yearSql = $driver === 'sqlite' ? "cast(strftime('%Y', tanggal_aduan) as integer)" : "YEAR(tanggal_aduan)";

        $results = (clone $query)
            ->whereNotNull('tanggal_aduan')
            ->selectRaw("{$yearSql} as tahun, COUNT(*) as jumlah")
            ->groupBy(\Illuminate\Support\Facades\DB::raw($yearSql))
            ->orderBy('tahun', 'asc')
            ->get();

        return $results->map(function ($item) {
            return (object) ['tahun' => $item->tahun, 'jumlah' => $item->jumlah];
        })->toArray();
    }

    public static function getDaftarTahun(Builder $query): array
    {
        $driver = \Illuminate\Support\Facades\DB::connection()->getDriverName();
        $yearSql = $driver === 'sqlite' ? "cast(strftime('%Y', tanggal_aduan) as integer)" : "YEAR(tanggal_aduan)";

        return (clone $query)
            ->whereNotNull('tanggal_aduan')
            ->selectRaw("{$yearSql} as tahun")
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun')
            ->toArray();
    }

    public function scopeInYear(Builder $query, int $year): Builder
    {
        return $query->whereBetween('tanggal_aduan', ["{$year}-01-01", "{$year}-12-31"]);
    }

    public function scopeFilterAduan(Builder $query, \Illuminate\Http\Request $request): Builder
    {
        return $query
            ->when($request->filled('status'), function ($q) use ($request) {
                $q->where('sudah_direspon', $request->status === 'sudah');
            })
            ->when($request->filled('kanal'), function ($q) use ($request) {
                if ($request->kanal === 'Lainnya') {
                    $q->where('kanal', 'like', 'Lainnya%');
                } else {
                    $q->where('kanal', $request->kanal);
                }
            })
            ->when($request->filled('tahun'), function ($q) use ($request) {
                $q->whereYear('tanggal_aduan', $request->tahun);
            });
    }
}

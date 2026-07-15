<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Kanal;
use App\Models\Klasifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class KonfigurasiController extends Controller
{
    public function index()
    {
        $users = User::whereIn('role', ['petugas', 'pimpinan'])->orderBy('name')->get();
        $kanals = Kanal::orderByRaw("CASE WHEN LOWER(nama) = 'lainnya' THEN 1 ELSE 0 END, nama ASC")->get();
        $klasifikasis = Klasifikasi::orderByRaw("CASE WHEN LOWER(nama) = 'lainnya' THEN 1 ELSE 0 END, nama ASC")->get();
        
        $aduanKanals = \App\Models\Aduan::select('kanal')->distinct()->whereNotNull('kanal')->pluck('kanal');
        $allKanalsAsal = $aduanKanals->concat($kanals->pluck('nama'))->unique()->sort()->values();
        $allKanalsAsal = $allKanalsAsal->reject(fn($k) => strtolower($k) === 'lainnya')->push('Lainnya')->values();

        $aduanKlasifikasis = \App\Models\Aduan::select('klasifikasi')->distinct()->whereNotNull('klasifikasi')->pluck('klasifikasi');
        $allKlasifikasisAsal = $aduanKlasifikasis->concat($klasifikasis->pluck('nama'))->unique()->sort()->values();
        $allKlasifikasisAsal = $allKlasifikasisAsal->reject(fn($k) => strtolower($k) === 'lainnya')->push('Lainnya')->values();

        return view('konfigurasi.index', compact('users', 'kanals', 'klasifikasis', 'allKanalsAsal', 'allKlasifikasisAsal'));
    }

}

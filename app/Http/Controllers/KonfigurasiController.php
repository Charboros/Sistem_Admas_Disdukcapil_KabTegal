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

    // --- MANAJEMEN USER ---
    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'role' => ['required', 'in:petugas,pimpinan'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        User::create([
            'name' => $request->name,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'User berhasil ditambahkan.');
    }

    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'role' => ['required', 'in:petugas,pimpinan'],
            'password' => ['nullable', 'confirmed', Password::defaults()],
        ]);

        $user->name = $request->name;
        $user->role = $request->role;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return back()->with('success', 'User berhasil diperbarui.');
    }

    public function destroyUser(User $user)
    {
        if ($user->isAdmin()) {
            return back()->with('error', 'Tidak dapat menghapus admin.');
        }
        
        $user->delete();
        return back()->with('success', 'User berhasil dihapus.');
    }

    // --- MANAJEMEN KANAL ---
    public function storeKanal(Request $request)
    {
        $request->validate([
            'nama' => ['required', 'string', 'max:255', 'unique:kanals,nama'],
        ]);

        Kanal::create([
            'nama' => $request->nama,
        ]);

        return back()->with('success', 'Kanal berhasil ditambahkan.');
    }

    public function destroyKanal(Kanal $kanal)
    {
        if (strtolower($kanal->nama) === 'lainnya') {
            return back()->with('error', 'Kanal "Lainnya" adalah opsi bawaan sistem dan tidak boleh dihapus.');
        }

        $kanal->delete();
        return back()->with('success', 'Kanal berhasil dihapus.');
    }

    public function mergeKanal(Request $request)
    {
        $request->validate([
            'kanal_asal' => 'required|string',
            'kanal_tujuan' => 'required|string|different:kanal_asal',
        ]);

        \App\Models\Aduan::where('kanal', $request->kanal_asal)
            ->update(['kanal' => $request->kanal_tujuan]);

        return back()->with('success', 'Data kanal berhasil digabungkan.');
    }

    // --- MANAJEMEN KLASIFIKASI ---
    public function storeKlasifikasi(Request $request)
    {
        $request->validate([
            'nama' => ['required', 'string', 'max:255', 'unique:klasifikasis,nama'],
        ]);

        Klasifikasi::create([
            'nama' => $request->nama,
        ]);

        return back()->with('success', 'Klasifikasi berhasil ditambahkan.');
    }

    public function destroyKlasifikasi(Klasifikasi $klasifikasi)
    {
        if (strtolower($klasifikasi->nama) === 'lainnya') {
            return back()->with('error', 'Klasifikasi "Lainnya" adalah opsi bawaan sistem dan tidak boleh dihapus.');
        }

        $klasifikasi->delete();
        return back()->with('success', 'Klasifikasi berhasil dihapus.');
    }

    public function mergeKlasifikasi(Request $request)
    {
        $request->validate([
            'klasifikasi_asal' => 'required|string',
            'klasifikasi_tujuan' => 'required|string|different:klasifikasi_asal',
        ]);

        \App\Models\Aduan::where('klasifikasi', $request->klasifikasi_asal)
            ->update(['klasifikasi' => $request->klasifikasi_tujuan]);

        return back()->with('success', 'Data klasifikasi berhasil digabungkan.');
    }
}

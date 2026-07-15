<?php

namespace App\Http\Controllers;

use App\Models\Klasifikasi;
use Illuminate\Http\Request;

class KlasifikasiController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nama' => ['required', 'string', 'max:255', 'unique:klasifikasis,nama'],
        ]);

        Klasifikasi::create([
            'nama' => $request->nama,
        ]);

        return back()->with('success', 'Klasifikasi berhasil ditambahkan.');
    }

    public function destroy(Klasifikasi $klasifikasi)
    {
        if (strtolower($klasifikasi->nama) === 'lainnya') {
            return back()->with('error', 'Klasifikasi "Lainnya" adalah opsi bawaan sistem dan tidak boleh dihapus.');
        }

        $klasifikasi->delete();
        return back()->with('success', 'Klasifikasi berhasil dihapus.');
    }

    public function merge(Request $request)
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

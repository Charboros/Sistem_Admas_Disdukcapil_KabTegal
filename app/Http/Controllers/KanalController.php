<?php

namespace App\Http\Controllers;

use App\Models\Kanal;
use Illuminate\Http\Request;

class KanalController extends Controller
{
    public function store(\App\Http\Requests\StoreKanalRequest $request)
    {
        Kanal::create([
            'nama' => $request->nama,
        ]);

        return back()->with('success', 'Kanal berhasil ditambahkan.');
    }

    public function destroy(Kanal $kanal)
    {
        if (strtolower($kanal->nama) === 'lainnya') {
            return back()->with('error', 'Kanal "Lainnya" adalah opsi bawaan sistem dan tidak boleh dihapus.');
        }

        $kanal->delete();
        return back()->with('success', 'Kanal berhasil dihapus.');
    }

    public function merge(\App\Http\Requests\MergeKanalRequest $request)
    {
        \App\Models\Aduan::where('kanal', $request->kanal_asal)
            ->update(['kanal' => $request->kanal_tujuan]);

        return back()->with('success', 'Data kanal berhasil digabungkan.');
    }
}

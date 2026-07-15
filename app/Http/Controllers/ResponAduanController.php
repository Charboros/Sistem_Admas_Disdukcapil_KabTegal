<?php

namespace App\Http\Controllers;

use App\Models\Aduan;
use App\Models\ResponAduan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResponAduanController extends Controller
{
    public function store(\App\Http\Requests\StoreResponRequest $request, Aduan $aduan)
    {

        // ------------------------------------------------------------------
        // 2. Simpan Data Respon ke Database
        // ------------------------------------------------------------------
        ResponAduan::create([
            'aduan_id'       => $aduan->id,       // Menghubungkan respon ini dengan aduan yang mana
            'isi_respon'     => $request->isi_respon,
            'tanggal_respon' => date('Y-m-d'),    // Tanggal saat ini
            'respon_by'      => Auth::id(),       // ID petugas/admin yang memberikan respon
        ]);

        // ------------------------------------------------------------------
        // 3. Update Status Aduan
        // ------------------------------------------------------------------
        // Ubah status aduan menjadi "sudah direspon" (true)
        $aduan->update([
            'sudah_direspon'  => true,
        ]);

        return back()->with('success', 'Respon untuk aduan ' . $aduan->nomor_aduan . ' berhasil dikirim.');
    }
}

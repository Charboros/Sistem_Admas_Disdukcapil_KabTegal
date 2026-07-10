<?php

namespace App\Http\Controllers;

use App\Models\Aduan;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $aduans = Aduan::with('petugas')
            ->filterAduan($request)
            ->orderBy('tanggal_aduan', 'desc')
            ->get();

        $listKanal = \App\Http\Controllers\AduanController::listKanal();
        $listTahun = Aduan::getDaftarTahun(Aduan::query());

        return view('laporan.index', compact('aduans', 'listKanal', 'listTahun'));
    }
}

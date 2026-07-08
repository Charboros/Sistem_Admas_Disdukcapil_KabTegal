<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AduanController;
use App\Http\Controllers\ResponAduanController;
use App\Http\Controllers\LaporanController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Aduan
    Route::get('/aduan/export', [AduanController::class, 'export'])->name('aduan.export');
    Route::resource('aduan', AduanController::class);
    Route::post('/aduan/{aduan}/respon', [ResponAduanController::class, 'store'])->name('aduan.respon.store');

    // Laporan dialihkan ke Dashboard (sudah digabung)
    Route::get('/laporan', function () {
        return redirect()->route('dashboard');
    })->name('laporan.index');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

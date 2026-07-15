<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AduanController;
use App\Http\Controllers\ResponAduanController;
use App\Http\Controllers\LaporanController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');

    Route::get('/aduan/input', [AduanController::class, 'create'])->name('aduan.create');
    Route::post('/aduan/input', [AduanController::class, 'store'])->name('aduan.store');
    Route::get('/aduan/data', [AduanController::class, 'data'])->name('aduan.data');
    Route::get('/aduan/{aduan}', [AduanController::class, 'show'])->name('aduan.show');
    Route::get('/aduan/{aduan}/image', [AduanController::class, 'image'])->name('aduan.image');
    Route::delete('/aduan/{aduan}', [AduanController::class, 'destroy'])->name('aduan.destroy');

    Route::post('/aduan/{aduan}/respon', [ResponAduanController::class, 'store'])->name('aduan.respon.store');

    Route::get('/rekap', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/rekap/export', [AduanController::class, 'export'])->name('aduan.export');

    Route::middleware(['admin'])->prefix('konfigurasi')->name('konfigurasi.')->group(function () {
        Route::get('/', [\App\Http\Controllers\KonfigurasiController::class, 'index'])->name('index');
        
        $userController = \App\Http\Controllers\UserController::class;
        Route::post('/user', [$userController, 'store'])->name('user.store');
        Route::put('/user/{user}', [$userController, 'update'])->name('user.update');
        Route::delete('/user/{user}', [$userController, 'destroy'])->name('user.destroy');
        
        $kanalController = \App\Http\Controllers\KanalController::class;
        Route::post('/kanal', [$kanalController, 'store'])->name('kanal.store');
        Route::post('/kanal/merge', [$kanalController, 'merge'])->name('kanal.merge');
        Route::delete('/kanal/{kanal}', [$kanalController, 'destroy'])->name('kanal.destroy');
        
        $klasifikasiController = \App\Http\Controllers\KlasifikasiController::class;
        Route::post('/klasifikasi', [$klasifikasiController, 'store'])->name('klasifikasi.store');
        Route::post('/klasifikasi/merge', [$klasifikasiController, 'merge'])->name('klasifikasi.merge');
        Route::delete('/klasifikasi/{klasifikasi}', [$klasifikasiController, 'destroy'])->name('klasifikasi.destroy');
    });
});

require __DIR__ . '/auth.php';

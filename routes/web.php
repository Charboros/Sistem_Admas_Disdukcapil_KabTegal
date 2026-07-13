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
        $konfigController = \App\Http\Controllers\KonfigurasiController::class;
        Route::get('/', [$konfigController, 'index'])->name('index');
        
        Route::post('/user', [$konfigController, 'storeUser'])->name('user.store');
        Route::delete('/user/{user}', [$konfigController, 'destroyUser'])->name('user.destroy');
        
        Route::post('/kanal', [$konfigController, 'storeKanal'])->name('kanal.store');
        Route::post('/kanal/merge', [$konfigController, 'mergeKanal'])->name('kanal.merge');
        Route::delete('/kanal/{kanal}', [$konfigController, 'destroyKanal'])->name('kanal.destroy');
        
        Route::post('/klasifikasi', [$konfigController, 'storeKlasifikasi'])->name('klasifikasi.store');
        Route::post('/klasifikasi/merge', [$konfigController, 'mergeKlasifikasi'])->name('klasifikasi.merge');
        Route::delete('/klasifikasi/{klasifikasi}', [$konfigController, 'destroyKlasifikasi'])->name('klasifikasi.destroy');
    });
});

require __DIR__ . '/auth.php';

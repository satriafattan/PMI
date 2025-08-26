<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Public\PemesananController as PublicPemesanan;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PemesananController;
use App\Http\Controllers\Admin\RekapStokController;
use App\Http\Controllers\Admin\VerifikasiPemesananController;
use App\Http\Controllers\Admin\RiwayatController;
use App\Http\Controllers\Public\PublicPemesananController;

// Halaman landing
Route::view('/', 'welcome')->name('home');

// Halaman about
Route::get('/about', fn() => view('about'))->name('about');

// Publik: pemesan non-login
Route::get('/pemesanan', [PublicPemesananController::class,'create'])->name('pemesanan.create');
Route::post('/pemesanan', [PublicPemesananController::class,'store'])->name('pemesanan.store');

// Auth admin
Route::get('/admin/login', [AuthController::class,'showLogin'])->name('admin.login');
Route::post('/admin/login', [AuthController::class,'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AuthController::class,'logout'])->name('admin.logout');

// Admin area
Route::middleware('auth:admin')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class,'index'])->name('dashboard');

    // Pemesanan (CMS)
    Route::resource('pemesanan', PemesananController::class)->only(['index','create','store','show','edit','update','destroy']);

    // Rekap Stok
    Route::resource('rekap-stok', RekapStokController::class)->except(['show']);

    // Verifikasi
    Route::get('verifikasi', [VerifikasiPemesananController::class,'index'])->name('verifikasi.index');
    Route::post('verifikasi/{pemesanan}', [VerifikasiPemesananController::class,'store'])->name('verifikasi.store');
    Route::patch('verifikasi/{verifikasi}/status', [VerifikasiPemesananController::class,'updateStatus'])->name('verifikasi.updateStatus');

    // Riwayat
    Route::get('riwayat', [RiwayatController::class,'index'])->name('riwayat.index');
});

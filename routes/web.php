<?php

use Illuminate\Support\Facades\Route;

// === Public Controllers ===
use App\Http\Controllers\Public\PublicPemesananController;
use App\Http\Controllers\Public\EventScheduleController;
// use App\Http\Controllers\Public\StokController;

// === Admin Controllers ===
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PemesananController;
use App\Http\Controllers\Admin\RekapStokController;
use App\Http\Controllers\Admin\StokDarahController;
use App\Http\Controllers\Admin\VerifikasiPemesananController;
use App\Http\Controllers\Admin\RiwayatController;

/*
|--------------------------------------------------------------------------
| Landing & Static Pages
|--------------------------------------------------------------------------
*/
Route::view('/', 'welcome')->name('home');
Route::get('/about', fn() => view('about'))->name('about');

/*
|--------------------------------------------------------------------------
| Public: Pemesanan (Non-Login)
|--------------------------------------------------------------------------
| Alur:
|  - GET  /pemesanan               -> form pemesanan
|  - POST /pemesanan               -> simpan (status 'pending'), catat riwayat
|  - GET  /pemesanan/konfirmasi/{kode} -> ringkasan + tracking status by 'kode'
*/
Route::get('/pemesanan', [PublicPemesananController::class, 'create'])->name('pemesanan.create');
Route::post('/pemesanan', [PublicPemesananController::class, 'store'])->name('pemesanan.store');
Route::get('/pemesanan/konfirmasi/{kode}', [PublicPemesananController::class, 'konfirmasi'])
    ->name('pemesanan.konfirmasi');

/*
|--------------------------------------------------------------------------
| Public: Penjadwalan Event & Stok Darah
|--------------------------------------------------------------------------
*/
Route::get('/jadwal-event',  [EventScheduleController::class, 'create'])->name('public.event.create');
Route::post('/jadwal-event', [EventScheduleController::class, 'store'])->name('public.event.store');

// Route::get('/stok', StokController::class)->name('stok');

/*
|--------------------------------------------------------------------------
| Admin Auth
|--------------------------------------------------------------------------
*/
Route::get('/admin/login',   [AuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login',  [AuthController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

/*
|--------------------------------------------------------------------------
| Admin Area (guard: admin)
|--------------------------------------------------------------------------
| Catatan Verifikasi:
|   - GET    admin/verifikasi                 -> list pemesanan (pending/approved/rejected)
|   - POST   admin/verifikasi/{pemesanan}     -> buat entri verifikasi (approved/rejected),
|                                                SEKALIGUS update status di 'pemesanan_darah'
|   - PATCH  admin/verifikasi/{verifikasi}/status -> ubah status entri verifikasi (mis. koreksi)
|
| Kontrak parameter:
|   {pemesanan}   -> binding ke model PemesananDarah (default by id)
|   {verifikasi}  -> binding ke model VerifikasiPemesanan (default by id)
*/
Route::middleware('auth:admin')->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Pemesanan (CMS)
    Route::resource('pemesanan', PemesananController::class)
        ->only(['index','create','store','show','edit','update','destroy']);

    // Rekap Stok
    Route::resource('rekap-stok', RekapStokController::class)->except(['show']);

    // Stok Darah
    Route::resource('stok-darah', StokDarahController::class)->except(['show']);
    Route::get('/stok',    [StokDarahController::class, 'index'])->name('stok.index');
    Route::post('/stok',   [StokDarahController::class, 'store'])->name('stok.store');

    // Verifikasi Pemesanan
    Route::get('verifikasi',                         [VerifikasiPemesananController::class, 'index'])->name('verifikasi.index');
    Route::post('verifikasi/{pemesanan}',            [VerifikasiPemesananController::class, 'store'])->name('verifikasi.store');
    Route::patch('verifikasi/{verifikasi}/status',   [VerifikasiPemesananController::class, 'updateStatus'])->name('verifikasi.updateStatus');

    // Riwayat
    Route::get('riwayat', [RiwayatController::class, 'index'])->name('riwayat.index');
});

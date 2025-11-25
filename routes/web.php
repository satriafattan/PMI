<?php

use Illuminate\Support\Facades\Route;

// === Public Controllers ===
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\Public\PublicPemesananController;
use App\Http\Controllers\Public\EventScheduleController;
use App\Http\Controllers\Public\StokController;

// === Admin Controllers ===
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\BloodUnitController;
use App\Http\Controllers\Admin\StokDarahController;
use App\Http\Controllers\Admin\VerifikasiPemesananController;
use App\Http\Controllers\Admin\RiwayatController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\EventVerificationController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\AdminManagementController;

/*
|--------------------------------------------------------------------------
| Landing & Static Pages
|--------------------------------------------------------------------------
*/

Route::get('/', [WelcomeController::class, 'index'])->name('home');
Route::get('/about', fn() => view('about'))->name('about');

/*
|--------------------------------------------------------------------------
| Public: Pemesanan (Non-Login)
|--------------------------------------------------------------------------
*/
Route::get('/pemesanan', [PublicPemesananController::class, 'create'])->name('pemesanan.create');

// Rate limiting: maksimal 3 submit per 10 menit per IP
Route::post('/pemesanan', [PublicPemesananController::class, 'store'])
    ->name('pemesanan.store')
    ->middleware('throttle:3,10');

Route::get('/pemesanan/konfirmasi/{kode}', [PublicPemesananController::class, 'konfirmasi'])
    ->name('pemesanan.konfirmasi');

/*
|--------------------------------------------------------------------------
| Public: Penjadwalan Event & Stok Darah
|--------------------------------------------------------------------------
*/
Route::get('/jadwal-event', [EventScheduleController::class, 'create'])->name('public.event.create');

// Rate limiting untuk event scheduling
Route::post('/jadwal-event', [EventScheduleController::class, 'store'])
    ->name('public.event.store')
    ->middleware('throttle:5,10');

Route::get('/stok', [StokController::class, '__invoke'])->name('stok');
Route::get('/api/stok-golongan', [StokController::class, 'getStokGolongan'])->name('api.stok-golongan');

/*
|--------------------------------------------------------------------------
| Admin Auth
|--------------------------------------------------------------------------
*/
Route::get('/admin/login', [AuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

// Forget Password Routes
Route::get('/admin/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('admin.forgot-password');
Route::post('/admin/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('admin.forgot-password.submit');
Route::get('/admin/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('admin.reset-password');
Route::post('/admin/reset-password', [AuthController::class, 'resetPassword'])->name('admin.reset-password.submit');

/*
|--------------------------------------------------------------------------
| Admin Area (guard: admin)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:admin')->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ===== API Notifikasi (untuk polling) =====
    Route::get('/api/notifications', [NotificationController::class, 'getPendingNotifications'])->name('api.notifications');

    // ===== Detail Darah (Blood Units) =====
    // ==> PERBAIKAN: pakai nama rute 'detail-darah.*' supaya sesuai dengan pemanggilan di Blade.
    Route::get('/detail-darah', [BloodUnitController::class, 'index'])
        ->name('detail-darah.index');   // final: admin.detail-darah.index
    Route::get('/detail-darah/tersedia', [BloodUnitController::class, 'tersedia'])
        ->name('detail-darah.tersedia');
    Route::get('/detail-darah/tersedia/export', [BloodUnitController::class, 'exportTersedia'])
        ->name('detail-darah.tersedia.export');
    Route::get('/detail-darah/keluar', [BloodUnitController::class, 'keluar'])
        ->name('detail-darah.keluar');
    Route::get('/detail-darah/keluar/export', [BloodUnitController::class, 'exportKeluar'])
        ->name('detail-darah.keluar.export');
    Route::get('/detail-darah/kadaluwarsa', [BloodUnitController::class, 'kadaluwarsa'])
        ->name('detail-darah.kadaluwarsa');
    Route::get('/detail-darah/kadaluwarsa/export', [BloodUnitController::class, 'exportKadaluwarsa'])
        ->name('detail-darah.kadaluwarsa.export');
    Route::get('/detail-darah/{unit}', [BloodUnitController::class, 'show'])
        ->name('detail-darah.show');    // final: admin.detail-darah.show
    Route::get('/detail-darah/data', [BloodUnitController::class, 'data'])
        ->name('detail-darah.data');    // final: admin.detail-darah.data

    // ===== Stok Darah =====
    Route::resource('stok-darah', StokDarahController::class)->except(['show']);

    // ===== Verifikasi Pemesanan =====
    Route::get('verifikasi', [VerifikasiPemesananController::class, 'index'])->name('verifikasi.index');
    Route::post('verifikasi/{pemesanan}', [VerifikasiPemesananController::class, 'store'])->name('verifikasi.store');
    Route::patch('verifikasi/{verifikasi}/status', [VerifikasiPemesananController::class, 'updateStatus'])->name('verifikasi.updateStatus');

    // ===== Riwayat =====
    Route::get('riwayat', [RiwayatController::class, 'index'])->name('riwayat.index');
    Route::delete('riwayat/{id}', [RiwayatController::class, 'destroy'])->name('riwayat.destroy');


    // ===== Laporan =====
    Route::get('laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('laporan/export-pdf', [LaporanController::class, 'exportPdf'])->name('laporan.exportPdf');
    Route::get('laporan/export-excel', [LaporanController::class, 'exportExcel'])->name('laporan.exportExcel');

    // ===== Event Verifikasi =====
    Route::get('event-verifikasi', [EventVerificationController::class, 'index'])->name('event-verifikasi.index');
    Route::get('event-verifikasi/{event}', [EventVerificationController::class, 'show'])->name('event-verifikasi.show');
    Route::post('event-verifikasi/{event}/decide', [EventVerificationController::class, 'decide'])->name('event-verifikasi.decide');
    Route::get('event-verifikasi/{event}/surat', [EventVerificationController::class, 'downloadSurat'])->name('event-verifikasi.surat');

    // ===== Manajemen Admin =====
    Route::resource('admins', AdminManagementController::class)->except(['show']);
});

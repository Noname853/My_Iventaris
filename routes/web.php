<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AlatController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\UserController;

// Redirect root ke dashboard
Route::get('/', function () {
    return redirect()->route('dashboard');
});

Auth::routes();

// Dashboard routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/dashboard/kelompok/tambah', [App\Http\Controllers\KelompokController::class, 'store'])->name('kelompok.tambah');
    Route::get('/laporan', [DashboardController::class, 'laporan'])->name('laporan');
    Route::get('/dashboard/stats', [DashboardController::class, 'getStats'])->name('dashboard.stats');
    Route::get('/dashboard/chart-data', [DashboardController::class, 'getChartData'])->name('dashboard.chart-data');
});

// Alat routes
Route::middleware('auth')->group(function () {
    Route::resource('alat', AlatController::class);
    Route::patch('/alat/{alat}/update-stock', [AlatController::class, 'updateStock'])->name('alat.update-stock');
    Route::get('/alat/{alat}/details', [AlatController::class, 'getDetails'])->name('alat.details');
    Route::get('/alat/{alat}/ios16', [AlatController::class, 'showIOS16'])->name('alat.show-ios16');
    Route::get('/alat-search', [AlatController::class, 'search'])->name('alat.search');
    Route::post('/alat/generate-code', [AlatController::class, 'generateCode'])->name('alat.generate-code');
    
    // Import/Export routes (Admin only)
    Route::get('/alat-export', [AlatController::class, 'export'])->name('alat.export');
    Route::post('/alat-import', [AlatController::class, 'import'])->name('alat.import');
    Route::get('/alat-template', [AlatController::class, 'downloadTemplate'])->name('alat.template');
});

// Peminjaman routes
Route::middleware('auth')->group(function () {
    Route::resource('peminjaman', PeminjamanController::class);
    Route::patch('/peminjaman/{peminjaman}/verify', [PeminjamanController::class, 'verify'])->name('peminjaman.verify');
    Route::patch('/peminjaman/{peminjaman}/verifikasi', [PeminjamanController::class, 'verify'])->name('peminjaman.verifikasi');
    Route::patch('/peminjaman/{peminjaman}/return', [PeminjamanController::class, 'return'])->name('peminjaman.return');
    Route::patch('/peminjaman/{peminjaman}/kembalikan', [PeminjamanController::class, 'return'])->name('peminjaman.kembalikan');
    Route::patch('/peminjaman/{peminjaman}/cancel', [PeminjamanController::class, 'cancel'])->name('peminjaman.cancel');
    Route::get('/peminjaman-export', [PeminjamanController::class, 'export'])->name('peminjaman.export');
});

// User management routes
Route::middleware('auth')->group(function () {
    Route::resource('users', UserController::class);
    Route::patch('/users/{user}/verify-email', [UserController::class, 'verifyEmail'])->name('users.verify-email');
    Route::patch('/users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
    Route::post('/users/bulk-action', [UserController::class, 'bulkAction'])->name('users.bulk-action');
    Route::get('/users-export', [UserController::class, 'export'])->name('users.export');
});

// Admin routes with prefix
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::resource('users', UserController::class);
    Route::patch('/users/{user}/verify-email', [UserController::class, 'verifyEmail'])->name('users.verify-email');
    Route::patch('/users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
    Route::post('/users/bulk-action', [UserController::class, 'bulkAction'])->name('users.bulk-action');
    Route::get('/users-export', [UserController::class, 'export'])->name('users.export');
});

// Redirect /home to dashboard for compatibility
Route::get('/home', function () {
    return redirect()->route('dashboard');
})->name('home');

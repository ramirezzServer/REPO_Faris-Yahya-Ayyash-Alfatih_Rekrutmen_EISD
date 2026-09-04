<?php

use App\Http\Controllers\Admin\JalurPengolahanController;
use App\Http\Controllers\Admin\KawasanController;
use App\Http\Controllers\Admin\PeriodeKuotaController;
use App\Http\Controllers\Admin\VerifikasiLaporanController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Operator\LaporanNeracaController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'publik'])->name('dashboard.publik');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/admin/dashboard', [DashboardController::class, 'admin'])
        ->middleware('role:admin')->name('dashboard.admin');

    Route::get('/operator/dashboard', [DashboardController::class, 'operator'])
        ->middleware('role:operator')->name('dashboard.operator');

    Route::get('/warga/dashboard', [DashboardController::class, 'warga'])
        ->middleware('role:warga')->name('dashboard.warga');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('kawasan', KawasanController::class)
        ->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);

    Route::resource('jalur-pengolahan', JalurPengolahanController::class)
        ->parameters(['jalur-pengolahan' => 'jalurPengolahan'])
        ->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);

    Route::get('periode-kuota', [PeriodeKuotaController::class, 'index'])->name('periode-kuota.index');
    Route::get('periode-kuota/create', [PeriodeKuotaController::class, 'create'])->name('periode-kuota.create');
    Route::post('periode-kuota', [PeriodeKuotaController::class, 'store'])->name('periode-kuota.store');
    Route::put('periode-kuota/{id}/tutup', [PeriodeKuotaController::class, 'tutup'])->name('periode-kuota.tutup');

    Route::get('verifikasi-laporan', [VerifikasiLaporanController::class, 'index'])->name('verifikasi-laporan.index');
    Route::get('verifikasi-laporan/{id}', [VerifikasiLaporanController::class, 'show'])->name('verifikasi-laporan.show');
    Route::put('verifikasi-laporan/{id}/verify', [VerifikasiLaporanController::class, 'verify'])->name('verifikasi-laporan.verify');
    Route::put('verifikasi-laporan/{id}/reject', [VerifikasiLaporanController::class, 'reject'])->name('verifikasi-laporan.reject');
});

Route::middleware(['auth', 'role:operator'])->prefix('operator')->name('operator.')->group(function () {
    Route::resource('laporan-neraca', LaporanNeracaController::class)
        ->parameters(['laporan-neraca' => 'laporanNeraca'])
        ->only(['index', 'create', 'store', 'show']);
});

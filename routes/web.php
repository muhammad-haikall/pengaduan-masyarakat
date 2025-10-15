<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MasyarakatController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PetugasController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\PetugasAuth;

/*
|--------------------------------------------------------------------------
| Route untuk Masyarakat
|--------------------------------------------------------------------------
*/
Route::redirect('/', '/login');

// Auth Masyarakat
Route::controller(MasyarakatController::class)->group(function () {
    Route::get('/login', 'showLogin')->name('login');
    Route::post('/login', 'login')->name('login.submit');
    Route::get('/register', 'showRegister')->name('register');
    Route::post('/register', 'register')->name('register.submit');
    Route::post('/logout', 'logout')->name('logout');
});

// Dashboard + CRUD Pengaduan (untuk masyarakat)
Route::prefix('dashboard')->controller(MasyarakatController::class)->group(function () {
    Route::get('/pengaduan', 'showDashboard')->name('pengaduan.index');
    Route::post('/pengaduan', 'storePengaduan')->name('pengaduan.store');
    Route::get('/pengaduan/{id}/edit', 'editPengaduan')->name('pengaduan.edit');
    Route::post('/pengaduan/{id}/update', 'updatePengaduan')->name('pengaduan.update');
    Route::delete('/pengaduan/{id}/delete', 'deletePengaduan')->name('pengaduan.delete');
});

/*
|--------------------------------------------------------------------------
| Route untuk Admin
|--------------------------------------------------------------------------
*/

// Auth Admin
Route::prefix('admin')->controller(AdminController::class)->group(function () {
    Route::get('/login', 'showLogin')->name('admin.login');
    Route::post('/login', 'login')->name('admin.login.submit');
    Route::post('/logout', 'logout')->name('admin.logout');
});

// Hanya bisa diakses jika admin login
Route::prefix('admin')->controller(AdminController::class)->group(function () {
    // Dashboard
    Route::get('/dashboard', 'dashboard')->name('admin.dashboard');

    // Register Petugas (khusus admin)
    Route::get('/petugas/register', 'showRegisterPetugas')->name('admin.register');
    Route::post('/petugas/register', 'registerPetugas')->name('admin.register.submit');

    // Aksi Pengaduan
    Route::get('/pengaduan/{id}/verifikasi', 'verifikasi')->name('admin.pengaduan.verifikasi');
    Route::post('/pengaduan/{id}/validasi', 'validasi')->name('admin.pengaduan.validasi');
    Route::post('/pengaduan/{id}/tanggapan', 'tanggapan')->name('admin.pengaduan.tanggapan');

    // Laporan
    Route::get('/laporan/pdf', 'exportLaporan')->name('admin.laporan.pdf');
});

/*
|--------------------------------------------------------------------------
| Route untuk Petugas
|--------------------------------------------------------------------------
*/

// Auth Petugas
// Route untuk Petugas
Route::prefix('petugas')->controller(PetugasController::class)->group(function () {
    Route::get('/login', 'showLogin')->name('petugas.login');
    Route::post('/login', 'login')->name('petugas.login.submit');
    Route::get('/logout', 'logout')->name('petugas.logout');
    Route::get('/dashboard', 'dashboard')->name('petugas.dashboard');

    Route::get('/pengaduan/{id}/verifikasi', 'verifikasi')->name('petugas.pengaduan.verifikasi');
    Route::post('/pengaduan/{id}/validasi', 'validasi')->name('petugas.pengaduan.validasi');
    Route::post('/pengaduan/{id}/tanggapan', 'tanggapan')->name('petugas.pengaduan.tanggapan');

});

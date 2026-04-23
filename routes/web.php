<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Pelanggan;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ── Redirect root ke login ─────────────────────────────────────────────────
Route::get('/', fn () => redirect()->route('login'));

// ── Autentikasi (Public) ───────────────────────────────────────────────────
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ── Admin Routes (auth + is_admin) ────────────────────────────────────────
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'is_admin'])
    ->group(function () {
        // Dashboard / Kelola Antrian
        Route::get('/dashboard', [Admin\AntrianController::class, 'index'])->name('dashboard');
        Route::get('/antrian', [Admin\AntrianController::class, 'index'])->name('antrian.index');
        Route::patch('/antrian/{antrian}/proses', [Admin\AntrianController::class, 'proses'])->name('antrian.proses');
        Route::patch('/antrian/{antrian}/selesai', [Admin\AntrianController::class, 'selesai'])->name('antrian.selesai');
        Route::delete('/antrian/{antrian}', [Admin\AntrianController::class, 'destroy'])->name('antrian.destroy');

        // Kelola Harga Layanan
        Route::get('/harga', [Admin\HargaController::class, 'index'])->name('harga.index');
        Route::put('/harga/{jenisLayanan}', [Admin\HargaController::class, 'update'])->name('harga.update');

        // Data Harian
        Route::get('/data-harian', [Admin\DataHarianController::class, 'index'])->name('data-harian.index');
    });

// ── Pelanggan Routes (auth + is_pelanggan) ────────────────────────────────
Route::prefix('pelanggan')
    ->name('pelanggan.')
    ->middleware(['auth', 'is_pelanggan'])
    ->group(function () {
        Route::get('/dashboard', [Pelanggan\AntrianPelangganController::class, 'dashboard'])->name('dashboard');
        Route::get('/antrian/buat', [Pelanggan\AntrianPelangganController::class, 'create'])->name('antrian.create');
        Route::post('/antrian', [Pelanggan\AntrianPelangganController::class, 'store'])->name('antrian.store');
        Route::get('/antrian/status', [Pelanggan\AntrianPelangganController::class, 'status'])->name('antrian.status');
    });

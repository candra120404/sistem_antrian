<?php

use App\Http\Controllers\Api\AntrianApiController;
use App\Http\Controllers\Api\AuthApiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes (untuk mobile Flutter)
|--------------------------------------------------------------------------
*/

// ── Public Endpoints ──────────────────────────────────────────────────────
Route::post('/login', [AuthApiController::class, 'login']);
Route::post('/register', [AuthApiController::class, 'register']);

// ── Protected Endpoints (Sanctum) ─────────────────────────────────────────
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('/logout', [AuthApiController::class, 'logout']);
    Route::get('/me', [AuthApiController::class, 'me']);

    // Antrian — urutan route spesifik dulu sebelum {id}!
    Route::get('/antrian', [AntrianApiController::class, 'index']);
    Route::post('/antrian', [AntrianApiController::class, 'store']);
    Route::get('/antrian/saya', [AntrianApiController::class, 'milikSaya']);
    Route::get('/antrian/{id}/posisi', [AntrianApiController::class, 'posisi']);
    Route::patch('/antrian/{id}/selesai', [AntrianApiController::class, 'selesai']);
    Route::delete('/antrian/{id}', [AntrianApiController::class, 'destroy']);

    // Harga Layanan
    Route::get('/harga', [AntrianApiController::class, 'harga']);
    Route::put('/harga/{id}', [AntrianApiController::class, 'updateHarga']);

    // Laporan Harian
    Route::get('/data-harian', [AntrianApiController::class, 'dataHarian']);
});

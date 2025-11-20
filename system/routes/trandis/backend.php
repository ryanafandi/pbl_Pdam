<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Trandis\DashboardController;
use App\Http\Controllers\Trandis\SpkController;         // Controller 1
use App\Http\Controllers\Trandis\PemasanganController;  // Controller 2

Route::get('/', [DashboardController::class, 'index']);
Route::get('dashboard', [DashboardController::class, 'index']);

// ==================================================
// 1. SPK MASUK (Controller: SpkController)
// ==================================================
Route::prefix('spk-masuk')->group(function () {
    Route::get('/',            [SpkController::class, 'index']);
    Route::get('{id}',         [SpkController::class, 'show']);
    Route::post('{id}/jadwal', [SpkController::class, 'storeSchedule']);
});

// ==================================================
// 2. PEMASANGAN (Controller: PemasanganController)
// ==================================================
Route::prefix('pemasangan')->group(function () {
    Route::get('/',            [PemasanganController::class, 'index']);
    Route::get('{id}',         [PemasanganController::class, 'show']);
    
    // Aksi Lapangan
    Route::get('{id}/print',   [PemasanganController::class, 'print'])->name('trandis.spk.print');
    Route::post('{id}/start',  [PemasanganController::class, 'startWork']);
    Route::post('{id}/stop',   [PemasanganController::class, 'stopWork']);
    Route::post('{id}/photo',  [PemasanganController::class, 'uploadPhoto']);
    Route::post('{id}/finish', [PemasanganController::class, 'finishWork']);
});
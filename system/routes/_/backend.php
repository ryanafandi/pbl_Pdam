<?php

use App\Http\Controllers\Backend\PengajuanController;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\StatusPengajuanController; 
use App\Http\Controllers\Backend\DokumenController;


Route::get('/', function () {
    return view('backend.index');
});

Route::resource("Pengajuan", PengajuanController::class);

// Status & Proses Pengajuan
Route::get('Proses', [StatusPengajuanController::class, 'index']);
Route::get('Proses/{id}', [StatusPengajuanController::class, 'show']);


// Tagihan & Dokumen (RNA + Persetujuan) untuk pelanggan
Route::get('dokumen',        [DokumenController::class, 'index']);
Route::get('dokumen/{rab}',  [DokumenController::class, 'show']);
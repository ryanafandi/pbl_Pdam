<?php

use App\Http\Controllers\Backend\PengajuanController;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('backend.index');
});

Route::resource("Pengajuan", PengajuanController::class);


Route::get('Pengajuan/print/surat-permohonan', [PengajuanController::class, 'printSurat'])
    ->name('Pengajuan.print');


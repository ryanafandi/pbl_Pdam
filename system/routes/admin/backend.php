<?php

use App\Http\Controllers\Admin\Pengajuan;
use App\Http\Controllers\Admin\PengajuanController;
use Illuminate\Support\Facades\Route;
// ----------------- ADMIN ROUTES ----------------- //
Route::get('/', function () {
    return view('admin.index');
});

Route::resource('pengajuan', PengajuanController::class);
Route::post('pengajuan/{nik}/status', [PengajuanController::class, 'updateStatus'])->name('pengajuan.updateStatus');
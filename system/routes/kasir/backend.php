<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Kasir\TagihanController;

Route::get('/', function () {
    return redirect('kasir/tagihan');
});

// daftar tagihan
Route::get('tagihan', [TagihanController::class, 'index']);

// detail 1 tagihan
Route::get('tagihan/{rab}', [TagihanController::class, 'show']);

// aksi bayar
Route::post('tagihan/{rab}/pay', [TagihanController::class, 'pay']);

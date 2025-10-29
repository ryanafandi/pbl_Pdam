<?php

use App\Http\Controllers\Frontend\PengajuanPengguna;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('frontend.index');
});


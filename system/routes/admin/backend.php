<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PengajuanController;
use App\Http\Controllers\Admin\SpkoController;
use App\Http\Controllers\Admin\RabController as AdminRabController;
use App\Http\Controllers\Admin\DokumenBiayaController;
use App\Http\Controllers\Admin\SpkController;

// Dashboard
Route::get('/', [DashboardController::class, 'index']);

// Pengajuan
Route::get('pengajuan',            [PengajuanController::class, 'index']);
Route::get('pengajuan/create',     [PengajuanController::class, 'create']);
Route::post('pengajuan',           [PengajuanController::class, 'store']);
Route::get('pengajuan/{id}',       [PengajuanController::class, 'show']);
Route::get('pengajuan/{id}/edit',  [PengajuanController::class, 'edit']);
Route::put('pengajuan/{id}',       [PengajuanController::class, 'update']);
Route::delete('pengajuan/{id}',    [PengajuanController::class, 'destroy']);

// Aksi tambahan
Route::post('pengajuan/{id}/send-to-director', [PengajuanController::class, 'sendToDirector']);
Route::patch('pengajuan/{id}/status',          [PengajuanController::class, 'setStatus']);
Route::patch('pengajuan/{id}/progress',        [PengajuanController::class, 'setProgress']);
Route::post('pengajuan/{id}/reject', [PengajuanController::class, 'reject'])
    ->name('admin.pengajuan.reject');

// SPKO
Route::get('spko',             [SpkoController::class, 'index']);
Route::get('spko/create',      [SpkoController::class, 'create']);
Route::post('spko',            [SpkoController::class, 'store']);
Route::get('spko/{id}',        [SpkoController::class, 'show']);
Route::get('spko/{id}/edit',   [SpkoController::class, 'edit']);
Route::put('spko/{id}',        [SpkoController::class, 'update']);
Route::delete('spko/{id}',     [SpkoController::class, 'destroy']);

// >>> Tambahan penting: Kirim ke Tim Perencanaan
Route::post('spko/{spko}/kirim-perencanaan', [SpkoController::class, 'kirimPerencanaan']);

Route::get('rab',        [AdminRabController::class, 'index']);
Route::get('rab/{id}', [AdminRabController::class, 'show'])
    ->whereNumber('id');



// Dokumen Biaya (Rencana Nilai Anggaran + Surat Persetujuan)
Route::get('dokumen_biaya',            [DokumenBiayaController::class, 'index']);
Route::get('dokumen_biaya/{rab}',      [DokumenBiayaController::class, 'show']);
Route::post('dokumen_biaya/{rab}',     [DokumenBiayaController::class, 'store']); // simpan/update dokumen
Route::post('dokumen_biaya/{rab}/hapus', [DokumenBiayaController::class, 'destroy']);

// Cetak
Route::get('dokumen_biaya/{rab}/rna',          [DokumenBiayaController::class, 'printRna']);
Route::get('dokumen_biaya/{rab}/persetujuan',  [DokumenBiayaController::class, 'printPersetujuan']);

// Aksi kirim ke pelanggan
Route::post('dokumen_biaya/{rab}/kirim', [DokumenBiayaController::class, 'sendToCustomer']);




// =======================
// SPK (Surat Perintah Kerja)
// URL dasar: /admin/spk/...
// =======================
Route::prefix('spk')->name('admin.spk.')->group(function () {

    // List SPK
    // GET /admin/spk
    Route::get('/', [SpkController::class, 'index'])
        ->name('index');

    // Form buat SPK dari 1 RAB
    // GET /admin/spk/create/{rab}
    Route::get('create/{rab}', [SpkController::class, 'create'])
        ->whereNumber('rab')
        ->name('create');

    // Simpan SPK baru
    // POST /admin/spk/store/{rab}
    Route::post('store/{rab}', [SpkController::class, 'store'])
        ->whereNumber('rab')
        ->name('store');

    // Detail SPK
    // GET /admin/spk/{id}
    Route::get('{id}', [SpkController::class, 'show'])
        ->whereNumber('id')
        ->name('show');

    // Kirim ke direktur
    // POST /admin/spk/{id}/kirim
    Route::post('{id}/kirim', [SpkController::class, 'sendToDirector'])
        ->whereNumber('id')
        ->name('kirim');

    // Setujui SPK
    // POST /admin/spk/{id}/approve
    Route::post('{id}/approve', [SpkController::class, 'approve'])
        ->whereNumber('id')
        ->name('approve');

    // Tolak SPK
    // POST /admin/spk/{id}/reject
    Route::post('{id}/reject', [SpkController::class, 'reject'])
        ->whereNumber('id')
        ->name('reject');

    // Tandai pekerjaan selesai
    // POST /admin/spk/{id}/selesai
    Route::post('{id}/selesai', [SpkController::class, 'markFinished'])
        ->whereNumber('id')
        ->name('selesai');
});

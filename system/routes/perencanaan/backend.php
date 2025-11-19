<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Perencanaan\SpkoController;
use App\Http\Controllers\Perencanaan\JadwalController;
use App\Http\Controllers\Perencanaan\RabController;
use App\Http\Controllers\Perencanaan\SurveiController;

// Landing -> antrian SPKO
Route::get('/', fn() => redirect('perencanaan/spko'));

// =======================
// SPKO (antrian & detail)
// =======================
Route::get('spko',                  [SpkoController::class, 'index'])->name('per.spko.index');
Route::get('spko/{id}',             [SpkoController::class, 'show'])->whereNumber('id')->name('per.spko.show');

// Jadwal survei via SPKOController (form & simpan)
Route::get('spko/{id}/edit-jadwal', [SpkoController::class, 'editSchedule'])->whereNumber('id')->name('per.spko.jadwal.edit');
Route::post('spko/{id}/jadwal',     [SpkoController::class, 'updateSchedule'])->whereNumber('id')->name('per.spko.jadwal.update');
Route::get('spko/{id}/edit',  [SpkoController::class, 'edit'])->whereNumber('id');
Route::put('spko/{id}',       [SpkoController::class, 'update'])->whereNumber('id');

// Atur jadwal survei
Route::get('spko/{id}/edit-jadwal', [SpkoController::class, 'editSchedule']);
Route::put('spko/{id}/edit-jadwal', [SpkoController::class, 'updateSchedule']);
// =======================
// Penjadwalan (kalender)
// =======================
Route::get('jadwal',             [JadwalController::class, 'index'])->name('per.jadwal.index');
Route::get('jadwal/create',      [JadwalController::class, 'create'])->name('per.jadwal.create'); // ?spko=ID
Route::post('jadwal',            [JadwalController::class, 'store'])->name('per.jadwal.store');
Route::get('jadwal/{spko}',      [JadwalController::class, 'show'])->whereNumber('spko')->name('per.jadwal.show');
Route::get('jadwal/{spko}/edit', [JadwalController::class, 'edit'])->whereNumber('spko')->name('per.jadwal.edit2');
Route::put('jadwal/{spko}',      [JadwalController::class, 'update'])->whereNumber('spko')->name('per.jadwal.update2');
Route::delete('jadwal/{spko}',   [JadwalController::class, 'destroy'])->whereNumber('spko')->name('per.jadwal.destroy');

// =======================
// Survei (hasil lapangan)
// =======================
Route::get('survei',             [SurveiController::class, 'index'])->name('per.survei.index');
// (opsional) buat baru — tidak wajib dipakai, tapi disediakan
Route::get('survei/create',      [SurveiController::class, 'create'])->name('per.survei.create');  // ?spko=ID
Route::post('survei',            [SurveiController::class, 'store'])->name('per.survei.store');

Route::get('survei/{spko}',      [SurveiController::class, 'show'])->whereNumber('spko')->name('per.survei.show');
Route::get('survei/{spko}/edit', [SurveiController::class, 'edit'])->whereNumber('spko')->name('per.survei.edit');
Route::put('survei/{spko}',      [SurveiController::class, 'update'])->whereNumber('spko')->name('per.survei.update');

// Hapus record survei (opsional)
Route::delete('survei/{spko}',   [SurveiController::class, 'destroy'])->whereNumber('spko')->name('per.survei.destroy');

// =======================
// RAB
// =======================
Route::get('rab', [RabController::class, 'index'])->name('per.rab.index');

Route::get('rab/{spko}/create', [RabController::class, 'create'])->whereNumber('spko')->name('per.rab.create');   // form susun RAB baru

Route::post('rab/{spko}', [RabController::class, 'store'])->whereNumber('spko')->name('per.rab.store'); // simpan RAB baru

Route::get('rab/{spko}', [RabController::class, 'show'])->whereNumber('spko')->name('per.rab.show');  // detail RAB

Route::get('rab/{spko}/edit', [RabController::class, 'edit'])->whereNumber('spko')->name('per.rab.edit');  // edit RAB  
Route::put('rab/{spko}', [RabController::class, 'update'])->whereNumber('spko')->name('per.rab.update');// update RAB

Route::delete('rab/{spko}', [RabController::class, 'destroy'])->whereNumber('spko')->name('per.rab.destroy');  // hapus RAB

// Kirim RAB ke direktur
Route::post('rab/{spko}/kirim', [RabController::class, 'kirimKeDirektur']);
Route::post('rab/{spko}/approve', [RabController::class, 'approve']);
Route::post('rab/{spko}/reject', [RabController::class, 'reject']);

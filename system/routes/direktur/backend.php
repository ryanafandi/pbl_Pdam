<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Direktur\DirekturController;
use App\Http\Controllers\Direktur\RabApprovalController;

// Dashboard Approval (default ke daftar "menunggu")
Route::get('/', [DirekturController::class, 'index']);

// =================== Approval Pendaftaran =================== //
// =================== Approval Pendaftaran =================== //

// ganti dari 'approval/pendaftaran' jadi 'pendaftaran'
Route::get('pendaftaran',              [DirekturController::class, 'index']);
Route::get('pendaftaran/create',       [DirekturController::class, 'create']);
Route::post('pendaftaran',             [DirekturController::class, 'store']);

Route::get('pendaftaran/{id}',         [DirekturController::class, 'show']);
Route::get('pendaftaran/{id}/edit',    [DirekturController::class, 'edit']);
Route::put('pendaftaran/{id}',         [DirekturController::class, 'update']);
Route::delete('pendaftaran/{id}',      [DirekturController::class, 'destroy']);

Route::post('pendaftaran/{id}/approve', [DirekturController::class, 'approve']);
Route::post('pendaftaran/{id}/reject', [DirekturController::class, 'reject']);

// aksi persetujuan
Route::post('approval/pendaftaran/{id}/approve', [DirekturController::class, 'approve']); // set status: APPROVED
Route::post('approval/pendaftaran/{id}/reject', [DirekturController::class, 'reject']);  // set status: REJECTED + alasan

// Inbox RAB
Route::get('rab', [RabApprovalController::class, 'index']);

// Detail RAB
Route::get('rab/{spko}', [RabApprovalController::class, 'show'])->whereNumber('spko');

Route::post('rab/{spko}/approve', [RabApprovalController::class, 'approve'])
    ->name('direktur.rab.approve');

// Reject RAB
Route::post('rab/{spko}/reject', [RabApprovalController::class, 'reject'])
    ->name('direktur.rab.reject');

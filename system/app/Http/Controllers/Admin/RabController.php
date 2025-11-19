<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RabHeader;
use Illuminate\Http\Request;

class RabController extends Controller
{
    /** Daftar RAB yang sudah disetujui direktur dan siap diproses admin */
    public function index()
{
    $rows = RabHeader::with(['spko.pengajuan'])
        ->where('status', 'disetujui')
        ->orderByDesc('approved_at')
        ->paginate(15);

    return view('admin.rab.index', compact('rows'));
}
    /** Detail 1 RAB + rincian item (untuk buat RNA / cetak persetujuan) */
    public function show($id)
    {
        $row = RabHeader::with([
                'spko.pengajuan',
                'details',
            ])
            ->findOrFail($id);

        return view('admin.rab.show', compact('row'));
    }

    // nanti di sini bisa ditambah:
    // - storeRNA()
    // - cetakPersetujuan()
}

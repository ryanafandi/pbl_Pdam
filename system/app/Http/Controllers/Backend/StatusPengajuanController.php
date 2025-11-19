<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use Illuminate\Http\Request;

class StatusPengajuanController
{
    /** List semua pengajuan pelanggan + status & progres */
    public function index(Request $request)
    {
        // NANTI: filter berdasarkan user yang login
        // misalnya: Pengajuan::where('user_id', auth()->id())
        // Untuk sementara, tampilkan semua dulu:
        $rows = Pengajuan::orderByDesc('created_at')->paginate(10);

        return view('backend.proses.index', compact('rows'));
    }

    /** Detail 1 pengajuan (timeline proses) */
    public function show($id)
    {
        $row = Pengajuan::findOrFail($id);
        return view('backend.proses.show', compact('row'));
    }
}

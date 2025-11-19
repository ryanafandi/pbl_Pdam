<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\RabHeader;
use Illuminate\Http\Request;

class DokumenController extends Controller
{
    /**
     * Daftar dokumen biaya (RNA & persetujuan) milik pelanggan.
     */
    public function index(Request $request)
    {
        $status = $request->query('status'); // optional: SENT / PAID

        $q = RabHeader::with('spko.pengajuan')
            ->whereNotNull('billing_status');

        // kalau mau khusus milik pelanggan login, di sini filter:
        // $userId = auth()->id();
        // $q->whereHas('spko.pengajuan', fn($qq) => $qq->where('user_id', $userId));

        if ($status) {
            $q->where('billing_status', $status);
        }

        $rows = $q->orderByDesc('billing_sent_at')
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view('backend.dokumen.index', compact('rows', 'status'));
    }

    /**
     * Detail 1 dokumen biaya (read only).
     */
    public function show($id)
    {
        $row = RabHeader::with('spko.pengajuan')->findOrFail($id);

        // pastikan memang sudah ada dokumen biaya
        if (is_null($row->billing_status)) {
            abort(404);
        }

        // kalau mau batasi hanya milik pelanggan login:
        // $userId = auth()->id();
        // if ($row->spko->pengajuan->user_id !== $userId) abort(403);

        return view('backend.dokumen.show', compact('row'));
    }
}

<?php

namespace App\Http\Controllers\Direktur;

use App\Http\Controllers\Controller;
use App\Models\RabHeader;
use Illuminate\Http\Request;

class RabApprovalController extends Controller
{
    /** Inbox RAB yang sudah dikirim tim Perencanaan */
public function index()
{
    $rab = RabHeader::with('spko', 'spko.pengajuan')
        ->where('status', 'dikirim')
        ->orderBy('sent_to_director_at','asc')
        ->paginate(10);

    return view('direktur.rab.index', compact('rab'));
}
    /** Setujui RAB */
    public function approve($spko)
    {
        // Ambil RAB berdasarkan spko_id
        $rab = RabHeader::where('spko_id', $spko)->firstOrFail();

        $namaDirektur = config('app.direktur_default', 'Direktur');

        $rab->update([
            'status'        => 'disetujui',
            'approved_at'   => now(),
            'approved_by'   => $namaDirektur,
            'rejected_at'   => null,
            'rejected_by'   => null,
            'rejection_note'=> null,
        ]);

        return back()->with('success', 'RAB telah disetujui Direktur.');
    }

    /** Tolak RAB */
    public function reject(Request $request, $spko)
    {
        $rab = RabHeader::where('spko_id', $spko)->firstOrFail();

        $request->validate([
            'alasan' => 'required|string',
        ]);

        $namaDirektur = config('app.direktur_default', 'Direktur');

        $rab->update([
            'status'        => 'ditolak',
            'rejected_at'   => now(),
            'rejected_by'   => $namaDirektur,
            'rejection_note'=> $request->alasan,
        ]);

        return back()->with('success', 'RAB telah ditolak.');
    }
}

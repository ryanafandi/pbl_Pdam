<?php

namespace App\Http\Controllers\Direktur;

use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DirekturController extends Controller
{
    /** LIST: default tampilkan yang menunggu persetujuan */
    public function index(Request $request)
    {
        $q = Pengajuan::query();

        // filter tab: waiting / approved / rejected / all
        $tab = $request->get('tab', 'waiting');

        if ($tab === 'waiting') {
            $q->where('status', Pengajuan::ST_SENT_TO_DIRECTOR);
        } elseif ($tab === 'approved') {
            $q->where('status', Pengajuan::ST_APPROVED);
        } elseif ($tab === 'rejected') {
            $q->where('status', Pengajuan::ST_REJECTED);
        } // tab=all => tanpa filter status

        // pencarian
        if ($request->filled('s')) {
            $s = trim($request->s);
            $q->where(function ($w) use ($s) {
                $w->where('no_pendaftaran', 'like', "%$s%")
                  ->orWhere('pemohon_nama', 'like', "%$s%")
                  ->orWhere('alamat_pemasangan', 'like', "%$s%");
            });
        }

        $data = $q->orderByDesc('created_at')->paginate(15)->withQueryString();

        return view('direktur.Pengajuan.index', compact('data', 'tab'));
    }

    /** DETAIL */
    public function show(string $id)
    {
        $row = Pengajuan::findOrFail($id);
        return view('direktur.Pengajuan.show', compact('row'));
    }

    /** FORM EDIT CATATAN DIREKTUR (opsional) */
    public function edit(string $id)
    {
        $row = Pengajuan::findOrFail($id);
        return view('direktur.Pengajuan.edit', compact('row'));
    }

    /** SIMPAN CATATAN DIREKTUR (opsional) */
    public function update(Request $request, string $id)
    {
        $row = Pengajuan::findOrFail($id);

        $request->validate([
            'catatan_direktur' => 'nullable|string|max:5000',
        ]);

        $row->update([
            'catatan_direktur' => $request->catatan_direktur,
        ]);

        return redirect('direktur/approval/pendaftaran/'.$row->id)
                ->with('success', 'Catatan direktur disimpan.');
    }

    /** DIREKTUR: SETUJUI */
    public function approve(string $id, Request $request)
    {
        $row = Pengajuan::findOrFail($id);

        if (!in_array($row->status, [Pengajuan::ST_SENT_TO_DIRECTOR, Pengajuan::ST_REJECTED], true)) {
            return back()->with('error', 'Status saat ini tidak bisa disetujui.');
        }

        $request->validate([
            'catatan_direktur' => 'nullable|string|max:5000',
        ]);

        DB::transaction(function () use ($row, $request) {
            $row->update([
                'status'            => Pengajuan::ST_APPROVED,
                'catatan_direktur'  => $request->catatan_direktur ?: $row->catatan_direktur,
                'approved_at'       => now(),
                'approved_by'       => Auth::id(),
                // kalau sebelumnya pernah ditolak, bersihkan field reject
                'rejected_at'       => null,
                'rejected_by'       => null,
            ]);
        });

        return back()->with('success', 'Pengajuan disetujui.');
    }

    /** DIREKTUR: TOLAK */
    public function reject(string $id, Request $request)
    {
        $row = Pengajuan::findOrFail($id);

        if ($row->status === Pengajuan::ST_APPROVED) {
            return back()->with('error', 'Pengajuan yang sudah disetujui tidak dapat ditolak.');
        }

        $request->validate([
            'catatan_direktur' => 'required|string|max:5000', // alasan wajib diisi
        ]);

        DB::transaction(function () use ($row, $request) {
            $row->update([
                'status'            => Pengajuan::ST_REJECTED,
                'catatan_direktur'  => $request->catatan_direktur,
                'rejected_at'       => now(),
                'rejected_by'       => Auth::id(),
            ]);
        });

        return back()->with('success', 'Pengajuan ditolak.');
    }

    /** DIREKTUR: create/store/destroy tidak digunakan */
    public function create()    { return back()->with('info','Direktur tidak membuat pengajuan.'); }
    public function store()     { return back()->with('info','Direktur tidak membuat pengajuan.'); }
    public function destroy()   { return back()->with('error','Aksi hapus tidak diizinkan.'); }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengajuanController extends Controller
{
    /** List dengan filter sederhana + eager spko */
    public function index(Request $request)
    {
        $q = Pengajuan::with('spko');

        if ($request->filled('s')) {
            $q->search($request->s);
        }

        if ($request->filled('status')) {
            $q->where('status', $request->status);
        }

        if ($request->filled('progress_status')) {
            $q->where('progress_status', $request->progress_status);
        }

        // quick filter: siap dibuatkan SPKO (APPROVED & belum punya SPKO)
        if ($request->boolean('siap_spko')) {
            $q->siapSpko();
        }

        $data = $q->orderByDesc('created_at')->paginate(15)->withQueryString();
        return view('admin.pengajuan.index', compact('data'));
    }

    /** Detail */
    public function show(string $id)
    {
        $row = Pengajuan::with('spko')->findOrFail($id);
        return view('admin.pengajuan.show', compact('row'));
    }

    /** Edit (catatan admin + progres pemasangan) */
    public function edit(string $id)
    {
        $row = Pengajuan::findOrFail($id);
        return view('admin.pengajuan.edit', compact('row'));
    }

    /** Update */
    public function update(Request $request, string $id)
    {
        $row = Pengajuan::findOrFail($id);

        $request->validate([
            'catatan_admin'   => 'nullable|string|max:5000',
            'progress_status' => 'nullable|string|in:QUEUED,SURVEY,MATERIAL_READY,SCHEDULED,INSTALLING,INSTALLED',
        ]);

        $data = [
            'catatan_admin' => $request->catatan_admin,
        ];

        if ($request->filled('progress_status')) {
            $data['progress_status']     = $request->progress_status;
            $data['progress_updated_at'] = now();
        }

        $row->update($data);

        return redirect('admin/pengajuan/'.$row->id)->with('success', 'Perubahan disimpan.');
    }

    /** Hapus */
    public function destroy(string $id)
    {
        $row = Pengajuan::findOrFail($id);
        $row->delete();
        return redirect('admin/pengajuan')->with('success', 'Data dihapus.');
    }

    /** Kirim ke Direktur (gunakan helper model) */
    public function sendToDirector(string $id, Request $request)
    {
        $row = Pengajuan::findOrFail($id);

        if ($row->status !== Pengajuan::ST_SUBMITTED) {
            return back()->with('error', 'Hanya status SUBMITTED yang dapat dikirim ke direktur.');
        }

        $request->validate(['catatan_admin' => 'nullable|string|max:5000']);
        $row->kirimKeDirektur($request->catatan_admin);

        return back()->with('success', 'Pengajuan berhasil dikirim ke direktur.');
    }

    /** (Opsional) Override status persetujuan — jaga-jaga admin */
    public function setStatus(string $id, Request $request)
    {
        $row = Pengajuan::findOrFail($id);
        $request->validate([
            'status' => 'required|string|in:SUBMITTED,SENT_TO_DIRECTOR,APPROVED,REJECTED',
            'catatan_direktur' => 'nullable|string|max:5000',
        ]);

        if ($request->status === Pengajuan::ST_APPROVED) {
            $row->setujuiOlehDirektur($request->catatan_direktur, null);
        } elseif ($request->status === Pengajuan::ST_REJECTED) {
            $alasan = $request->catatan_direktur ?: 'Ditolak (tanpa alasan)';
            $row->tolakOlehDirektur($alasan, null);
        } else {
            $row->update(['status' => $request->status]);
        }

        return back()->with('success', 'Status persetujuan diperbarui.');
    }

    /** Ubah progres pemasangan (yang dilihat pelanggan) */
    public function setProgress(string $id, Request $request)
    {
        $row = Pengajuan::findOrFail($id);
        $request->validate([
            'progress_status' => 'required|string|in:QUEUED,SURVEY,MATERIAL_READY,SCHEDULED,INSTALLING,INSTALLED',
        ]);

        $row->update([
            'progress_status'     => $request->progress_status,
            'progress_updated_at' => now(),
        ]);

        return back()->with('success', 'Progres pemasangan diperbarui.');
    }

        /** Admin menolak pengajuan (sebelum / tanpa dikirim ke direktur) */
    public function reject(string $id, Request $request)
    {
        $row = Pengajuan::findOrFail($id);

        // Biar konsisten: hanya boleh tolak jika masih SUBMITTED
        if ($row->status !== Pengajuan::ST_SUBMITTED) {
            return back()->with('error', 'Hanya pengajuan dengan status SUBMITTED yang dapat ditolak.');
        }

        // Kalau mau pakai alasan penolakan dari admin
        $request->validate([
            'reason' => 'nullable|string|max:5000',
        ]);

        $row->status        = Pengajuan::ST_REJECTED;
        // simpan alasan ke catatan_admin (opsional)
        if ($request->filled('reason')) {
            $row->catatan_admin = $request->reason;
        }
        $row->save();

        return back()->with('success', 'Pengajuan berhasil ditolak.');
    }

}

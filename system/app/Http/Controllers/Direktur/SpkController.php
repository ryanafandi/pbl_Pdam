<?php

namespace App\Http\Controllers\Direktur;

use App\Http\Controllers\Controller;
use App\Models\SpkHeader;
use Illuminate\Http\Request;

class SpkController extends Controller
{
    /**
     * Daftar SPK yang masuk (Inbox Direktur)
     */
    public function index()
    {
        // Mengambil SPK dengan prioritas status 'kirim_direktur' (Menunggu Approval) di paling atas
        $data = SpkHeader::with('rab.spko.pengajuan')
            ->whereIn('status', ['kirim_direktur', 'disetujui', 'ditolak', 'selesai'])
            ->orderByRaw("FIELD(status, 'kirim_direktur') DESC") 
            ->orderByDesc('dibuat_at')
            ->paginate(10);

        return view('direktur.spk.index', compact('data'));
    }

    /**
     * Halaman Detail untuk Review & Approval
     */
    public function show($id)
    {
        $row = SpkHeader::with(['rab.spko.pengajuan'])->findOrFail($id);
        return view('direktur.spk.show', compact('row'));
    }

    /**
     * Fitur Preview Surat (Hanya Melihat)
     * Menggunakan view yang sama dengan admin, tapi tidak dikirim parameter 'print'
     */
    public function preview($id)
    {
        $row = SpkHeader::with(['rab.spko.pengajuan'])->findOrFail($id);
        
        // Kita gunakan view 'admin.spk.print' agar format suratnya satu sumber.
        // Karena tidak ada request()->merge(['print' => true]), maka window.print() tidak jalan otomatis.
        return view('admin.spk.print', compact('row'));
    }

    /**
     * Aksi Setuju
     */
    public function approve($id)
    {
        $spk = SpkHeader::findOrFail($id);
        
        try {
            $spk->approveByDirector(); // Fungsi dari Model SpkHeader
            return back()->with('success', 'SPK telah disetujui dan diteruskan ke Trandis.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Aksi Tolak
     */
    public function reject(Request $request, $id)
    {
        $spk = SpkHeader::findOrFail($id);
        $request->validate(['catatan' => 'required|string|max:1000']);

        try {
            $spk->rejectByDirector($request->catatan); // Fungsi dari Model SpkHeader
            return back()->with('success', 'SPK ditolak dan dikembalikan ke Admin.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
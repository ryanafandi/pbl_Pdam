<?php

namespace App\Http\Controllers\Trandis;

use App\Http\Controllers\Controller;
use App\Models\SpkHeader;
use Illuminate\Http\Request;

class SpkController extends Controller
{
    /**
     * DAFTAR SPK MASUK (Belum Dijadwalkan)
     */
    public function index()
    {
        $data = SpkHeader::with(['rab.spko.pengajuan'])
            ->where('status', 'disetujui')
            ->where('status_teknis', 'pending') // Filter status 'pending'
            ->orderByDesc('disetujui_at')
            ->paginate(10);

        return view('trandis.spk-masuk.index', compact('data'));
    }

    /**
     * DETAIL SPK MASUK (Review sebelum dijadwal)
     */
    public function show($id)
    {
        $row = SpkHeader::with(['rab.spko.pengajuan', 'rab.details','rab.spko.survei'])
                ->findOrFail($id);

        // Variabel back_url untuk tombol 'Kembali'
        $back_url = url('trandis/spk-masuk');

        return view('trandis.spk-masuk.show', compact('row', 'back_url'));
    }

    /**
     * AKSI SIMPAN JADWAL
     * Setelah dijadwal, status berubah dan data pindah ke PemasanganController
     */
    public function storeSchedule(Request $request, $id)
    {
        $row = SpkHeader::findOrFail($id);
        
        $request->validate([
            'tgl_jadwal' => 'required|date',
        ]);

        $row->update([
            'tgl_jadwal'    => $request->tgl_jadwal,
            'status_teknis' => 'scheduled' // Ubah status -> Pindah ke menu Pemasangan
        ]);

        return redirect('trandis/pemasangan')->with('success', 'Jadwal berhasil disimpan. SPK dipindahkan ke menu Proses Pemasangan.');
    }
}
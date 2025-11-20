<?php

namespace App\Http\Controllers\Trandis;

use App\Http\Controllers\Controller;
use App\Models\SpkHeader;
use App\Models\SpkLog;
use App\Models\SpkFoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PemasanganController extends Controller
{
    /**
     * DAFTAR PROSES PEMASANGAN (Sedang berjalan / Selesai)
     */
    public function index()
    {
        $data = SpkHeader::with(['rab.spko.pengajuan'])
            ->whereIn('status', ['disetujui', 'selesai'])
            ->where('status_teknis', '!=', 'pending') // Ambil yang SUDAH dijadwalkan
            ->orderByRaw("FIELD(status_teknis, 'working', 'scheduled', 'paused', 'installed')")
            ->orderByDesc('tgl_jadwal')
            ->paginate(10);

        return view('trandis.pemasangan.index', compact('data'));
    }

    /**
     * DASHBOARD KERJA (Timer, Log, Foto)
     */
    public function show($id)
    {
        $row = SpkHeader::with(['logs', 'fotos', 'rab.spko.pengajuan'])
                ->findOrFail($id);

        $back_url = url('trandis/pemasangan');

        return view('trandis.pemasangan.show', compact('row', 'back_url'));
    }

    // =========================================================================
    // AKSI EKSEKUSI LAPANGAN
    // =========================================================================

    public function startWork($id)
    {
        $row = SpkHeader::findOrFail($id);

        if ($row->is_working) return back()->with('error', 'Timer sedang berjalan.');
        
        SpkLog::create(['spk_header_id' => $row->id, 'mulai_pada' => now()]);
        $row->update(['status_teknis' => 'working']);
        
        return back()->with('success', 'Pekerjaan DIMULAI.');
    }

    public function stopWork(Request $request, $id)
    {
        $row = SpkHeader::findOrFail($id);
        $log = $row->logs()->whereNull('selesai_pada')->latest()->first();
        
        if (!$log) return back()->with('error', 'Tidak ada pekerjaan aktif.');
        
        $request->validate(['catatan' => 'required']);
        $log->update(['selesai_pada' => now(), 'catatan' => $request->catatan]);
        $row->update(['status_teknis' => 'paused']);
        
        return back()->with('success', 'Pekerjaan DIJEDA sementara.');
    }

    public function uploadPhoto(Request $request, $id)
    {
        $request->validate(['foto' => 'required|image|max:5120']);
        
        $file = $request->file('foto');
        $folder = 'public/app/spk_dokumentasi';
        if (!file_exists(public_path($folder))) mkdir(public_path($folder), 0777, true);
        
        $name = time().'-'.Str::random(5).'.'.$file->getClientOriginalExtension();
        $file->move(public_path($folder), $name);

        SpkFoto::create([
            'spk_header_id' => $id,
            'foto_path' => $folder.'/'.$name,
            'keterangan' => $request->keterangan
        ]);

        return back()->with('success', 'Foto berhasil diunggah.');
    }

    public function finishWork($id)
    {
        $row = SpkHeader::findOrFail($id);
        
        if ($row->is_working) return back()->with('error', 'Matikan timer (STOP) dulu.');
        if ($row->fotos()->count() == 0) return back()->with('error', 'Wajib upload foto bukti.');

        DB::transaction(function() use ($row) {
            $row->update(['status' => 'selesai', 'status_teknis' => 'installed']);
            if($row->rab->spko->pengajuan) {
                $row->rab->spko->pengajuan->update(['progress_status' => 'INSTALLED', 'progress_updated_at' => now()]);
            }
        });

        return back()->with('success', 'Pekerjaan SELESAI TOTAL.');
    }

    public function print($id)
    {
        $row = SpkHeader::with(['rab.spko.pengajuan'])->findOrFail($id);
        request()->merge(['print' => true]);
        return view('admin.spk.print', compact('row'));
    }
}
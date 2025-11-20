<?php

namespace App\Http\Controllers\Trandis;

use App\Http\Controllers\Controller;
use App\Models\SpkHeader;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Statistik Utama
        $data = [
            'baru'    => SpkHeader::where('status', 'disetujui')->where('status_teknis', 'pending')->count(),
            'jadwal'  => SpkHeader::where('status_teknis', 'scheduled')->count(),
            'proses'  => SpkHeader::where('status_teknis', 'working')->count(),
            'selesai' => SpkHeader::where('status', 'selesai')->count(),
        ];

        // 2. SPK Terbaru (5 item terakhir yang masuk)
        $recent_spk = SpkHeader::with('rab.spko.pengajuan')
            ->where('status', 'disetujui')
            ->orderByDesc('disetujui_at')
            ->limit(5)
            ->get();

        // 3. Jadwal Hari Ini (Prioritas)
        $today_schedule = SpkHeader::whereDate('tgl_jadwal', Carbon::today())
            ->where('status', '!=', 'selesai')
            ->get();

        return view('trandis.index', compact('data', 'recent_spk', 'today_schedule'));
    }
}
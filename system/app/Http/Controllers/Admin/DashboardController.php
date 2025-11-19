<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index()
    {
        // kalau tidak pakai tabel users, set false saja
        $hasUsers   = false;
        $totalUsers = 0;
        // Statistik utama
        $totalPengajuan   = Pengajuan::count();
        $pengajuanMasuk   = Pengajuan::where('status', Pengajuan::ST_SUBMITTED)->count();
        $pengajuanProses  = Pengajuan::whereIn('progress_status', [
            Pengajuan::PG_SURVEY,
            Pengajuan::PG_MATERIAL,
            Pengajuan::PG_SCHEDULED,
            Pengajuan::PG_INSTALLING,
        ])->count();
        $pengajuanSelesai = Pengajuan::where('progress_status', Pengajuan::PG_INSTALLED)->count();

        // Aktivitas terakhir
        $aktivitas = Pengajuan::orderByDesc('updated_at')
            ->take(8)
            ->get(['id', 'no_pendaftaran', 'pemohon_nama', 'status', 'progress_status', 'updated_at']);

        return view('admin.index', compact(
            'hasUsers',
            'totalUsers',
            'totalPengajuan',
            'pengajuanMasuk',
            'pengajuanProses',
            'pengajuanSelesai',
            'aktivitas'
        ));
    }
}

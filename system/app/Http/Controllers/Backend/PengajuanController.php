<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PengajuanController extends Controller
{
    public function index()
    {
        $data = Pengajuan::orderByDesc('id')->paginate(10);
        // folder view: resources/views/pengajuan/index.blade.php
        return view('backend.Pengajuan.index', compact('data'));
    }

    public function create()
    {
        // resources/views/pengajuan/create.blade.php
        return view('backend.Pengajuan.create');
    }

    public function store(Request $r)
    {
        $r->validate([
            'pemohon_nama'          => 'required|string|max:255',
            'nomor_telepon'         => 'required|string|max:30',
            'alamat_pemasangan'     => 'required|string',
            'peruntukan'            => 'required|string|max:255',
            'jumlah_kran'           => 'nullable|integer|min:0',
            'penghuni_tetap'        => 'nullable|integer|min:0',
            'penghuni_tidak_tetap'  => 'nullable|integer|min:0',
            'email'                 => 'nullable|email|max:120',
            'lampiran_ktp'          => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'lampiran_kk'           => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'foto_rumah'            => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
            'denah_lokasi'          => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:4096',
            'setuju'                => 'accepted',
        ]);

        $folder = 'public/app/pengajuan/' . date('Y/m');
        if (!is_dir(public_path($folder))) mkdir(public_path($folder), 0775, true);

        $save = function ($file, $prefix) use ($folder) {
            if (!$file) return null;
            $ext = $file->getClientOriginalExtension();
            $name = $prefix.'-'.time().'-'.Str::random(5).'.'.$ext;
            $file->move(public_path($folder), $name);
            return $folder.'/'.$name;
        };

        $ktp   = $save($r->file('lampiran_ktp'), 'ktp');
        $kk    = $save($r->file('lampiran_kk'), 'kk');
        $foto  = $save($r->file('foto_rumah'), 'rumah');
        $denah = $save($r->file('denah_lokasi'), 'denah');

        DB::transaction(function () use ($r, $ktp, $kk, $foto, $denah) {
            Pengajuan::create([
                // no_pendaftaran dibuat otomatis di Model::creating
                'pemohon_nama'            => $r->pemohon_nama,
                'nomor_telepon'           => $r->nomor_telepon,
                'pekerjaan'               => $r->pekerjaan,
                'alamat_pemasangan'       => $r->alamat_pemasangan,
                'penghuni_tetap'          => (int)($r->penghuni_tetap ?? 0),
                'penghuni_tidak_tetap'    => (int)($r->penghuni_tidak_tetap ?? 0),
                'email'                   => $r->email,
                'peruntukan'              => $r->peruntukan,
                'jumlah_kran'             => (int)($r->jumlah_kran ?? 0),
                'langganan_ke_orang_lain' => $r->langganan_ke_orang_lain === 'Ya' ? 1 : 0,
                'pelanggan_terdekat'      => $r->pelanggan_terdekat,
                'ktp_url'                 => $ktp,
                'kk_url'                  => $kk,
                'foto_rumah_url'          => $foto,
                'denah_url'               => $denah,
                'setuju_pernyataan'       => 1,
                'setuju_waktu'            => now(),
                'setuju_ip'               => $r->ip(),
                'status'                  => 'SUBMITTED',
            ]);
        });

        return redirect('backend/Pengajuan')->with('success', 'Data pengajuan berhasil disimpan!');
    }

    public function show($id)
    {
    //     $row = Pengajuan::find($id);
    //     if (!$row) {
    //         return redirect()->route('backend.pengajuan.index')->with('error', 'Data tidak ditemukan.');
    //     }
    //     // resources/views/pengajuan/show.blade.php
    //     return view('backend.pengajuan.show', compact('row'));
    // }
}
}
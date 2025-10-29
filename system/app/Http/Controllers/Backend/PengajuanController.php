<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengajuan;

class PengajuanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('backend.pengajuan.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.pengajuan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       $Pengajuan = new Pengajuan();
       $Pengajuan->nama = request('nama');
       $Pengajuan->nik = request('nik');
       $Pengajuan->email = request('email');
       $Pengajuan->no_handphone = request('no_handphone');
       $Pengajuan->alamat = request('alamat');
       $Pengajuan->kecamatan = request('kecamatan');
       $Pengajuan->kelurahan = request('kelurahan');
       $Pengajuan->rt = request('rt');
       $Pengajuan->rw = request('rw');
       $Pengajuan->pekerjaan = request('pekerjaan');
       $Pengajuan->ktp = request('ktp');
       $Pengajuan->kk = request('kk');
       $Pengajuan->surat_permohonan = request('surat_permohonan');
       $Pengajuan->foto_rumah = request('foto_rumah');
       $Pengajuan->handleUploadfoto();
       $Pengajuan->save();
       return redirect('backend/Pengajuan')->with('success', 'Data pengajuan berhasil disimpan!');
      
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

        public function printSurat()
    {
        // untuk sementara tampilkan surat kosong
        return view('backend.Pengajuan.surat_permohonan');
    }

}

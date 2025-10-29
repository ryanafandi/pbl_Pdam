<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use Illuminate\Http\Request;

class PengajuanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pengajuan = Pengajuan::all();

        return view('admin.pengajuan.index', compact('pengajuan'));
        return view('admin.pengajuan.show', compact('pengajuan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $nik)
    {
        $data['pengajuan']= Pengajuan::find($nik);
        return view('admin.pengajuan.show', $data);
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
    public function destroy(string $nik)
    {
        $pengajuan = Pengajuan::find($nik);
        $pengajuan->delete();
        return redirect('admin/pengajuan')->with('success', 'Data pengajuan berhasil dihapus!');
    }

    public function updateStatus(Request $request, $nik)
{
    $pengajuan = Pengajuan::findOrFail($nik);
    $pengajuan->status = $request->status;
    $pengajuan->save();

    return redirect()->back()->with('success', 'Status pengajuan berhasil diperbarui!');
}
}

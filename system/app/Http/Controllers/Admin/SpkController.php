<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RabHeader;
use App\Models\SpkHeader;
use Illuminate\Http\Request;

class SpkController extends Controller
{
    /**
     * Daftar SPK + filter status / pencarian.
     */
    public function index(Request $request)
    {
        $q      = trim((string) $request->query('q'));
        $status = $request->query('status');

        // daftar status yang diizinkan
        $statuses = [
            'draft'          => 'Draft',
            'kirim_direktur' => 'Dikirim Direktur',
            'disetujui'      => 'Disetujui Direktur',
            'ditolak'        => 'Ditolak',
            'selesai'        => 'Selesai',
        ];

        $rows = SpkHeader::with(['rab.spko.pengajuan'])
            ->when($status && isset($statuses[$status]), function ($qq) use ($status) {
                $qq->where('status', $status);
            })
            ->when($q, function ($qq) use ($q) {
                $qq->where(function ($w) use ($q) {
                    $w->where('nomor_spk', 'like', "%{$q}%")
                        ->orWhere('nama_pelanggan', 'like', "%{$q}%")
                        ->orWhere('alamat', 'like', "%{$q}%")
                        ->orWhereHas('rab', function ($r) use ($q) {
                            $r->where('nomor_rab', 'like', "%{$q}%");
                        });
                });
            })
            ->orderByDesc('dibuat_at')
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.spk.index', compact('rows', 'q', 'status', 'statuses'));
    }

    /**
     * Form buat SPK dari 1 RAB yang sudah dibayar (PAID).
     * GET admin/spk/create/{rab}
     */
    public function create($rabId)
    {
        $rab = RabHeader::with(['spko.pengajuan'])->findOrFail($rabId);

        // Wajib sudah lunas
        if ($rab->billing_status !== 'PAID') {
            return redirect('admin/dokumen_biaya/' . $rab->id)
                ->with('error', 'Tagihan untuk RAB ini belum LUNAS. SPK belum boleh dibuat.');
        }

        // Cek kalau SPK sudah pernah dibuat
        $existing = SpkHeader::where('rab_id', $rab->id)->first();
        if ($existing) {
            return redirect('admin/spk/' . $existing->id)
                ->with('error', 'SPK untuk RAB ini sudah dibuat.');
        }

        return view('admin.spk.create', compact('rab'));
    }

    /**
     * Simpan SPK baru.
     * POST admin/spk/{rab}
     */
    public function store(Request $request, $rabId)
    {
        $rab = RabHeader::with(['spko.pengajuan'])->findOrFail($rabId);

        // Guard lagi di sisi POST (kalau user nakal kirim manual)
        if ($rab->billing_status !== 'PAID') {
            return redirect('admin/dokumen_biaya/' . $rab->id)
                ->with('error', 'Tagihan untuk RAB ini belum LUNAS. SPK belum boleh dibuat.');
        }

        $data = $request->validate([
            'nomor_spk'      => 'nullable|string|max:100',
            'pekerjaan'      => 'required|string|max:100',
            'nama_pelanggan' => 'required|string|max:120',
            'alamat'         => 'required|string|max:200',
            'lokasi'         => 'nullable|string|max:200',
            'no_pelanggan'   => 'nullable|string|max:100',
            'catatan'        => 'nullable|string',
        ]);

        // trim nomor spk
        $data['nomor_spk'] = trim((string) ($data['nomor_spk'] ?? ''));

        // jika nomor kosong → generate otomatis
        if ($data['nomor_spk'] === '') {
            $data['nomor_spk'] = SpkHeader::generateNomorSpk();
        }

        $spk = SpkHeader::create([
            'rab_id'         => $rab->id,
            'nomor_spk'      => $data['nomor_spk'],
            'pekerjaan'      => $data['pekerjaan'],
            'nama_pelanggan' => $data['nama_pelanggan'],
            'alamat'         => $data['alamat'],
            'lokasi'         => $data['lokasi'] ?? null,
            'no_pelanggan'   => $data['no_pelanggan'] ?? null,
            'catatan'        => $data['catatan'] ?? null,
            'status'         => 'draft',
            'dibuat_at'      => now(),
        ]);

        return redirect('admin/spk/' . $spk->id)
            ->with('success', 'SPK berhasil dibuat.');
    }

    /**
     * Detail 1 SPK.
     */
    public function show($id)
    {
        $row = SpkHeader::with(['rab.spko.pengajuan'])->findOrFail($id);
        return view('admin.spk.show', compact('row'));
    }

    public function sendToDirector($id)
    {
        $row = SpkHeader::findOrFail($id);

        try {
            $row->sendToDirector();
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'SPK dikirim ke Direktur.');
    }

    public function approve($id)
    {
        $row = SpkHeader::findOrFail($id);

        try {
            $row->approveByDirector();
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'SPK disetujui. Bisa diteruskan ke Tim Trandis.');
    }

    public function reject(Request $request, $id)
    {
        $row  = SpkHeader::findOrFail($id);
        $note = $request->input('catatan');

        try {
            $row->rejectByDirector($note);
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'SPK ditolak oleh Direktur.');
    }

    public function markFinished($id)
    {
        $row = SpkHeader::findOrFail($id);

        try {
            $row->markFinished();
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'SPK ditandai selesai pemasangan.');
    }
}

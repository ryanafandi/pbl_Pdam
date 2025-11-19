<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RabHeader;
use Illuminate\Http\Request;

class DokumenBiayaController extends Controller
{
    // Daftar RAB yang siap dibuatkan dokumen biaya
    // app/Http/Controllers/Admin/DokumenBiayaController.php

    public function index(Request $request)
    {
        $q      = trim((string) $request->query('q'));
        $status = $request->query('status'); // DRAFT / SENT / PAID / null

        $rows = RabHeader::with('spko.pengajuan')
            ->where('status', 'disetujui')
            // filter teks
            ->when($q, function ($qq) use ($q) {
                $qq->where(function ($w) use ($q) {
                    $w->where('nomor_rab', 'like', "%{$q}%")
                        ->orWhereHas('spko', function ($spko) use ($q) {
                            $spko->where('nomor_spko', 'like', "%{$q}%");
                        })
                        ->orWhereHas('spko.pengajuan', function ($p) use ($q) {
                            $p->where('no_pendaftaran', 'like', "%{$q}%")
                                ->orWhere('pemohon_nama', 'like', "%{$q}%")
                                ->orWhere('alamat_pemasangan', 'like', "%{$q}%");
                        })
                        ->orWhere('rna_nomor', 'like', "%{$q}%")
                        ->orWhere('persetujuan_nomor', 'like', "%{$q}%");
                });
            })
            // filter status billing
            ->when($status, function ($qq) use ($status) {
                if ($status === 'DRAFT') {
                    $qq->where(function ($w) {
                        $w->whereNull('billing_status')
                            ->orWhere('billing_status', 'DRAFT');
                    });
                } else {
                    $qq->where('billing_status', $status);
                }
            })
            ->orderByDesc('approved_at')
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        // list status utk dropdown di view
        $statuses = [
            'DRAFT' => 'Draft Dokumen',
            'SENT'  => 'Dikirim ke Pelanggan',
            'PAID'  => 'Lunas',
        ];

        return view('admin.dokumen_biaya.index', compact('rows', 'q', 'status', 'statuses'));
    }



    // Detail + form dokumen biaya 1 RAB
    public function show($id)
    {
        $row = RabHeader::with(['spko.pengajuan'])->findOrFail($id);
        return view('admin.dokumen_biaya.show', compact('row'));
    }

    public function store(Request $request, $id)
    {
        $row = RabHeader::findOrFail($id);

        $data = $request->validate([
            'rna_nomor'           => 'nullable|string|max:50',
            'rna_tanggal'         => 'nullable|date',
            'persetujuan_nomor'   => 'nullable|string|max:50',
            'persetujuan_tanggal' => 'nullable|date',
            'jatuh_tempo'         => 'nullable|date',
            'billing_note'        => 'nullable|string',
        ]);

        // === RNA: auto-generate kalau belum ada & input kosong ===
        if (empty($row->rna_nomor) && empty($data['rna_nomor'] ?? null)) {
            $data['rna_nomor']   = RabHeader::generateRnaNumber();
            $data['rna_tanggal'] = $data['rna_tanggal'] ?? now()->toDateString(); // default hari ini
        }

        // === Nomor Persetujuan: auto-generate kalau belum ada & input kosong ===
        if (empty($row->persetujuan_nomor) && empty($data['persetujuan_nomor'] ?? null)) {
            $data['persetujuan_nomor']   = RabHeader::generatePersetujuanNumber();
            $data['persetujuan_tanggal'] = $data['persetujuan_tanggal'] ?? now()->toDateString();
        }

        // Billing status default
        if (!$row->billing_status) {
            $data['billing_status'] = 'DRAFT';
        }

        // Simpan sekali saja
        $row->update($data);

        return redirect('admin/dokumen_biaya/' . $row->id)
            ->with('success', 'Dokumen biaya disimpan.');
    }




    // Cetak RNA (tampilan khusus print)
    public function printRna($id)
    {
        $row = RabHeader::with(['spko.pengajuan'])->findOrFail($id);
        return view('admin.dokumen_biaya.print-rna', compact('row'));
    }

    // Cetak Bukti Persetujuan Biaya Penyambungan
    public function printPersetujuan($id)
    {
        $row = RabHeader::with(['spko.pengajuan'])->findOrFail($id);
        return view('admin.dokumen_biaya.print-persetujuan', compact('row'));
    }

    // Set status "dikirim ke pelanggan"
    public function sendToCustomer($id)
    {
        $row = RabHeader::findOrFail($id);

        // Pastikan minimal dokumen sudah lengkap secukupnya
        if (!$row->rna_nomor || !$row->persetujuan_nomor || !$row->jatuh_tempo) {
            return back()->with('error', 'Lengkapi No. RNA, No. Persetujuan, dan Jatuh Tempo sebelum mengirim ke pelanggan.');
        }

        // Kalau sudah PAID, jangan kirim lagi
        if ($row->billing_status === 'PAID') {
            return back()->with('error', 'Dokumen ini sudah ditandai lunas.');
        }

        $row->update([
            'billing_status' => 'SENT',
            'billing_sent_at' => now(),
        ]);

        // << ini yang kurang
        return redirect('admin/dokumen_biaya/' . $row->id)
            ->with('success', 'Dokumen biaya dikirim ke pelanggan dan siap diproses kasir.');
    }


    public function destroy($id)
    {
        $row = RabHeader::findOrFail($id);

        // Kalau sudah lunas, optional: jangan boleh dihapus
        if ($row->billing_status === 'PAID') {
            return back()->with('error', 'Tagihan sudah LUNAS, dokumen tidak boleh dihapus.');
        }

        // Reset semua kolom dokumen biaya
        $row->update([
            'rna_nomor'           => null,
            'rna_tanggal'         => null,
            'persetujuan_nomor'   => null,
            'persetujuan_tanggal' => null,
            'jatuh_tempo'         => null,
            'billing_status'      => null,
            'billing_sent_at'     => null,
            'billing_paid_at'     => null,
            'billing_note'        => null,
        ]);

        return redirect('admin/dokumen_biaya')
            ->with('success', 'Dokumen biaya (RNA & persetujuan) sudah dihapus. RAB tetap tersimpan.');
    }
}

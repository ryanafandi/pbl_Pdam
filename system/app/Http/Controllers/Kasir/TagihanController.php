<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\RabHeader;
use Illuminate\Http\Request;

class TagihanController extends Controller
{
    /**
     * Daftar tagihan (RAB) yang sudah dikirim admin dan siap dibayar.
     */
    public function index(Request $request)
    {
        $status = $request->query('status'); // optional: SENT / PAID

        $q = RabHeader::with('spko.pengajuan')
            ->whereNotNull('billing_status');

        if ($status) {
            // kalau user kirim ?status=SENT atau ?status=PAID
            $q->where('billing_status', $status);
        } else {
            // default: hanya tampilkan yang sudah dikirim ke pelanggan
            $q->where('billing_status', RabHeader::BILL_SENT);
        }

        $rows = $q->orderByDesc('billing_sent_at')
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view('kasir.tagihan.index', compact('rows', 'status'));
    }

    /**
     * Detail 1 tagihan + form pembayaran.
     */
    public function show($id)
    {
        $row = RabHeader::with('spko.pengajuan')->findOrFail($id);

        return view('kasir.tagihan.show', compact('row'));
    }

    /**
     * Proses pembayaran: tandai LUNAS.
     */
    public function pay(Request $request, $id)
    {
        $row = RabHeader::findOrFail($id);

        // Hanya boleh dibayar jika statusnya SENT
        if ($row->billing_status !== RabHeader::BILL_SENT) {
            return back()->with('error', 'Tagihan ini belum dikirim admin atau sudah lunas.');
        }

        $data = $request->validate([
            'paid_at'      => 'nullable|date',
            'payment_note' => 'nullable|string|max:5000',
        ]);

        $row->billing_status  = RabHeader::BILL_PAID;
        $row->billing_paid_at = $data['paid_at'] ?? now();

        // Tambahkan catatan kasir ke billing_note (tidak menghapus catatan lama)
        if (!empty($data['payment_note'])) {
            $prefix = '[Kasir] ';
            $note   = $prefix . trim($data['payment_note']);

            $row->billing_note = trim(
                ($row->billing_note ? $row->billing_note . "\n" : '') . $note
            );
        }

        $row->save();

        return redirect('kasir/tagihan/' . $row->id)
            ->with('success', 'Pembayaran berhasil dicatat dan tagihan ditandai LUNAS.');
    }
}

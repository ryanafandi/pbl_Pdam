<?php

namespace App\Http\Controllers\Perencanaan;

use App\Http\Controllers\Controller;
use App\Models\Spko;
use App\Models\RabHeader;
use App\Models\RabDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class RabController extends Controller
{
    /**
     * Daftar SPKO untuk menu "Susun RAB"
     */
    public function index()
    {
        $spko = Spko::with(['pengajuan', 'survei', 'rab'])
            ->forPerencanaanQueue()
            ->orderByDesc('id')
            ->paginate(15);

        return view('perencanaan.rab.index', compact('spko'));
    }

    /**
     * Detail RAB untuk 1 SPKO
     * GET /perencanaan/rab/{spko}
     */
    public function show($spko)
    {
        $row = Spko::with([
            'pengajuan',
            'survei',
            'rab.details',
        ])->findOrFail($spko);

        $rab = $row->rab; // bisa null

        return view('perencanaan.rab.show', compact('row', 'rab'));
    }

    /**
     * Form buat RAB baru
     * GET /perencanaan/rab/{spko}/create
     */
    public function create($spko)
    {
        $row = Spko::with(['pengajuan', 'survei', 'rab.details'])->findOrFail($spko);

        // kalau sudah ada RAB, arahkan ke edit
        if ($row->rab) {
            return redirect('perencanaan/rab/' . $row->id . '/edit')
                ->with('info', 'RAB sudah dibuat, silakan ubah melalui form edit.');
        }

        $rab   = new RabHeader(['spko_id' => $row->id]);
        $items = collect();

        return view('perencanaan.rab.create', compact('row', 'rab', 'items'));
    }

    /**
     * Simpan RAB baru (header + detail)
     * POST /perencanaan/rab/{spko}
     */
    public function store(Request $request, $spko)
    {
        $row = Spko::with(['pengajuan', 'survei'])->findOrFail($spko);

        // validasi + ambil header & items
        [$header, $items] = $this->validatePayload($request);

        // hitung subtotal berdasarkan items
        [$subDinas, $subPersil, $totalItems] = $this->hitungSubtotal($items);

        DB::transaction(function () use ($row, $header, $items, $subDinas, $subPersil, $totalItems) {

            // --- HEADER ---
            $rab = new RabHeader();
            $rab->spko_id   = $row->id;
            $rab->nomor_rab = $this->generateNomorRab($row);

            // nama & alamat diambil dari pengajuan / SPKO
            $rab->nama_pelanggan = $row->pengajuan->pemohon_nama
                ?? $row->pemilik_nama
                ?? '-';

            $rab->alamat = $row->pengajuan->alamat_pemasangan
                ?? $row->alamat
                ?? '-';

            $rab->kategori_tarif       = $header['kategori_tarif'] ?? null;
            $rab->pemasangan_terobos   = $header['pemasangan_terobos'] ?? 0;
            $rab->biaya_pendaftaran    = $header['biaya_pendaftaran'] ?? 0;
            $rab->biaya_admin          = $header['biaya_admin'] ?? 0;
            $rab->subtotal_pipa_dinas  = $subDinas;
            $rab->subtotal_pipa_persil = $subPersil;
            $rab->total = $totalItems + $rab->biaya_pendaftaran + $rab->biaya_admin;

            // status awal
            $rab->status              = 'draft';
            $rab->sent_to_director_at = null;
            $rab->approved_at         = null;
            $rab->approved_by         = null;
            $rab->rejected_at         = null;
            $rab->rejected_by         = null;
            $rab->rejection_note      = null;

            $rab->save();

            // --- DETAIL ---
            foreach ($items as $item) {
                RabDetail::create([
                    'rab_id'       => $rab->id,
                    'kategori'     => $item['kategori'],
                    'uraian'       => $item['uraian'],
                    'satuan'       => $item['satuan'],
                    'volume'       => $item['volume'],
                    'harga_satuan' => $item['harga_satuan'],
                    'jumlah'       => $item['jumlah'],
                    'subtotal'     => $item['subtotal'],
                ]);
            }
        });

        return redirect('perencanaan/rab/' . $row->id)
            ->with('success', 'RAB berhasil dibuat.');
    }

    /**
     * Form edit RAB
     * GET /perencanaan/rab/{spko}/edit
     */
    public function edit($spko)
    {
        $row = Spko::with(['pengajuan', 'survei', 'rab.details'])->findOrFail($spko);

        if (!$row->rab) {
            return redirect('perencanaan/rab/' . $row->id . '/create')
                ->with('info', 'RAB belum dibuat, silakan susun RAB terlebih dahulu.');
        }

        $rab   = $row->rab;
        $items = $rab->details;

        return view('perencanaan.rab.edit', compact('row', 'rab', 'items'));
    }

    /**
     * Update RAB (header + detail)
     * PUT /perencanaan/rab/{spko}
     */
    public function update(Request $request, $spko)
    {
        $row = Spko::with(['pengajuan', 'survei', 'rab.details'])->findOrFail($spko);

        [$header, $items] = $this->validatePayload($request);
        [$subDinas, $subPersil, $totalItems] = $this->hitungSubtotal($items);

        DB::transaction(function () use ($row, $header, $items, $subDinas, $subPersil, $totalItems) {

            /** @var \App\Models\RabHeader $rab */
            $rab = $row->rab ?? new RabHeader(['spko_id' => $row->id]);

            if (!$rab->nomor_rab) {
                $rab->nomor_rab = $this->generateNomorRab($row);
            }

            $rab->nama_pelanggan = $row->pengajuan->pemohon_nama
                ?? $row->pemilik_nama
                ?? '-';

            $rab->alamat = $row->pengajuan->alamat_pemasangan
                ?? $row->alamat
                ?? '-';

            $rab->kategori_tarif       = $header['kategori_tarif'] ?? null;
            $rab->pemasangan_terobos   = $header['pemasangan_terobos'] ?? 0;
            $rab->biaya_pendaftaran    = $header['biaya_pendaftaran'] ?? 0;
            $rab->biaya_admin          = $header['biaya_admin'] ?? 0;
            $rab->subtotal_pipa_dinas  = $subDinas;
            $rab->subtotal_pipa_persil = $subPersil;
            $rab->total = $totalItems + $rab->biaya_pendaftaran + $rab->biaya_admin;

            // jangan paksa ubah status di sini, biarkan mengikuti alur kirim/approve/reject
            if (!$rab->status) {
                $rab->status = 'draft';
            }

            $rab->save();

            // reset detail lama
            RabDetail::where('rab_id', $rab->id)->delete();

            // insert detail baru
            foreach ($items as $item) {
                RabDetail::create([
                    'rab_id'       => $rab->id,
                    'kategori'     => $item['kategori'],
                    'uraian'       => $item['uraian'],
                    'satuan'       => $item['satuan'],
                    'volume'       => $item['volume'],
                    'harga_satuan' => $item['harga_satuan'],
                    'jumlah'       => $item['jumlah'],
                    'subtotal'     => $item['subtotal'],
                ]);
            }
        });

        return redirect('perencanaan/rab/' . $row->id)
            ->with('success', 'RAB berhasil diperbarui.');
    }

    /**
     * Hapus RAB untuk 1 SPKO
     */
    /** Hapus 1 RAB beserta detailnya */
    public function destroy($id)
    {
        $rab = RabHeader::with('details')->findOrFail($id);

        DB::transaction(function () use ($rab) {
            // hapus detail dulu
            $rab->details()->delete();
            // baru hapus header
            $rab->delete();
        });

        return back()->with('success', 'RAB berhasil dihapus.');
    }
    // ================== Helpers ==================

    /**
     * Validasi payload dari form RAB.
     * Form menggunakan nama input: kategori[], uraian[], satuan[], volume[], harga_satuan[], jumlah[]
     */
    private function validatePayload(Request $request): array
    {
        // header yang dikirim dari form
        $header = $request->validate([
            'kategori_tarif'     => 'nullable|string|max:50',
            'pemasangan_terobos' => 'nullable|boolean',
            'biaya_pendaftaran'  => 'nullable|numeric',
            'biaya_admin'        => 'nullable|numeric',
        ]);

        // ambil array per kolom
        $kategori     = $request->input('kategori', []);
        $uraian       = $request->input('uraian', []);
        $satuan       = $request->input('satuan', []);
        $volume       = $request->input('volume', []);
        $hargaSatuan  = $request->input('harga_satuan', []);
        $jumlahInput  = $request->input('jumlah', []);  // bisa kosong

        $rowCount = max(
            count($kategori),
            count($uraian),
            count($satuan),
            count($volume),
            count($hargaSatuan),
            count($jumlahInput)
        );

        $items = [];

        for ($i = 0; $i < $rowCount; $i++) {
            $ura = trim($uraian[$i] ?? '');

            // lewati baris kosong
            if ($ura === '') {
                continue;
            }

            $kat = $kategori[$i] ?? 'pipa_dinas';
            $sat = $satuan[$i] ?? '';

            $vol   = (float) ($volume[$i] ?? 0);
            $harga = (float) ($hargaSatuan[$i] ?? 0);

            $jumlahRaw = $jumlahInput[$i] ?? null;

            if ($jumlahRaw === null || $jumlahRaw === '') {
                $jml = $vol * $harga;
            } else {
                $jml = (float) $jumlahRaw;
            }

            $items[] = [
                'kategori'     => $kat,
                'uraian'       => $ura,
                'satuan'       => $sat,
                'volume'       => $vol,
                'harga_satuan' => $harga,
                'jumlah'       => $jml,
                'subtotal'     => $jml,
            ];
        }

        if (count($items) === 0) {
            throw ValidationException::withMessages([
                'items' => 'Minimal satu baris rincian RAB harus diisi.',
            ]);
        }

        return [$header, $items];
    }

    /**
     * Hitung subtotal pipa dinas / persil + total item
     */
    private function hitungSubtotal(array $items): array
    {
        $subDinas   = 0;
        $subPersil  = 0;
        $totalItems = 0;

        foreach ($items as $item) {
            $jumlah   = $item['jumlah'] ?? 0;
            $kategori = $item['kategori'] ?? 'pipa_dinas';

            if ($kategori === 'pipa_persil') {
                $subPersil += $jumlah;
            } else {
                $subDinas += $jumlah;
            }

            $totalItems += $jumlah;
        }

        return [$subDinas, $subPersil, $totalItems];
    }

    /**
     * Generate nomor RAB otomatis, contoh: RAB-20251113-0003
     */
    private function generateNomorRab(Spko $spko): string
    {
        $tanggal = Carbon::now()->format('Ymd');

        $last = RabHeader::whereDate('created_at', Carbon::today())
            ->orderByDesc('id')
            ->value('nomor_rab');

        $running = 1;

        if ($last) {
            $parts       = explode('-', $last);
            $lastCounter = (int) end($parts);
            $running     = $lastCounter + 1;
        }

        return sprintf('RAB-%s-%04d', $tanggal, $running);
    }

    /**
     * KIRIM KE DIREKTUR
     */
    // Kirim RAB ke Direktur
public function kirimKeDirektur($spko)
{
    $row = Spko::with('rab')->findOrFail($spko);

    if (!$row->rab) {
        return back()->with('error', 'RAB belum dibuat.');
    }

    $rab = $row->rab;

    // set manual lalu save
    $rab->status = 'dikirim';
    $rab->sent_to_director_at = now();
    $rab->save();

    return back()->with('success', 'RAB berhasil dikirim ke Direktur.');
}

// Direktur menyetujui RAB
public function approve($spko)
{
    $row = Spko::with('rab')->findOrFail($spko);

    if (!$row->rab) {
        return back()->with('error', 'RAB belum ditemukan.');
    }

    // nanti kalau sudah ada user login, bisa pakai auth()->user()->name
    $namaDirektur = config('app.direktur_default', 'Direktur');

    $rab = $row->rab;
    $rab->status        = 'disetujui';
    $rab->approved_at   = now();
    $rab->approved_by   = $namaDirektur;
    $rab->rejected_at   = null;
    $rab->rejected_by   = null;
    $rab->rejection_note = null;
    $rab->save();

    return back()->with('success', 'RAB telah disetujui Direktur.');
}

// Direktur menolak RAB
public function reject(Request $request, $spko)
{
    $row = Spko::with('rab')->findOrFail($spko);

    if (!$row->rab) {
        return back()->with('error', 'RAB belum ditemukan.');
    }

    $request->validate([
        'alasan' => 'required|string',
    ]);

    $namaDirektur = config('app.direktur_default', 'Direktur');

    $rab = $row->rab;
    $rab->status        = 'ditolak';
    $rab->rejected_at   = now();
    $rab->rejected_by   = $namaDirektur;
    $rab->rejection_note = $request->alasan;
    $rab->save();

    return back()->with('success', 'RAB telah ditolak.');
}

}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Spko;
use App\Models\Pengajuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SpkoController extends Controller
{
    /** List SPKO + pencarian & filter */
    public function index(Request $request)
    {
        // --- per page ---
        $perPage = (int) $request->get('per_page', 15);
        if ($perPage <= 0 || $perPage > 200) {
            $perPage = 15;
        }

        // query dasar + relasi pengajuan
        $q = Spko::with('pengajuan');

        // --- filter pencarian q (nomor / pemohon / alamat / no pendaftaran) ---
        if ($request->filled('q')) {
            $search = trim($request->q);

            $q->where(function ($w) use ($search) {
                $w->where('nomor_spko', 'like', "%{$search}%")
                  ->orWhere('pemilik_nama', 'like', "%{$search}%")
                  ->orWhere('alamat', 'like', "%{$search}%")
                  // cari juga di tabel pengajuan
                  ->orWhereHas('pengajuan', function ($p) use ($search) {
                      $p->where('no_pendaftaran', 'like', "%{$search}%")
                        ->orWhere('pemohon_nama', 'like', "%{$search}%")
                        ->orWhere('alamat_pemasangan', 'like', "%{$search}%");
                  });
            });
        }

        // --- filter status ---
        if ($request->filled('status')) {
            $q->where('status', $request->status);
        }

        // --- eksekusi + paginate ---
        $data = $q->orderByDesc('created_at')
                  ->paginate($perPage)
                  ->appends($request->query()); // supaya query string tetap ada saat pindah halaman

        return view('admin.spko.index', compact('data'));
    }

    public function create(Request $request)
    {
        $pengajuan = Pengajuan::where('status', Pengajuan::ST_APPROVED)->get();
        $selected  = $request->query('pengajuan_id');
        return view('admin.spko.create', compact('pengajuan', 'selected'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pengajuan_id' => 'required|integer|exists:pengajuan,id',
            'tanggal_spko' => 'required|date',
            'tujuan'       => 'required|string|max:120',
            // opsional
            'laporan_ringkas'    => 'nullable|string',
            'disurvey_oleh_nama' => 'nullable|string|max:120',
            'disurvey_oleh_nipp' => 'nullable|string|max:60',
            'kabag_teknik_nama'  => 'nullable|string|max:120',
            'kabag_teknik_nipp'  => 'nullable|string|max:60',
            'direktur_nama'      => 'nullable|string|max:120',
            'direktur_nipp'      => 'nullable|string|max:60',
            'kepada_jabatan'     => 'nullable|string|max:120',
            'catatan'            => 'nullable|string',
        ]);

        $pengajuan = Pengajuan::findOrFail($validated['pengajuan_id']);
        if ($pengajuan->status !== Pengajuan::ST_APPROVED) {
            return back()->with('error', 'Pengajuan belum disetujui direktur.')->withInput();
        }

        $spko = new Spko();
        $spko->fill($validated);

        // Auto ambil dari pengajuan
        $spko->pemilik_nama = $pengajuan->pemohon_nama;
        $spko->alamat       = $pengajuan->alamat_pemasangan;
        $spko->lokasi       = $pengajuan->alamat_pemasangan;

        // Radio 'terobos'
        $spko->terobos = (int) $request->input('terobos', 0);

        $spko->status = Spko::ST_DRAFT;
        $spko->save();

        return redirect('admin/spko/' . $spko->id)
            ->with('success', 'SPKO berhasil dibuat dengan nomor: ' . $spko->nomor_spko);
    }

    public function show($id)
    {
        $row = Spko::with('pengajuan')->findOrFail($id);
        return view('admin.spko.show', compact('row'));
    }

    public function edit($id)
    {
        $row = Spko::findOrFail($id);
        return view('admin.spko.edit', compact('row'));
    }

    public function update(Request $request, $id)
    {
        $row = Spko::findOrFail($id);

        $validated = $request->validate([
            'tanggal_spko'       => 'nullable|date',
            'tujuan'             => 'nullable|string|max:120',
            'laporan_ringkas'    => 'nullable|string',
            'disurvey_oleh_nama' => 'nullable|string|max:120',
            'disurvey_oleh_nipp' => 'nullable|string|max:60',
            'kabag_teknik_nama'  => 'nullable|string|max:120',
            'kabag_teknik_nipp'  => 'nullable|string|max:60',
            'direktur_nama'      => 'nullable|string|max:120',
            'direktur_nipp'      => 'nullable|string|max:60',
            'kepada_jabatan'     => 'nullable|string|max:120',
            'catatan'            => 'nullable|string',
            'status'             => 'nullable|string|in:DRAFT,SENT_TO_PLANNING,SENT_TO_DIRECTOR,APPROVED,REJECTED,DONE',
        ]);

        $row->fill($validated);
        $row->terobos = (int) $request->input('terobos', (int)($row->terobos ?? 0));
        $row->save();

        return redirect('admin/spko/' . $row->id)->with('success', 'Data SPKO diperbarui.');
    }

    public function destroy($id)
    {
        $row = Spko::findOrFail($id);
        $row->delete();
        return redirect('admin/spko')->with('success', 'SPKO dihapus.');
    }

    // === KIRIM KE TIM PERENCANAAN ===
    public function kirimPerencanaan(Spko $spko)
    {
        if ($spko->status !== Spko::ST_DRAFT) {
            return back()->with('error', 'Hanya SPKO berstatus DRAFT yang bisa dikirim ke Perencanaan.');
        }

        DB::transaction(function () use ($spko) {
            $spko->update([
                'status'              => Spko::ST_SENT_PLANNING,
                'sent_to_planning_at' => now(),
            ]);
        });

        return back()->with('success', 'SPKO berhasil dikirim ke Tim Perencanaan.');
    }
}

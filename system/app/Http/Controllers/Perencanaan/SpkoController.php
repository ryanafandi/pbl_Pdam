<?php

namespace App\Http\Controllers\Perencanaan;

use App\Http\Controllers\Controller;
use App\Models\Spko;
use App\Models\Survei;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class SpkoController extends Controller
{
    /**
     * List SPKO untuk Tim Perencanaan
     * - bisa search nomor / nama / alamat
     * - bisa filter status
     */
    public function index(Request $request)
    {
        $q      = trim((string) $request->query('q'));
        $status = $request->query('status');

        $spko = Spko::with(['pengajuan', 'survei'])
            // kalau mau khusus antrian Perencanaan saja, bisa pakai:
            // ->forPerencanaanQueue()
            ->when($status, fn ($qq) => $qq->where('status', $status))
            ->when($q, function ($qq) use ($q) {
                $qq->where(function ($w) use ($q) {
                    $w->where('nomor_spko', 'like', "%{$q}%")
                        ->orWhere('pemilik_nama', 'like', "%{$q}%")
                        ->orWhere('alamat', 'like', "%{$q}%")
                        ->orWhereHas('pengajuan', function ($p) use ($q) {
                            $p->where('pemohon_nama', 'like', "%{$q}%");
                        });
                });
            })
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        $statuses = [
            Spko::ST_DRAFT,
            Spko::ST_SENT_PLANNING,
            Spko::ST_SENT_DIRECTOR,
            Spko::ST_APPROVED,
            Spko::ST_REJECTED,
            Spko::ST_DONE,
        ];

        return view('perencanaan.spko.index', [
            'spko'     => $spko,
            'q'        => $q,
            'status'   => $status,
            'statuses' => $statuses,
        ]);
    }

    /**
     * Detail 1 SPKO
     */
    public function show($id)
    {
        $row = Spko::with(['pengajuan', 'survei'])->findOrFail($id);
        return view('perencanaan.spko.show', compact('row'));
    }

    /**
     * Form atur / ubah jadwal survei
     * URL kira-kira: GET /perencanaan/spko/{id}/edit-jadwal
     */
    public function editSchedule($id)
    {
        $row = Spko::with(['pengajuan', 'survei'])->findOrFail($id);
        return view('perencanaan.spko.edit', compact('row'));
    }

    /**
     * Simpan jadwal survei + sinkron ke tabel survei
     * URL kira-kira: PUT /perencanaan/spko/{id}/edit-jadwal
     */
    public function updateSchedule(Request $request, $id)
    {
        $row = Spko::findOrFail($id);

        // Sesuaikan dengan "name" di form edit-jadwal.blade.php
        $validated = $request->validate([
            'tanggal_survei' => 'required|date',
            'jam_survei'     => 'required|string',          // contoh: "10:32" atau "10:32 PM"
            'petugas'        => 'required|string|max:120',
            'nipp'           => 'nullable|string|max:60',
            'catatan'        => 'nullable|string',
        ]);

        // Gabungkan tanggal + jam menjadi datetime lengkap
        // Carbon cukup pintar menangani format jam "10:32 PM" dll.
        $jadwalString = $validated['tanggal_survei'] . ' ' . $validated['jam_survei'];
        $jadwal       = Carbon::parse($jadwalString);   // instance Carbon (datetime)

        DB::transaction(function () use ($row, $validated, $jadwal) {
            // ====== SIMPAN KE TABEL SPKO ======
            $row->survey_scheduled_at = $jadwal;                  // cast sebagai datetime di model
            $row->disurvey_oleh_nama  = $validated['petugas'];
            $row->disurvey_oleh_nipp  = $validated['nipp'] ?? null;
            // Kalau tabel spko punya kolom catatan, boleh pakai:
            // $row->catatan             = $validated['catatan'] ?? $row->catatan;
            $row->save();

            // ====== SINKRON KE TABEL SURVEI ======
            $sv = Survei::firstOrNew(['spko_id' => $row->id]);
            $sv->scheduled_at = $jadwal;
            $sv->petugas_nama = $validated['petugas'];
            $sv->petugas_nipp = $validated['nipp'] ?? null;
            if (!empty($validated['catatan'])) {
                $sv->catatan = $validated['catatan'];
            }
            $sv->save();
        });

        return redirect('perencanaan/spko/' . $row->id)
            ->with('success', 'Jadwal survei berhasil disimpan.');
    }
}

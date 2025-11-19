<?php

namespace App\Http\Controllers\Perencanaan;

use App\Http\Controllers\Controller;
use App\Models\Spko;
use App\Models\Survei;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class JadwalController extends Controller
{
    /**
     * Daftar penjadwalan (kalender/daftar).
     * Urutan aman: survey_scheduled_at desc, lalu id desc.
     */
    public function index()
{
    $spko = Spko::with(['pengajuan', 'survei'])
        // HANYA SPKO yang sudah punya jadwal survei
        ->whereNotNull('survey_scheduled_at')
        ->orderByDesc('survey_scheduled_at')
        ->orderByDesc('id')
        ->paginate(15);

    return view('perencanaan.jadwal.index', compact('spko'));
}

    /**
     * Form buat jadwal untuk 1 SPKO (opsional ?spko=ID untuk preselect).
     */
    public function create(Request $request)
    {
        $spkoId = (int) $request->query('spko');
        $row = $spkoId ? Spko::with('survei')->findOrFail($spkoId) : null;

        return view('perencanaan.jadwal.create', compact('row'));
    }

    /**
     * Simpan jadwal + sinkron ke tabel survei (upsert).
     * Pada store, jadwal DIWAJIBKAN (pakai scheduled_at ATAU tanggal+jam).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'spko_id'      => 'required|integer|exists:spko,id',

            // User bebas pilih salah satu model input jadwal:
            'scheduled_at' => 'nullable|date',
            'tanggal'      => 'nullable|date',
            'jam'          => 'nullable|date_format:H:i',

            'petugas_nama' => 'nullable|string|max:120',
            'petugas_nipp' => 'nullable|string|max:60',
            'catatan'      => 'nullable|string',
        ]);

        $row = Spko::findOrFail($validated['spko_id']);

        // Bangun datetime dari input
        $built = $this->buildDateTime(
            $validated['scheduled_at'] ?? null,
            $validated['tanggal'] ?? null,
            $validated['jam'] ?? null
        );

        // Pada STORE, jadwal harus ada:
        if ($built['incomplete']) {
            return back()
                ->withErrors(['scheduled_at' => 'Lengkapi tanggal dan jam, atau gunakan satu kolom Jadwal (datetime).'])
                ->withInput();
        }
        if (is_null($built['value'])) {
            return back()
                ->withErrors(['scheduled_at' => 'Jadwal wajib diisi.'])
                ->withInput();
        }

        DB::transaction(function () use ($row, $validated, $built) {
            // ===== Simpan di SPKO (kompatibel modul lama)
            $row->survey_scheduled_at = $built['value'];
            $row->disurvey_oleh_nama  = $validated['petugas_nama'] ?? null;
            $row->disurvey_oleh_nipp  = $validated['petugas_nipp'] ?? null;
            if (!empty($validated['catatan'])) {
                $row->catatan = $validated['catatan'];
            }
            $row->save();

            // ===== Sinkron ke SURVEI (upsert by spko_id)
            $sv = Survei::firstOrNew(['spko_id' => $row->id]);
            $sv->scheduled_at = $built['value'];
            if (!empty($validated['petugas_nama'])) $sv->petugas_nama = $validated['petugas_nama'];
            if (!empty($validated['petugas_nipp'])) $sv->petugas_nipp = $validated['petugas_nipp'];
            $sv->save();
        });

        return redirect('perencanaan/jadwal')->with('success', 'Jadwal dibuat & disinkronkan.');
    }

    /**
     * Detail jadwal per SPKO.
     */
    public function show($spko)
    {
        $row = Spko::with(['pengajuan', 'survei'])->findOrFail($spko);
        return view('perencanaan.jadwal.show', compact('row'));
    }

    /**
     * Form edit jadwal untuk 1 SPKO.
     */
    public function edit($spko)
    {
        $row = Spko::with(['pengajuan', 'survei'])->findOrFail($spko);
        return view('perencanaan.jadwal.edit', compact('row'));
    }

    /**
     * Update jadwal + sinkron ke tabel survei (upsert).
     * Tidak mengosongkan jadwal jika user tidak mengubah bagian jadwal.
     */
    public function update(Request $request, $spko)
    {
        $row = Spko::with('survei')->findOrFail($spko);

        $validated = $request->validate([
            'scheduled_at' => 'nullable|date',
            'tanggal'      => 'nullable|date',
            'jam'          => 'nullable|date_format:H:i',

            'petugas_nama' => 'nullable|string|max:120',
            'petugas_nipp' => 'nullable|string|max:60',
            'catatan'      => 'nullable|string',
        ]);

        // Bangun datetime dari input (boleh kosong => tidak diubah)
        $built = $this->buildDateTime(
            $validated['scheduled_at'] ?? null,
            $validated['tanggal'] ?? null,
            $validated['jam'] ?? null
        );

        if ($built['incomplete']) {
            return back()
                ->withErrors(['scheduled_at' => 'Lengkapi tanggal dan jam, atau kosongkan keduanya bila tidak ingin mengubah jadwal.'])
                ->withInput();
        }

        DB::transaction(function () use ($row, $validated, $built) {
            // ===== Update SPKO (hanya jika ada perubahan)
            if (!is_null($built['value'])) {
                $row->survey_scheduled_at = $built['value'];
            }
            if (array_key_exists('petugas_nama', $validated)) {
                // kalau input kosong, set null; kalau tidak isi field-nya, jangan disentuh
                $row->disurvey_oleh_nama = $validated['petugas_nama'] ?? null;
            }
            if (array_key_exists('petugas_nipp', $validated)) {
                $row->disurvey_oleh_nipp = $validated['petugas_nipp'] ?? null;
            }
            if (array_key_exists('catatan', $validated)) {
                $row->catatan = $validated['catatan'] ?? null;
            }
            $row->save();

            // ===== Upsert SURVEI (jaga data teknis tetap aman)
            $sv = $row->survei ?: new Survei(['spko_id' => $row->id]);

            if (!is_null($built['value'])) {
                $sv->scheduled_at = $built['value'];
            }
            if (array_key_exists('petugas_nama', $validated)) {
                $sv->petugas_nama = $validated['petugas_nama'] ?? null;
            }
            if (array_key_exists('petugas_nipp', $validated)) {
                $sv->petugas_nipp = $validated['petugas_nipp'] ?? null;
            }

            $sv->save();
        });

        return redirect('perencanaan/jadwal')->with('success', 'Jadwal diperbarui & disinkronkan.');
    }

    /**
     * Hapus/clear jadwal + bersihkan nilai terkait di survei (data teknis tetap aman).
     */
    public function destroy($spko)
    {
        $row = Spko::findOrFail($spko);

        DB::transaction(function () use ($row) {
            // Kosongkan di SPKO
            $row->survey_scheduled_at = null;
            $row->disurvey_oleh_nama  = null;
            $row->disurvey_oleh_nipp  = null;
            $row->save();

            // Kosongkan jadwal & petugas di SURVEI
            $sv = Survei::where('spko_id', $row->id)->first();
            if ($sv) {
                $sv->scheduled_at = null;
                $sv->petugas_nama = null;
                $sv->petugas_nipp = null;
                $sv->save();
            }
        });

        return redirect('perencanaan/jadwal')->with('success', 'Jadwal dibersihkan.');
    }

    /**
     * Helper: bentuk datetime dari (scheduled_at) atau (tanggal + jam).
     * Mengembalikan ['value' => Carbon|null, 'incomplete' => bool]
     * - value null & incomplete=false  => user tidak mengubah jadwal
     * - value null & incomplete=true   => hanya salah satu (tanggal/jam) diisi -> error
     */
    private function buildDateTime(?string $scheduledAt, ?string $tanggal, ?string $jam): array
    {
        // Opsi 1: input datetime-local langsung
        if (!empty($scheduledAt)) {
            try {
                return ['value' => Carbon::parse($scheduledAt), 'incomplete' => false];
            } catch (\Throwable $e) {
                return ['value' => null, 'incomplete' => true];
            }
        }

        // Opsi 2: gabung tanggal + jam (keduanya harus ada)
        $hasTanggal = !empty($tanggal);
        $hasJam     = !empty($jam);

        if ($hasTanggal && $hasJam) {
            try {
                return ['value' => Carbon::parse($tanggal.' '.$jam), 'incomplete' => false];
            } catch (\Throwable $e) {
                return ['value' => null, 'incomplete' => true];
            }
        }

        // Tidak ada perubahan (dua-duanya kosong)
        if (!$hasTanggal && !$hasJam) {
            return ['value' => null, 'incomplete' => false];
        }

        // Hanya salah satu yang diisi
        return ['value' => null, 'incomplete' => true];
    }
}

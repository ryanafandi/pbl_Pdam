<?php

namespace App\Http\Controllers\Perencanaan;

use App\Http\Controllers\Controller;
use App\Models\Spko;
use App\Models\Survei;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SurveiController extends Controller
{
    /** List SPKO yang siap diinput hasil survei */
    public function index()
    {
        $spko = Spko::with(['pengajuan', 'survei'])
            ->forPerencanaanQueue()
            ->orderByDesc('id')
            ->paginate(15);

        return view('perencanaan.survei.index', compact('spko'));
    }

    /** (Opsional) Form survei baru — biasanya via edit per SPKO */
    public function create(Request $request)
    {
        $spkoId = (int) $request->query('spko');
        $row = $spkoId ? Spko::with(['pengajuan', 'survei'])->findOrFail($spkoId) : null;

        if ($row && !$row->survei) {
            $row->setRelation('survei', new Survei(['spko_id' => $row->id]));
        }

        // Reuse view edit agar tidak duplikasi
        if ($row) return view('perencanaan.survei.edit', compact('row'));
        return redirect()->route('per.survei.index')->with('error', 'Pilih SPKO terlebih dulu.');
    }

    /** Simpan survei baru (opsional) */
    public function store(Request $request)
    {
        $validated = $this->validatePayload($request, true);

        $row = Spko::with('survei')->findOrFail($validated['spko_id']);
        $survei = $row->survei ?: new Survei(['spko_id' => $row->id]);
        $this->fillAndUpload($request, $survei, $validated);

        return redirect()->route('per.survei.index')->with('success', 'Hasil survei dibuat.');
    }

    /** Detail hasil survei per SPKO */
    public function show($spko)
    {
        $row = Spko::with(['pengajuan', 'survei'])->findOrFail($spko);

        if (!$row->survei) {
            // Siapkan instance kosong agar view aman ditampilkan
            $row->setRelation('survei', new Survei(['spko_id' => $row->id]));
        }

        return view('perencanaan.survei.show', compact('row'));
    }

    /** Form input/edit hasil survei per SPKO */
    public function edit($spko)
    {
        $row = Spko::with(['pengajuan', 'survei'])->findOrFail($spko);

        if (!$row->survei) {
            $row->setRelation('survei', new Survei(['spko_id' => $row->id]));
        }

        return view('perencanaan.survei.edit', compact('row'));
    }

    /** Simpan (upsert) hasil survei */
    public function update(Request $request, $spko)
    {
        $row = Spko::with('survei')->findOrFail($spko);
        $validated = $this->validatePayload($request);

        $survei = $row->survei ?: new Survei(['spko_id' => $row->id]);
        $this->fillAndUpload($request, $survei, $validated);

        return redirect()->route('per.survei.index')->with('success', 'Hasil survei disimpan.');
    }

    /** Hapus record survei (opsional) */
    public function destroy($spko)
    {
        $row = Spko::with('survei')->findOrFail($spko);

        DB::transaction(function () use ($row) {
            if ($row->survei) {
                // hapus file foto jika ada
                if ($row->survei->lokasi_foto && file_exists(public_path('public/app/survei/' . $row->survei->lokasi_foto))) {
                    @unlink(public_path('public/app/survei/' . $row->survei->lokasi_foto));
                }
                $row->survei->delete();
            }
        });

        return redirect()->route('per.survei.index')->with('success', 'Data survei dihapus.');
    }

    // ======================
    // Helpers
    // ======================

    private function validatePayload(Request $request, bool $withSpkoId = false): array
    {
        $rules = [
            // Jadwal & petugas
            'scheduled_at'        => 'nullable|date',
            'petugas_nama'        => 'nullable|string|max:120',
            'petugas_nipp'        => 'nullable|string|max:60',

            // Lokasi & foto
            'latitude'            => 'nullable|numeric|between:-90,90',
            'longitude'           => 'nullable|numeric|between:-180,180',
            'lokasi_foto'         => 'nullable|image|mimes:jpg,jpeg,png|max:3072',

            // Data teknis
            'jenis_pipa_dinas'    => 'nullable|string|max:50',
            'panjang_pipa_dinas'  => 'nullable|numeric',
            'jenis_pipa_persil'   => 'nullable|string|max:50',
            'panjang_pipa_persil' => 'nullable|numeric',
            'jenis_sambungan'     => 'nullable|string|max:100',
            'jenis_meter_air'     => 'nullable|string|max:50',
            'jenis_tanah'         => 'nullable|string|max:100',
            'kondisi_jalan'       => 'nullable|string|max:100',
            'kedalaman_galian'    => 'nullable|numeric',
            'kendala_lapangan'    => 'nullable|string',
            'catatan_teknis'      => 'nullable|string',
            'terobos' => 'nullable|in:0,1',
            // Validasi
            'disetujui_oleh'      => 'nullable|string|max:120',
            'disetujui_at'        => 'nullable|date',
            'tandai_selesai'      => 'nullable|boolean',
        ];

        if ($withSpkoId) {
            $rules = ['spko_id' => 'required|integer|exists:spko,id'] + $rules;
        }

        return $request->validate($rules);
    }

    private function fillAndUpload(Request $request, Survei $survei, array $validated): void
    {
        $survei->fill($validated);

        // Upload foto
        if ($request->hasFile('lokasi_foto')) {
            $dir = public_path('public/app/survei');
            if (!is_dir($dir)) @mkdir($dir, 0775, true);

            $ext = $request->file('lokasi_foto')->getClientOriginalExtension();
            $filename = 'survei_' . $survei->spko_id . '_' . time() . '.' . $ext;
            $request->file('lokasi_foto')->move($dir, $filename);

            // hapus foto lama jika ada
            if ($survei->getOriginal('lokasi_foto') && file_exists($dir . '/' . $survei->getOriginal('lokasi_foto'))) {
                @unlink($dir . '/' . $survei->getOriginal('lokasi_foto'));
            }

            $survei->lokasi_foto = $filename;
        }

        if ($request->boolean('tandai_selesai')) {
            $survei->done_at = now();
        }

        // Normalisasi nilai terobos (null / 0 / 1)
        if ($request->has('terobos')) {
            $survei->terobos = $request->input('terobos') === '1' ? 1 : 0;
        } else {
            $survei->terobos = null;
        }

        $survei->save();
    }
}

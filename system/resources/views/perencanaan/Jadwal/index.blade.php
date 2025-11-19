<x-perencanaan>
  {{-- ====== Styling ringan khusus halaman ini ====== --}}
  <style>
    /* jarak & wrap tombol yang konsisten */
    .btn-stack { display:flex; flex-wrap:wrap; align-items:center; gap:.25rem; }
    .btn-stack > * { margin: .18rem .25rem .18rem 0; }

    /* badge jadwal */
    .chip { display:inline-flex; align-items:center; border-radius:999px; padding:.15rem .6rem; font-size:.75rem; font-weight:600; }
    .chip--ok { background:#e8f7ef; color:#137a4d; border:1px solid #bfe8d3; }
    .chip--muted { background:#f1f3f5; color:#6b7280; border:1px solid #e5e7eb; }

    /* varian tombol lembut (compatible Bootstrap 4) */
    .btn-soft-primary { background:#eef4ff; color:#0d6efd; border:1px solid #d9e6ff; }
    .btn-soft-success { background:#eaf7ef; color:#137a4d; border:1px solid #cfe8d7; }
    .btn-soft-danger  { background:#ffefef; color:#c62828; border:1px solid #ffd6d6; }
    .btn-soft-light   { background:#f8fafc; color:#374151; border:1px solid #edf2f7; }

    /* baris difokuskan (opsional) */
    .row-focus { box-shadow: inset 4px 0 0 #0d6efd; background:#f0f7ff; }

    /* singkatkan label di layar kecil (ikon saja) */
    .btn-label { display:none; }
    @media (min-width: 576px) { .btn-label { display:inline; } }

    .table td, .table th { vertical-align: middle; }
    .table thead th { font-weight:600; letter-spacing:.2px; }
  </style>

  {{-- ====== Header ====== --}}
  <div class="content-header d-flex align-items-center justify-content-between mb-3">
    <div>
      <h1 class="m-0 text-primary fw-bold">
        <i class="fas fa-calendar-alt"></i> Penjadwalan Survei
      </h1>
      <small class="text-muted">Atur jadwal survei untuk SPKO yang telah dikirim dari Admin</small>
    </div>

    <div class="btn-stack">
      <a href="{{ url('perencanaan/spko') }}" class="btn btn-soft-light btn-sm" title="Antrian SPKO">
        <i class="fas fa-inbox"></i> <span class="btn-label">Antrian SPKO</span>
      </a>
      <a href="{{ url('perencanaan/survei') }}" class="btn btn-soft-light btn-sm" title="Input Hasil Survei">
        <i class="fas fa-map-marked-alt"></i> <span class="btn-label">Input Hasil</span>
      </a>
    </div>
  </div>

  @php
    // ====== Normalisasi data dari controller ======
    // Bisa dikirim sebagai $data (baru) atau $spko (lama)
    $list = $data ?? ($spko ?? null);
    if (!$list) { $list = collect(); } // fallback koleksi kosong

    // Baris yang sedang difokuskan (opsional)
    $selected = $selected ?? null;

    // Helper jadwal (ambil dari kolom spko terlebih dahulu, fallback ke relasi survei)
    $getScheduled = function($row) {
        if (!empty($row->survey_scheduled_at)) {
            return optional($row->survey_scheduled_at)->format('d/m/Y H:i');
        }
        if (optional($row->survei)->scheduled_at) {
            return optional($row->survei->scheduled_at)->format('d/m/Y H:i');
        }
        return null;
    };

    // Helper petugas (nama + nipp) – prioritas kolom di spko, fallback survei
    $getPetugas = function($row) {
        $nama = $row->disurvey_oleh_nama ?? optional($row->survei)->petugas_nama;
        $nipp = $row->disurvey_oleh_nipp ?? optional($row->survei)->petugas_nipp;
        return [$nama, $nipp];
    };
  @endphp

  {{-- ====== Alerts ====== --}}
  @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
  @if(session('error'))   <div class="alert alert-danger">{{ session('error') }}</div>   @endif

  @if(!empty($selected))
    <div class="alert alert-info">
      Fokus pada SPKO: <strong class="text-monospace">{{ $selected->nomor_spko }}</strong> —
      {{ $selected->pengajuan->pemohon_nama ?? $selected->pemilik_nama }} ({{ $selected->alamat }})
    </div>
  @endif

  {{-- ====== Tabel utama ====== --}}
  <section class="content">
    <div class="card shadow-sm">
      <div class="card-body table-responsive p-0">
        <table class="table table-hover mb-0">
          <thead class="bg-light">
            <tr>
              <th style="width:60px;">#</th>
              <th>No. SPKO</th>
              <th>Pemohon</th>
              <th>Alamat</th>
              <th>Jadwal</th>
              <th>Petugas</th>
              <th style="width:260px;">Aksi</th>
            </tr>
          </thead>
          <tbody>
          @forelse($list as $i => $row)
            @php
              $jadwal = $getScheduled($row);
              [$petugasNama, $petugasNipp] = $getPetugas($row);
              $isFocus = !empty($selected) && $selected->id === $row->id;
            @endphp
            <tr class="{{ $isFocus ? 'row-focus' : '' }}">
              <td>
                {{ method_exists($list, 'firstItem') ? ($list->firstItem() + $i) : ($i + 1) }}
              </td>
              <td class="text-monospace">{{ $row->nomor_spko }}</td>
              <td>{{ $row->pengajuan->pemohon_nama ?? $row->pemilik_nama }}</td>
              <td>{{ $row->alamat }}</td>
              <td>
                @if($jadwal)
                  <span class="chip chip--ok">
                    <i class="fas fa-circle" style="font-size:.5rem;margin-right:.4rem;"></i> {{ $jadwal }}
                  </span>
                @else
                  <span class="chip chip--muted">
                    <i class="fas fa-minus" style="font-size:.7rem;margin-right:.35rem;"></i> Belum
                  </span>
                @endif
              </td>
              <td>
                @if($petugasNama)
                  {{ $petugasNama }}
                  @if($petugasNipp)
                    <small class="text-muted">({{ $petugasNipp }})</small>
                  @endif
                @else
                  <span class="text-muted">-</span>
                @endif
              </td>
              <td>
                <div class="btn-stack">
                  @if($jadwal)
                    <a href="{{ url('perencanaan/jadwal/'.$row->id.'/edit') }}"
                       class="btn btn-soft-primary btn-sm" title="Ubah jadwal">
                      <i class="fas fa-edit"></i> <span class="btn-label">Ubah</span>
                    </a>
                    <form action="{{ url('perencanaan/jadwal/'.$row->id) }}" method="POST"
                          onsubmit="return confirm('Hapus jadwal survei untuk {{ $row->nomor_spko }} ?')">
                      @csrf @method('DELETE')
                      <button class="btn btn-soft-danger btn-sm" title="Hapus jadwal">
                        <i class="fas fa-trash"></i> <span class="btn-label">Hapus</span>
                      </button>
                    </form>
                  @else
                    <a href="{{ url('perencanaan/jadwal/create?spko='.$row->id) }}"
                       class="btn btn-soft-success btn-sm" title="Buat jadwal">
                      <i class="fas fa-calendar-plus"></i> <span class="btn-label">Buat Jadwal</span>
                    </a>
                  @endif

                  <a href="{{ url('perencanaan/spko/'.$row->id) }}"
                     class="btn btn-soft-light btn-sm" title="Detail SPKO">
                    <i class="fas fa-eye"></i> <span class="btn-label">Detail</span>
                  </a>
                </div>
              </td>
            </tr>
          @empty
            <tr><td colspan="7" class="text-center text-muted py-4">Belum ada SPKO untuk dijadwalkan.</td></tr>
          @endforelse
          </tbody>
        </table>
      </div>

      @if(method_exists($list, 'hasPages') && $list->hasPages())
        <div class="card-footer pb-0">{{ $list->links() }}</div>
      @endif
    </div>
  </section>
</x-perencanaan>

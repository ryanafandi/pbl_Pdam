<x-perencanaan>
  {{-- ====== Mini style khusus tabel & tombol ====== --}}
  <style>
    .table thead th { position: sticky; top: 0; z-index: 1; background: #f8fafc; }
    .chip {
      display:inline-flex; align-items:center; gap:.35rem;
      padding:.25rem .5rem; border-radius:999px; font-size:.75rem; line-height:1;
      background:#eef2ff; color:#3730a3; border:1px solid #e0e7ff;
    }
    .chip-muted { background:#f3f4f6; color:#6b7280; border-color:#e5e7eb; }
    .actions { display:flex; flex-wrap:wrap; gap:.35rem; justify-content:flex-end; }
    @media (max-width: 768px) {
      .table td:nth-child(3), .table th:nth-child(3) { display:none; }   /* sembunyikan kolom Pemohon di mobile */
      .table td:nth-child(4), .table th:nth-child(4) { display:none; }   /* sembunyikan kolom Alamat di mobile  */
      .actions { justify-content:flex-start; }
    }
  </style>

  <div class="content-header d-flex align-items-center justify-content-between mb-2">
    <div>
      <h1 class="m-0 text-primary fw-bold">
        <i class="fas fa-map-marked-alt"></i> Input Hasil Survei
      </h1>
      <small class="text-muted">SPKO berstatus <em>Sent to Planning</em></small>
    </div>
  </div>

  @php
    // Normalisasi: controller bisa kirim $data atau $spko
    $list = $data ?? ($spko ?? null);
    if (!$list) { $list = collect(); }

    // Helper jadwal (ambil dari spko.survey_scheduled_at -> fallback survei.scheduled_at)
    $getScheduled = function ($row) {
        if (!empty($row->survey_scheduled_at)) {
            return optional($row->survey_scheduled_at)->format('d/m/Y H:i');
        }
        if (optional($row->survei)->scheduled_at) {
            return optional($row->survei->scheduled_at)->format('d/m/Y H:i');
        }
        return null;
    };

    // Helper petugas (ambil dari spko.* -> fallback survei.*)
    $getPetugas = function ($row) {
        $nama = $row->disurvey_oleh_nama ?? optional($row->survei)->petugas_nama;
        $nipp = $row->disurvey_oleh_nipp ?? optional($row->survei)->petugas_nipp;
        return [$nama, $nipp];
    };
  @endphp

  <section class="content">
    @if (session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if (session('error'))   <div class="alert alert-danger">{{ session('error') }}</div>   @endif

    <div class="card shadow-sm">
      {{-- Toolbar kecil --}}
      <div class="card-header bg-white d-flex flex-wrap align-items-center gap-2">
        <div class="mr-auto d-flex align-items-center text-muted small">
          <i class="fas fa-info-circle mr-2"></i>
          <span>Gunakan tombol <strong>Input / Edit</strong> untuk mengisi hasil survei per SPKO.</span>
        </div>
        <div class="d-flex align-items-center">
          <span class="chip chip-muted">
            <i class="far fa-list-alt"></i>
            Total: <strong class="ml-1">{{ method_exists($list,'total') ? $list->total() : $list->count() }}</strong>
          </span>
        </div>
      </div>

      <div class="card-body table-responsive p-0">
        <table class="table table-hover mb-0">
          <thead>
            <tr>
              <th style="width:60px;">#</th>
              <th>No. SPKO</th>
              <th>Pemohon</th>
              <th>Alamat</th>
              <th>Jadwal</th>
              <th>Petugas</th>
              <th style="width:280px;" class="text-right">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($list as $i => $row)
              @php
                $jadwal = $getScheduled($row);
                [$petugasNama, $petugasNipp] = $getPetugas($row);
              @endphp
              <tr class="{{ $jadwal ? '' : 'table-light' }}">
                <td>
                  {{ method_exists($list, 'firstItem') ? ($list->firstItem() + $i) : ($i + 1) }}
                </td>

                <td class="text-monospace align-middle">
                  <div class="d-flex flex-column">
                    <span class="font-weight-bold">{{ $row->nomor_spko }}</span>
                    <span class="chip mt-1">
                      <i class="far fa-clock"></i>
                      {{ $jadwal ? 'Terjadwal' : 'Belum Dijadwalkan' }}
                    </span>
                  </div>
                </td>

                <td class="align-middle">
                  {{ $row->pengajuan->pemohon_nama ?? $row->pemilik_nama }}
                </td>

                <td class="align-middle text-truncate" style="max-width: 260px;">
                  {{ $row->alamat }}
                </td>

                <td class="align-middle">
                  @if($jadwal)
                    <span class="badge badge-light border">
                      <i class="far fa-calendar-alt"></i> {{ $jadwal }}
                    </span>
                  @else
                    <span class="text-muted">-</span>
                  @endif
                </td>

                <td class="align-middle">
                  @if($petugasNama)
                    <div class="d-flex flex-column">
                      <span>{{ $petugasNama }}</span>
                      @if($petugasNipp)
                        <small class="text-muted">NIPP: {{ $petugasNipp }}</small>
                      @endif
                    </div>
                  @else
                    <span class="text-muted">-</span>
                  @endif
                </td>

                <td class="align-middle">
                  <div class="actions">
                    <a href="{{ url('perencanaan/survei/'.$row->id.'/edit') }}"
                       class="btn btn-sm btn-primary" title="Input / Edit hasil survei">
                      <i class="fas fa-edit"></i> <span class="d-none d-md-inline">Input / Edit</span>
                    </a>

                    <a href="{{ url('perencanaan/survei/'.$row->id) }}"
                       class="btn btn-sm btn-light" title="Detail survei / SPKO">
                      <i class="fas fa-eye"></i> <span class="d-none d-md-inline">Detail</span>
                    </a>

                    @if ($row->survei)
                      <form action="{{ url('perencanaan/survei/'.$row->id) }}"
                            method="POST" class="d-inline"
                            onsubmit="return confirm('Hapus data survei untuk SPKO {{ $row->nomor_spko }}? Tindakan ini tidak bisa dibatalkan.')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger" title="Hapus data survei">
                          <i class="fas fa-trash"></i> <span class="d-none d-md-inline">Hapus</span>
                        </button>
                      </form>
                    @endif
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="text-center text-muted py-4">
                  Belum ada data.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      @if (method_exists($list, 'hasPages') && $list->hasPages())
        <div class="card-footer d-flex justify-content-between align-items-center">
          <small class="text-muted">
            Menampilkan {{ $list->firstItem() }}–{{ $list->lastItem() }}
            dari {{ $list->total() }} data
          </small>
          <div>{{ $list->links() }}</div>
        </div>
      @endif
    </div>
  </section>
</x-perencanaan>

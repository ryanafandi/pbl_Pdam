{{-- resources/views/admin/spk/index.blade.php --}}
<x-admin-dashboard>
  <div class="content-header">
    <div class="container-fluid">
      <div class="d-flex align-items-center justify-content-between mb-2">
        <div>
          <h1 class="m-0 text-primary fw-bold">
            <i class="fas fa-clipboard-list"></i> Daftar SPK
          </h1>
          <small class="text-muted">
            Surat Perintah Kerja untuk pemasangan sambungan baru.
          </small>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">

      {{-- Flash message --}}
      @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
          <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      @endif
      @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
          <i class="fas fa-exclamation-triangle mr-1"></i> {{ session('error') }}
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      @endif

      {{-- Filter / Pencarian --}}
      <div class="card shadow-sm mb-2">
        <div class="card-body py-2">
          <form action="{{ url('admin/spk') }}" method="GET" class="form-inline flex-wrap">

            {{-- Cari teks --}}
            <div class="input-group mr-2 mb-2">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-search"></i></span>
              </div>
              <input type="text"
                     name="q"
                     value="{{ $q }}"
                     class="form-control"
                     placeholder="Cari nomor SPK / nama / alamat / RAB">
            </div>

            {{-- Filter status --}}
            <div class="input-group mr-2 mb-2">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-filter"></i></span>
              </div>
              <select name="status" class="form-control">
                <option value="">— Semua Status —</option>
                @foreach($statuses as $key => $label)
                  <option value="{{ $key }}" {{ $status === $key ? 'selected' : '' }}>
                    {{ $label }}
                  </option>
                @endforeach
              </select>
            </div>

            <button class="btn btn-primary mb-2 mr-2">
              <i class="fas fa-sync-alt mr-1"></i> Terapkan
            </button>

            @if(request()->hasAny(['q','status']))
              <a href="{{ url('admin/spk') }}" class="btn btn-outline-secondary mb-2">
                Reset
              </a>
            @endif
          </form>
        </div>
      </div>

      {{-- TABEL --}}
      <div class="card shadow-sm">
        <div class="card-body table-responsive p-0">
          <table class="table table-hover mb-0">
            <thead class="thead-light">
              <tr>
                <th style="width:70px;">#</th>
                <th>No. SPK</th>
                <th>Pelanggan</th>
                <th>Pekerjaan</th>
                <th>Terkait</th>
                <th>Status</th>
                <th class="text-right" style="width:130px;">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($rows as $i => $row)
                @php
                  $rab        = $row->rab;
                  $spko       = $rab->spko ?? null;
                  $pengajuan  = $spko->pengajuan ?? null;
                  $statusText = $row->status_label;
                  $badgeClass = match($row->status) {
                    'draft'          => 'badge-secondary',
                    'kirim_direktur' => 'badge-info',
                    'disetujui'      => 'badge-success',
                    'ditolak'        => 'badge-danger',
                    'selesai'        => 'badge-dark',
                    default          => 'badge-light',
                  };
                @endphp
                <tr>
                  <td class="text-muted">
                    {{ $rows->firstItem() + $i }}
                  </td>

                  <td class="text-monospace">
                    <strong>{{ $row->nomor_spk ?? '-' }}</strong>
                    <div class="small text-muted">
                      dibuat: {{ optional($row->dibuat_at)->format('d/m/Y H:i') ?? '-' }}
                    </div>
                  </td>

                  <td>
                    {{ $row->nama_pelanggan ?? ($pengajuan->pemohon_nama ?? '-') }}
                    <div class="small text-muted">
                      {{ $row->alamat ?? ($pengajuan->alamat_pemasangan ?? '-') }}
                    </div>
                  </td>

                  <td>
                    {{ $row->pekerjaan ?? '-' }}
                    @if($row->lokasi)
                      <div class="small text-muted">
                        Lokasi: {{ $row->lokasi }}
                      </div>
                    @endif
                  </td>

                  <td class="small">
                    @if($rab)
                      <div>No. RAB: <span class="text-monospace">{{ $rab->nomor_rab }}</span></div>
                    @endif
                    @if($spko)
                      <div>No. SPKO: <span class="text-monospace">{{ $spko->nomor_spko }}</span></div>
                    @endif
                    @if($pengajuan)
                      <div>No. Daftar: <span class="text-monospace">{{ $pengajuan->no_pendaftaran }}</span></div>
                    @endif
                  </td>

                  <td>
                    <span class="badge {{ $badgeClass }}">
                      {{ $statusText }}
                    </span>
                    @if($row->status === 'disetujui' && $row->disetujui_at)
                      <div class="small text-muted">
                        disetujui: {{ $row->disetujui_at->format('d/m/Y H:i') }}
                      </div>
                    @endif
                  </td>

                  <td class="text-right">
                    <a href="{{ url('admin/spk/'.$row->id) }}"
                       class="btn btn-sm btn-outline-primary">
                      <i class="fas fa-eye"></i> Detail
                    </a>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="7" class="text-center text-muted py-4">
                    <i class="far fa-folder-open fa-2x mb-2"></i><br>
                    Belum ada SPK yang tercatat.
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        @if($rows->hasPages())
          <div class="card-footer d-flex justify-content-between align-items-center">
            <div class="small text-muted">
              Menampilkan {{ $rows->firstItem() }}–{{ $rows->lastItem() }} dari {{ $rows->total() }} data
            </div>
            <div>
              {{ $rows->links() }}
            </div>
          </div>
        @endif
      </div>
    </div>
  </section>
</x-admin-dashboard>

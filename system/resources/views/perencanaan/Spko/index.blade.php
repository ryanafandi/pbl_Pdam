<x-perencanaan>
  @php
    $fmt = fn($dt) => $dt ? \Illuminate\Support\Carbon::parse($dt)->format('d/m/Y H:i') : null;

    $getScheduled = function($row) use ($fmt) {
      if (!empty($row->survey_scheduled_at)) return $fmt($row->survey_scheduled_at);
      if (optional($row->survei)->scheduled_at) return $fmt($row->survei->scheduled_at);
      return null;
    };

    $getPetugas = function($row) {
      $nama = $row->disurvey_oleh_nama ?? optional($row->survei)->petugas_nama;
      $nipp = $row->disurvey_oleh_nipp ?? optional($row->survei)->petugas_nipp;
      return [$nama, $nipp];
    };
  @endphp

  <div class="content-header d-flex align-items-center justify-content-between mb-2">
    <div>
      <h1 class="m-0 text-primary fw-bold">
        <i class="fas fa-clipboard-list"></i> Semua SPKO (Tim Perencanaan)
      </h1>
      <small class="text-muted">Pencarian, filter status, dan aksi cepat.</small>
    </div>
    <div class="btn-group">
      <a href="{{ url('perencanaan/jadwal') }}" class="btn btn-light">
        <i class="fas fa-calendar-alt"></i> Penjadwalan
      </a>
      <a href="{{ url('perencanaan/survei') }}" class="btn btn-light">
        <i class="fas fa-map-marked-alt"></i> Input Hasil Survei
      </a>
    </div>
  </div>

  @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
  @if(session('error'))   <div class="alert alert-danger">{{ session('error') }}</div>   @endif

  <div class="card shadow-sm mb-3">
    <div class="card-body">
      <form method="GET" action="{{ url('perencanaan/spko') }}" class="row g-2 align-items-end">
        <div class="col-md-5">
          <label class="small text-muted mb-1">Cari</label>
          <div class="input-group">
            <span class="input-group-text"><i class="fas fa-search"></i></span>
            <input type="text" name="q" value="{{ $q ?? '' }}" class="form-control"
                   placeholder="Nomor SPKO / Pemohon / Alamat">
          </div>
        </div>
        <div class="col-md-3">
          <label class="small text-muted mb-1">Status</label>
          <select name="status" class="form-control">
            <option value="">— Semua —</option>
            @foreach($statuses as $st)
              <option value="{{ $st }}" {{ ($status ?? '')===$st ? 'selected' : '' }}>{{ $st }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-4 d-flex gap-2">
          <button class="btn btn-primary mt-3 mt-md-0"><i class="fas fa-filter"></i> Terapkan</button>
          @if(($q ?? null) || ($status ?? null))
            <a href="{{ url('perencanaan/spko') }}" class="btn btn-outline-secondary mt-3 mt-md-0">
              <i class="fas fa-times"></i> Reset
            </a>
          @endif
        </div>
      </form>
    </div>
  </div>

  <div class="card shadow-sm">
    <div class="card-body table-responsive p-0">
      <table class="table table-hover mb-0">
        <thead class="bg-light">
          <tr>
            <th style="width:60px;">#</th>
            <th>No. SPKO</th>
            <th>Pemohon</th>
            <th>Alamat</th>
            <th>Status</th>
            <th>Jadwal</th>
            <th>Petugas</th>
            <th style="width:280px;">Aksi</th>
          </tr>
        </thead>
        <tbody>
        @forelse($spko as $i => $row)
          @php
            $jadwal = $getScheduled($row);
            [$petugasNama, $petugasNipp] = $getPetugas($row);
            $badge = $row->status_badge_class ?? 'badge-light';
          @endphp
          <tr>
            <td>{{ $spko->firstItem() + $i }}</td>
            <td class="text-monospace">{{ $row->nomor_spko }}</td>
            <td>{{ $row->pengajuan->pemohon_nama ?? $row->pemilik_nama ?? '-' }}</td>
            <td>{{ $row->alamat ?? '-' }}</td>
            <td><span class="badge {{ $badge }}">{{ $row->status_label }}</span></td>
            <td>
              @if($jadwal)
                <span class="badge bg-success-subtle border text-dark">{{ $jadwal }}</span>
              @else
                <span class="text-muted">Belum dijadwalkan</span>
              @endif
            </td>
            <td>
              @if($petugasNama)
                {{ $petugasNama }} @if($petugasNipp)<small class="text-muted">({{ $petugasNipp }})</small>@endif
              @else
                <span class="text-muted">-</span>
              @endif
            </td>
            <td>
              <div class="btn-group btn-group-sm" role="group">
                <a href="{{ url('perencanaan/spko/'.$row->id) }}" class="btn btn-light">
                  <i class="fas fa-eye"></i> Detail
                </a>
                <a href="{{ url('perencanaan/spko/'.$row->id.'/edit-jadwal') }}" class="btn btn-primary">
                  <i class="fas fa-calendar-edit"></i> Jadwal
                </a>
                <a href="{{ url('perencanaan/survei/'.$row->id.'/edit') }}" class="btn btn-info">
                  <i class="fas fa-map-marked-alt"></i> Survei
                </a>
              </div>
            </td>
          </tr>
        @empty
          <tr><td colspan="8" class="text-center text-muted py-4">Tidak ada data.</td></tr>
        @endforelse
        </tbody>
      </table>
    </div>
    @if($spko->hasPages())
      <div class="card-footer pb-0">{{ $spko->links() }}</div>
    @endif
  </div>
</x-perencanaan>

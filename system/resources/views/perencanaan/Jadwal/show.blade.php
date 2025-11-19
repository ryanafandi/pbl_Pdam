<x-perencanaan>
  <div class="content-header d-flex align-items-center justify-content-between mb-2">
    <div>
      <h1 class="m-0">Detail Jadwal Survei</h1>
      <small class="text-muted">SPKO: <span class="text-monospace">{{ $row->nomor_spko }}</span></small>
    </div>
    <a href="{{ url('perencanaan/jadwal') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
  </div>

  <section class="content">
    <div class="card">
      <div class="card-body">
        <dl class="row">
          <dt class="col-sm-3">Waktu Survei</dt>
          <dd class="col-sm-9">
            {{ $row->survey_scheduled_at ? $row->survey_scheduled_at->format('d/m/Y H:i') : 'Belum dijadwalkan' }}
          </dd>

          <dt class="col-sm-3">Petugas</dt>
          <dd class="col-sm-9">
            {{ $row->disurvey_oleh_nama ?: '-' }}
            @if($row->disurvey_oleh_nipp)
              <small class="text-muted">({{ $row->disurvey_oleh_nipp }})</small>
            @endif
          </dd>

          <dt class="col-sm-3">Catatan</dt>
          <dd class="col-sm-9">{{ $row->catatan ?: '-' }}</dd>
        </dl>

        <a href="{{ url('perencanaan/jadwal/'.$row->id.'/edit') }}" class="btn btn-primary">
          <i class="fas fa-edit"></i> Ubah Jadwal
        </a>
      </div>
    </div>
  </section>
</x-perencanaan>

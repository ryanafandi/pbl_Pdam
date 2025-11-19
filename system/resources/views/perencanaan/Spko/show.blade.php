{{-- resources/views/perencanaan/spko/show.blade.php --}}
<x-perencanaan>
  @php
    $p   = optional($row->pengajuan);   // relasi pengajuan
    $sv  = optional($row->survei);      // relasi survei (kalau mau ditampilkan ringkas)
    $fmt = fn($dt) => $dt ? \Illuminate\Support\Carbon::parse($dt)->format('d/m/Y H:i') : '-';

    // Badge status SPKO
    $statusClass = [
      'DRAFT'             => 'badge-secondary',
      'SENT_TO_PLANNING'  => 'badge-info',
      'SENT_TO_DIRECTOR'  => 'badge-primary',
      'APPROVED'          => 'badge-success',
      'REJECTED'          => 'badge-danger',
      'DONE'              => 'badge-dark',
    ][$row->status] ?? 'badge-light';
  @endphp

  <div class="content-header d-flex align-items-center justify-content-between mb-2">
    <div>
      <h1 class="m-0 text-primary fw-bold">
        <i class="fas fa-file-alt"></i> Detail SPKO
      </h1>
      <small class="text-muted">
        Nomor: <span class="text-monospace">{{ $row->nomor_spko }}</span>
      </small>
    </div>

    <div class="d-flex gap-2">
      {{-- Opsional: tombol Edit SPKO untuk tim perencanaan jika diizinkan --}}
      {{-- <a href="{{ url('perencanaan/spko/'.$row->id.'/edit') }}" class="btn btn-primary">
        <i class="fas fa-edit"></i> Edit SPKO
      </a> --}}

      <a href="{{ url('perencanaan/spko') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
      </a>
    </div>
  </div>

  {{-- Ringkas Identitas & Status --}}
  <div class="row">
    <div class="col-md-6">
      <div class="card shadow-sm mb-3">
        <div class="card-header bg-white">
          <strong><i class="fas fa-id-card"></i> Identitas Dokumen</strong>
        </div>
        <div class="card-body">
          <dl class="row mb-0">
            <dt class="col-sm-4">Nomor</dt>
            <dd class="col-sm-8 text-monospace">{{ $row->nomor_spko }}</dd>

            <dt class="col-sm-4">Tanggal</dt>
            <dd class="col-sm-8">{{ $row->tanggal_spko?->format('d/m/Y') ?? '-' }}</dd>

            <dt class="col-sm-4">Tujuan</dt>
            <dd class="col-sm-8">{{ $row->tujuan ?? '-' }}</dd>

            <dt class="col-sm-4">Status</dt>
            <dd class="col-sm-8">
              <span class="badge {{ $statusClass }}">{{ $row->status }}</span>
            </dd>
          </dl>
        </div>
      </div>
    </div>

    <div class="col-md-6">
      <div class="card shadow-sm mb-3">
        <div class="card-header bg-white">
          <strong><i class="fas fa-users-cog"></i> Pejabat / Petugas</strong>
        </div>
        <div class="card-body">
          <dl class="row mb-0">
            <dt class="col-sm-4">Kepada</dt>
            <dd class="col-sm-8">{{ $row->kepada_jabatan ?? 'Tim Perencanaan' }}</dd>

            <dt class="col-sm-4">Disurvey oleh</dt>
            <dd class="col-sm-8">
              {{ $row->disurvey_oleh_nama ?? '-' }}
              @if($row->disurvey_oleh_nipp)
                <small class="text-muted">({{ $row->disurvey_oleh_nipp }})</small>
              @endif
            </dd>

            <dt class="col-sm-4">Kabag Teknik</dt>
            <dd class="col-sm-8">
              {{ $row->kabag_teknik_nama ?? '-' }}
              @if($row->kabag_teknik_nipp)
                <small class="text-muted">({{ $row->kabag_teknik_nipp }})</small>
              @endif
            </dd>

            <dt class="col-sm-4">Direktur</dt>
            <dd class="col-sm-8">
              {{ $row->direktur_nama ?? '-' }}
              @if($row->direktur_nipp)
                <small class="text-muted">({{ $row->direktur_nipp }})</small>
              @endif
            </dd>
          </dl>
        </div>
      </div>
    </div>
  </div>

  {{-- DATA PEMOHON (dari relasi pengajuan) --}}
  <div class="card shadow-sm mb-3">
    <div class="card-header bg-white">
      <strong><i class="fas fa-user"></i> Data Pemohon</strong>
      @if($p->no_pendaftaran)
        <span class="badge badge-light border ml-2">No. Pendaftaran: <span class="text-monospace">{{ $p->no_pendaftaran }}</span></span>
      @endif
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-6">
          <dl class="row mb-0">
            <dt class="col-sm-5">Nama Pemohon</dt>
            <dd class="col-sm-7">{{ $p->pemohon_nama ?? '-' }}</dd>

            <dt class="col-sm-5">No. Telepon</dt>
            <dd class="col-sm-7">{{ $p->nomor_telepon ?? '-' }}</dd>

            <dt class="col-sm-5">Email</dt>
            <dd class="col-sm-7">{{ $p->email ?? '-' }}</dd>

            <dt class="col-sm-5">Pekerjaan</dt>
            <dd class="col-sm-7">{{ $p->pekerjaan ?? '-' }}</dd>
          </dl>
        </div>
        <div class="col-md-6">
          <dl class="row mb-0">
            <dt class="col-sm-5">Alamat Pemasangan</dt>
            <dd class="col-sm-7">{{ $p->alamat_pemasangan ?? ($row->alamat ?? '-') }}</dd>

            <dt class="col-sm-5">Peruntukan</dt>
            <dd class="col-sm-7">{{ $p->peruntukan ?? '-' }}</dd>

            <dt class="col-sm-5">Jumlah Kran</dt>
            <dd class="col-sm-7">{{ $p->jumlah_kran ?? '-' }}</dd>

            <dt class="col-sm-5">Pelanggan Terdekat</dt>
            <dd class="col-sm-7">{{ $p->pelanggan_terdekat ?? '-' }}</dd>
          </dl>
        </div>
      </div>

      {{-- Baris tambahan opsional sesuai kolom yang Anda punya --}}
      <div class="row mt-3">
        <div class="col-md-6">
          <dl class="row mb-0">
            <dt class="col-sm-5">Penghuni Tetap</dt>
            <dd class="col-sm-7">{{ $p->penghuni_tetap ?? '-' }}</dd>

            <dt class="col-sm-5">Penghuni Tidak Tetap</dt>
            <dd class="col-sm-7">{{ $p->penghuni_tidak_tetap ?? '-' }}</dd>
          </dl>
        </div>
        <div class="col-md-6">
          <dl class="row mb-0">
            <dt class="col-sm-5">Langganan ke Orang Lain</dt>
            <dd class="col-sm-7">{{ $p->langganan_orang_lain ?? ($p->langganan_ke_orang_lain ?? '-') }}</dd>
          </dl>
        </div>
      </div>
    </div>
  </div>

  {{-- OPSIONAL: Ringkasan Jadwal dari SPKO/Survei --}}
  <div class="card shadow-sm mb-3">
    <div class="card-header bg-white">
      <strong><i class="fas fa-calendar-alt"></i> Ringkasan Jadwal Survei</strong>
    </div>
    <div class="card-body">
      <dl class="row mb-0">
        <dt class="col-sm-3">Jadwal</dt>
        <dd class="col-sm-9">
          {{ $fmt($row->survey_scheduled_at ?? $sv->scheduled_at) }}
        </dd>

        <dt class="col-sm-3">Petugas</dt>
        <dd class="col-sm-9">
          {{ $row->disurvey_oleh_nama ?? $sv->petugas_nama ?? '-' }}
          @php $nipp = $row->disurvey_oleh_nipp ?? $sv->petugas_nipp; @endphp
          @if($nipp) <small class="text-muted">({{ $nipp }})</small> @endif
        </dd>
      </dl>
    </div>
  </div>

  {{-- OPSIONAL: Laporan/ Catatan dari SPKO
  <div class="card shadow-sm">
    <div class="card-header bg-white">
      <strong><i class="fas fa-clipboard-list"></i> Laporan & Catatan</strong>
    </div>
    <div class="card-body">
      <div class="mb-3">
        <div class="text-muted small">Laporan Hasil Opname</div>
        <div class="p-2 rounded border bg-light">{{ $row->laporan_ringkas ?? '-' }}</div>
      </div>
      <div>
        <div class="text-muted small">Catatan</div>
        <div class="p-2 rounded border bg-light">{{ $row->catatan ?? '-' }}</div>
      </div>
    </div>
  </div> --}}
</x-perencanaan>

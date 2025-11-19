<x-admin-dashboard>
  <div class="content-header">
    <div class="container-fluid d-flex justify-content-between align-items-center mb-2">
      <div>
        <h1 class="m-0 text-primary fw-bold">
          <i class="fas fa-file-alt"></i> Detail SPKO
        </h1>
        <div class="small text-muted">
          Nomor: <span class="text-monospace">{{ $row->nomor_spko }}</span>
        </div>
      </div>
      <div>
        <a href="{{ url('admin/spko') }}" class="btn btn-light">
          <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <a href="{{ url('admin/spko/'.$row->id.'/edit') }}" class="btn btn-info">
          <i class="fas fa-edit"></i> Edit
        </a>
      </div>
    </div>
  </div>

  @php
    // Helpers
    $fmt = fn($v, $f='d/m/Y H:i') => $v ? \Illuminate\Support\Carbon::parse($v)->format($f) : '-';
    $dash = fn($v) => ($v === null || $v === '') ? '-' : $v;
    $person = function ($nama, $nipp) {
      if (!$nama && !$nipp) return '-';
      return trim(($nama ?: '-') . ($nipp ? ' ('.$nipp.')' : ''));
    };

    // Relasi
    $p  = optional($row->pengajuan);
    $sv = optional($row->survei);

    // Badge status (fallback kalau model belum siapkan class)
    $statusClassMap = [
      'DRAFT'             => 'badge-secondary',
      'SENT_TO_PLANNING'  => 'badge-info',
      'SENT_TO_DIRECTOR'  => 'badge-primary',
      'APPROVED'          => 'badge-success',
      'REJECTED'          => 'badge-danger',
      'DONE'              => 'badge-dark',
    ];
    $statusBadge = $row->status_badge_class ?? ($statusClassMap[$row->status] ?? 'badge-light');

    // Jadwal & petugas (ambil dari SPKO, fallback Survei)
    $scheduled = $row->survey_scheduled_at ?? $sv->scheduled_at;
    $petugasNama = $row->disurvey_oleh_nama ?? $sv->petugas_nama;
    $petugasNipp = $row->disurvey_oleh_nipp ?? $sv->petugas_nipp;
  @endphp

  <section class="content">
    <div class="container-fluid">

      {{-- Ringkasan cepat: Status + Jadwal --}}
      <div class="row">
        <div class="col-md-4">
          <div class="card shadow-sm mb-3">
            <div class="card-body">
              <div class="text-muted small mb-1">Status Dokumen</div>
              <span class="badge {{ $statusBadge }}">{{ $row->status }}</span>
            </div>
          </div>
        </div>
        <div class="col-md-8">
          <div class="card shadow-sm mb-3">
            <div class="card-body">
              <div class="row">
                <div class="col-sm-6 mb-2">
                  <div class="text-muted small mb-1">Jadwal Survei</div>
                  <div class="font-weight-bold">{{ $fmt($scheduled) }}</div>
                </div>
                <div class="col-sm-6 mb-2">
                  <div class="text-muted small mb-1">Petugas</div>
                  <div class="font-weight-bold">
                    {{ $person($petugasNama, $petugasNipp) }}
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      {{-- IDENTITAS & PEJABAT --}}
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

                <dt class="col-sm-4">No. Pendaftaran</dt>
                <dd class="col-sm-8 text-monospace">{{ $p->no_pendaftaran ?? '-' }}</dd>

                <dt class="col-sm-4">Tanggal</dt>
                <dd class="col-sm-8">{{ $row->tanggal_spko?->format('d/m/Y') ?? '-' }}</dd>

                <dt class="col-sm-4">Tujuan</dt>
                <dd class="col-sm-8">{{ $dash($row->tujuan ?? 'PEMASANGAN SAMBUNGAN BARU') }}</dd>

                <dt class="col-sm-4">Status</dt>
                <dd class="col-sm-8"><span class="badge {{ $statusBadge }}">{{ $row->status }}</span></dd>
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
                <dt class="col-sm-5">Kepada</dt>
                <dd class="col-sm-7">{{ $dash($row->kepada_jabatan ?? 'Tim Perencanaan') }}</dd>

                <dt class="col-sm-5">Disurvey oleh</dt>
                <dd class="col-sm-7">{{ $person($row->disurvey_oleh_nama, $row->disurvey_oleh_nipp) }}</dd>

                <dt class="col-sm-5">Kabag Teknik</dt>
                <dd class="col-sm-7">{{ $person($row->kabag_teknik_nama, $row->kabag_teknik_nipp) }}</dd>

                <dt class="col-sm-5">Direktur</dt>
                <dd class="col-sm-7">{{ $person($row->direktur_nama, $row->direktur_nipp) }}</dd>
              </dl>
            </div>
          </div>
        </div>
      </div>

      {{-- DATA PEMOHON (RELASI PENGAJUAN) --}}
      <div class="card shadow-sm mb-3">
        <div class="card-header bg-white d-flex align-items-center">
          <strong><i class="fas fa-user"></i> Data Pemohon</strong>
          @if($p->no_pendaftaran)
            <span class="badge badge-light border ml-2">
              No. Pendaftaran: <span class="text-monospace">{{ $p->no_pendaftaran }}</span>
            </span>
          @endif
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <dl class="row mb-0">
                <dt class="col-sm-5">Nama Pemohon</dt>
                <dd class="col-sm-7">{{ $p->pemohon_nama ?? ($row->pemilik_nama ?? '-') }}</dd>

                <dt class="col-sm-5">No. Telepon</dt>
                <dd class="col-sm-7">{{ $dash($p->nomor_telepon) }}</dd>

                <dt class="col-sm-5">Email</dt>
                <dd class="col-sm-7">{{ $dash($p->email) }}</dd>

                <dt class="col-sm-5">Pekerjaan</dt>
                <dd class="col-sm-7">{{ $dash($p->pekerjaan) }}</dd>
              </dl>
            </div>
            <div class="col-md-6">
              <dl class="row mb-0">
                <dt class="col-sm-5">Alamat Pemasangan</dt>
                <dd class="col-sm-7">{{ $p->alamat_pemasangan ?? ($row->alamat ?? '-') }}</dd>

                <dt class="col-sm-5">Peruntukan</dt>
                <dd class="col-sm-7">{{ $dash($p->peruntukan) }}</dd>

                <dt class="col-sm-5">Jumlah Kran</dt>
                <dd class="col-sm-7">{{ $dash($p->jumlah_kran) }}</dd>

                <dt class="col-sm-5">Pelanggan Terdekat</dt>
                <dd class="col-sm-7">{{ $dash($p->pelanggan_terdekat) }}</dd>
              </dl>
            </div>
          </div>

          <div class="row mt-3">
            <div class="col-md-6">
              <dl class="row mb-0">
                <dt class="col-sm-5">Penghuni Tetap</dt>
                <dd class="col-sm-7">{{ $dash($p->penghuni_tetap) }}</dd>

                <dt class="col-sm-5">Penghuni Tidak Tetap</dt>
                <dd class="col-sm-7">{{ $dash($p->penghuni_tidak_tetap) }}</dd>
              </dl>
            </div>
            <div class="col-md-6">
              <dl class="row mb-0">
                <dt class="col-sm-5">Langganan ke Orang Lain</dt>
                <dd class="col-sm-7">
                  {{ $dash($p->langganan_orang_lain ?? $p->langganan_ke_orang_lain) }}
                </dd>
              </dl>
            </div>
          </div>
        </div>
      </div>

      {{-- LAPORAN & CATATAN --}}
      <div class="card shadow-sm mb-3">
        <div class="card-header bg-white">
          <strong><i class="fas fa-clipboard-list"></i> Laporan Hasil Opname</strong>
        </div>
        <div class="card-body">
          <div class="p-3 border rounded bg-light">{!! nl2br(e($row->laporan_ringkas ?: '-')) !!}</div>

          @if ($row->catatan)
            <hr>
            <div class="text-muted small mb-1">Catatan</div>
            <div class="p-3 border rounded bg-light">{!! nl2br(e($row->catatan)) !!}</div>
          @endif
        </div>
      </div>

      {{-- RIWAYAT & META --}}
      @if ($row->approval_note || $row->sent_to_planning_at || $row->sent_to_director_at || $row->approved_at || $row->rejected_at)
        <div class="card shadow-sm mb-3">
          <div class="card-header bg-white">
            <strong><i class="fas fa-history"></i> Riwayat Pengiriman & Persetujuan</strong>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-6">
                <dl class="row mb-0">
                  <dt class="col-sm-6">Dikirim ke Perencanaan</dt>
                  <dd class="col-sm-6">{{ $fmt($row->sent_to_planning_at) }}</dd>

                  <dt class="col-sm-6">Dikirim ke Direktur</dt>
                  <dd class="col-sm-6">{{ $fmt($row->sent_to_director_at) }}</dd>

                  <dt class="col-sm-6">Disetujui</dt>
                  <dd class="col-sm-6">
                    {{ $fmt($row->approved_at) }}
                    @if($row->approved_by) <small class="text-muted">oleh #{{ $row->approved_by }}</small> @endif
                  </dd>

                  <dt class="col-sm-6">Ditolak</dt>
                  <dd class="col-sm-6">
                    {{ $fmt($row->rejected_at) }}
                    @if($row->rejected_by) <small class="text-muted">oleh #{{ $row->rejected_by }}</small> @endif
                  </dd>
                </dl>
              </div>
              <div class="col-md-6">
                @if ($row->approval_note)
                  <div class="text-muted small mb-1">Catatan Persetujuan</div>
                  <div class="p-3 border rounded">{{ $row->approval_note }}</div>
                @endif
              </div>
            </div>
          </div>
        </div>
      @endif

      <div class="card shadow-sm">
        <div class="card-header bg-white">
          <strong><i class="fas fa-info-circle"></i> Meta</strong>
        </div>
        <div class="card-body">
          <dl class="row mb-0">
            <dt class="col-sm-3">Dibuat</dt>
            <dd class="col-sm-9">{{ $fmt($row->created_at) }}</dd>
            <dt class="col-sm-3">Diubah</dt>
            <dd class="col-sm-9">{{ $fmt($row->updated_at) }}</dd>
          </dl>
        </div>
      </div>

    </div>
  </section>
</x-admin-dashboard>

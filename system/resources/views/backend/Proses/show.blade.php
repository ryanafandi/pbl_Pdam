{{-- resources/views/backend/Proses/show.blade.php --}}
<x-backend>
  <div class="content-header">
    <div class="container-fluid">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <div>
          <h1 class="m-0 text-primary fw-bold">
            <i class="fas fa-stream"></i> Detail Status Pengajuan
          </h1>
          <small class="text-muted">
            No. Pendaftaran:
            <span class="text-monospace">{{ $row->no_pendaftaran }}</span>
          </small>
        </div>
        <a href="{{ url('backend/Proses') }}" class="btn btn-secondary btn-sm">
          <i class="fas fa-arrow-left mr-1"></i> Kembali
        </a>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">

      <div class="row">
        {{-- Ringkasan pengajuan --}}
        <div class="col-md-5">
          <div class="card shadow-sm">
            <div class="card-header">
              <h3 class="card-title">Ringkasan Pengajuan</h3>
            </div>
            <div class="card-body">
              <dl class="row mb-0">
                <dt class="col-sm-4">No. Pendaftaran</dt>
                <dd class="col-sm-8 text-monospace">{{ $row->no_pendaftaran }}</dd>

                <dt class="col-sm-4">Nama Pemohon</dt>
                <dd class="col-sm-8">{{ $row->pemohon_nama }}</dd>

                <dt class="col-sm-4">Alamat</dt>
                <dd class="col-sm-8">{{ $row->alamat_pemasangan }}</dd>

                <dt class="col-sm-4">Status</dt>
                <dd class="col-sm-8">
                  <span class="badge {{ $row->status_badge_class }}">
                    {{ $row->status_label }}
                  </span>
                </dd>

                <dt class="col-sm-4">Progres</dt>
                <dd class="col-sm-8">
                  <span class="badge {{ $row->progress_badge_class }}">
                    {{ $row->progress_label }}
                  </span>
                  @if($row->progress_updated_at)
                    <div class="small text-muted mt-1">
                      Terakhir diperbarui: {{ $row->progress_updated_at->format('d/m/Y H:i') }}
                    </div>
                  @endif
                </dd>

                <dt class="col-sm-4">Diajukan</dt>
                <dd class="col-sm-8">
                  {{ $row->created_at?->format('d/m/Y H:i') }}
                </dd>
              </dl>
            </div>
          </div>
        </div>

        {{-- Timeline proses sederhana --}}
        <div class="col-md-7">
          <div class="card shadow-sm">
            <div class="card-header">
              <h3 class="card-title">Riwayat Proses</h3>
            </div>
            <div class="card-body">

              <ul class="list-unstyled">

                <li class="mb-3">
                  <span class="font-weight-bold">
                    <i class="fas fa-file-alt text-primary mr-1"></i> Pengajuan diterima
                  </span>
                  <div class="small text-muted ml-4">
                    {{ $row->created_at?->format('d/m/Y H:i') }}
                  </div>
                  <div class="ml-4">
                    Data pengajuan Anda telah tersimpan di sistem.
                  </div>
                </li>

                @if($row->sent_to_director_at)
                  <li class="mb-3">
                    <span class="font-weight-bold">
                      <i class="fas fa-paper-plane text-info mr-1"></i> Dikirim ke Direktur
                    </span>
                    <div class="small text-muted ml-4">
                      {{ $row->sent_to_director_at->format('d/m/Y H:i') }}
                    </div>
                    <div class="ml-4">
                      Pengajuan sedang menunggu persetujuan Direktur.
                    </div>
                    @if($row->catatan_admin)
                      <div class="ml-4 small text-muted mt-1">
                        Catatan Admin: {{ $row->catatan_admin }}
                      </div>
                    @endif
                  </li>
                @endif

                @if($row->approved_at)
                  <li class="mb-3">
                    <span class="font-weight-bold">
                      <i class="fas fa-check-circle text-success mr-1"></i> Pengajuan disetujui
                    </span>
                    <div class="small text-muted ml-4">
                      {{ $row->approved_at->format('d/m/Y H:i') }}
                    </div>
                    @if($row->catatan_direktur)
                      <div class="ml-4 small text-muted mt-1">
                        Catatan Direktur: {{ $row->catatan_direktur }}
                      </div>
                    @endif
                  </li>
                @endif

                @if($row->status === \App\Models\Pengajuan::ST_REJECTED && $row->rejected_at)
                  <li class="mb-3">
                    <span class="font-weight-bold">
                      <i class="fas fa-times-circle text-danger mr-1"></i> Pengajuan ditolak
                    </span>
                    <div class="small text-muted ml-4">
                      {{ $row->rejected_at->format('d/m/Y H:i') }}
                    </div>
                    @if($row->catatan_direktur)
                      <div class="ml-4 small text-muted mt-1">
                        Alasan: {{ $row->catatan_direktur }}
                      </div>
                    @endif
                  </li>
                @endif

                @if($row->progress_status)
                  <li class="mb-1">
                    <span class="font-weight-bold">
                      <i class="fas fa-tint text-primary mr-1"></i> Status pemasangan saat ini
                    </span>
                    <div class="small text-muted ml-4">
                      {{ $row->progress_label }}
                      @if($row->progress_updated_at)
                        ({{ $row->progress_updated_at->format('d/m/Y H:i') }})
                      @endif
                    </div>
                    <div class="ml-4">
                      Status akan diperbarui oleh petugas sesuai perkembangan di lapangan.
                    </div>
                  </li>
                @endif

              </ul>

            </div>
          </div>
        </div>
      </div>

    </div>
  </section>
</x-backend>

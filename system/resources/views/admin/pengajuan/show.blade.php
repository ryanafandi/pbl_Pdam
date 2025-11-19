{{-- resources/views/admin/pengajuan/show.blade.php --}}
<x-admin-dashboard>
  <div class="content-header">
    <div class="container-fluid">
      <div class="d-flex align-items-center justify-content-between mb-2">
        <div>
          <h1 class="m-0 text-primary fw-bold">
            <i class="fas fa-file-alt"></i> Detail Pengajuan
          </h1>
          <small class="text-muted">
            No. Pendaftaran: <span class="text-monospace">{{ $row->no_pendaftaran }}</span>
          </small>
        </div>

        <div>
          <a href="{{ url('admin/pengajuan') }}" class="btn btn-secondary btn-sm mr-1">
            <i class="fas fa-arrow-left"></i> Kembali
          </a>
          <a href="{{ url('admin/pengajuan/'.$row->id.'/edit') }}" class="btn btn-info btn-sm mr-1">
            <i class="fas fa-edit"></i> Edit
          </a>

          {{-- Kirim ke Direktur --}}
          @if($row->status === \App\Models\Pengajuan::ST_SUBMITTED)
            <form action="{{ url('admin/pengajuan/'.$row->id.'/send-to-director') }}"
                  method="POST"
                  class="d-inline">
              @csrf
              <button type="submit"
                      class="btn btn-success btn-sm mr-1"
                      onclick="return confirm('Kirim pengajuan ini ke Direktur?');">
                <i class="fas fa-paper-plane"></i> Kirim ke Direktur
              </button>
            </form>

            {{-- Tolak --}}
            <form action="{{ url('admin/pengajuan/'.$row->id.'/reject') }}"
                  method="POST"
                  class="d-inline">
              @csrf
              <button type="submit"
                      class="btn btn-danger btn-sm mr-1"
                      onclick="return confirm('Yakin ingin MENOLAK pengajuan ini?');">
                <i class="fas fa-times"></i> Tolak
              </button>
            </form>
          @endif

          {{-- Hapus --}}
          <form action="{{ url('admin/pengajuan/'.$row->id) }}"
                method="POST"
                class="d-inline">
            @csrf
            @method('DELETE')
            <button type="submit"
                    class="btn btn-outline-danger btn-sm"
                    onclick="return confirm('Hapus pengajuan ini?');">
              <i class="fas fa-trash-alt"></i> Hapus
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">

      @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif
      @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
      @endif

      <div class="row">
        {{-- Identitas & Alamat --}}
        <div class="col-md-6">
          <div class="card shadow-sm">
            <div class="card-header">
              <h3 class="card-title">Data Pemohon</h3>
            </div>
            <div class="card-body">
              <dl class="row mb-0">
                <dt class="col-sm-4">No. Pendaftaran</dt>
                <dd class="col-sm-8 text-monospace">{{ $row->no_pendaftaran }}</dd>

                <dt class="col-sm-4">Nama Pemohon</dt>
                <dd class="col-sm-8">{{ $row->pemohon_nama }}</dd>

                <dt class="col-sm-4">No. Telepon</dt>
                <dd class="col-sm-8">{{ $row->nomor_telepon }}</dd>

                <dt class="col-sm-4">Pekerjaan</dt>
                <dd class="col-sm-8">{{ $row->pekerjaan }}</dd>

                <dt class="col-sm-4">Email</dt>
                <dd class="col-sm-8">{{ $row->email ?: '-' }}</dd>

                <dt class="col-sm-4">Alamat Pemasangan</dt>
                <dd class="col-sm-8">{{ $row->alamat_pemasangan }}</dd>

                <dt class="col-sm-4">Peruntukan</dt>
                <dd class="col-sm-8">{{ $row->peruntukan }}</dd>

                <dt class="col-sm-4">Jumlah Kran</dt>
                <dd class="col-sm-8">{{ $row->jumlah_kran }}</dd>

                <dt class="col-sm-4">Penghuni Tetap</dt>
                <dd class="col-sm-8">{{ $row->penghuni_tetap }}</dd>

                <dt class="col-sm-4">Penghuni Tidak Tetap</dt>
                <dd class="col-sm-8">{{ $row->penghuni_tidak_tetap }}</dd>
              </dl>
            </div>
          </div>
        </div>

        {{-- Status & Catatan --}}
        <div class="col-md-6">
          <div class="card shadow-sm mb-3">
            <div class="card-header">
              <h3 class="card-title">Status Pengajuan</h3>
            </div>
            <div class="card-body">
              <p>
                <strong>Status Persetujuan:</strong>
                <span class="badge {{ $row->status_badge_class }}">
                  {{ $row->status_label }}
                </span>
              </p>
              <p>
                <strong>Progres Pemasangan:</strong>
                <span class="badge {{ $row->progress_badge_class }}">
                  {{ $row->progress_label }}
                </span>
              </p>
              <p><strong>Dibuat pada:</strong> {{ $row->created_at?->format('d/m/Y H:i') }}</p>
              <p><strong>Terakhir diubah:</strong> {{ $row->updated_at?->format('d/m/Y H:i') }}</p>
              @if($row->approved_at)
                <p><strong>Disetujui Direktur:</strong> {{ $row->approved_at->format('d/m/Y H:i') }}</p>
              @endif
              @if($row->rejected_at)
                <p><strong>Ditolak Direktur:</strong> {{ $row->rejected_at->format('d/m/Y H:i') }}</p>
              @endif>
            </div>
          </div>

          <div class="card shadow-sm">
            <div class="card-header">
              <h3 class="card-title">Catatan</h3>
            </div>
            <div class="card-body">
              <p><strong>Catatan Admin:</strong><br>
                {!! nl2br(e($row->catatan_admin ?? '-')) !!}
              </p>
              <hr>
              <p><strong>Catatan Direktur:</strong><br>
                {!! nl2br(e($row->catatan_direktur ?? '-')) !!}
              </p>
            </div>
          </div>
        </div>
      </div>

      {{-- Dokumen lampiran --}}
      <div class="row mt-3">
        <div class="col-md-3">
          <div class="card shadow-sm">
            <div class="card-header"><strong>KTP</strong></div>
            <div class="card-body text-center">
              @if($row->ktp_url)
                <a href="{{ asset($row->ktp_url) }}" target="_blank">
                  <img src="{{ asset($row->ktp_url) }}" class="img-fluid mb-2" alt="KTP">
                </a>
              @else
                <span class="text-muted">Tidak ada file</span>
              @endif
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card shadow-sm">
            <div class="card-header"><strong>KK</strong></div>
            <div class="card-body text-center">
              @if($row->kk_url)
                <a href="{{ asset($row->kk_url) }}" target="_blank">
                  <img src="{{ asset($row->kk_url) }}" class="img-fluid mb-2" alt="KK">
                </a>
              @else
                <span class="text-muted">Tidak ada file</span>
              @endif
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card shadow-sm">
            <div class="card-header"><strong>Foto Rumah</strong></div>
            <div class="card-body text-center">
              @if($row->foto_rumah_url)
                <a href="{{ asset($row->foto_rumah_url) }}" target="_blank">
                  <img src="{{ asset($row->foto_rumah_url) }}" class="img-fluid mb-2" alt="Foto Rumah">
                </a>
              @else
                <span class="text-muted">Tidak ada file</span>
              @endif
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card shadow-sm">
            <div class="card-header"><strong>Denah</strong></div>
            <div class="card-body text-center">
              @if($row->denah_url)
                <a href="{{ asset($row->denah_url) }}" target="_blank">
                  <img src="{{ asset($row->denah_url) }}" class="img-fluid mb-2" alt="Denah">
                </a>
              @else
                <span class="text-muted">Tidak ada file</span>
              @endif
            </div>
          </div>
        </div>
      </div>

    </div>
  </section>
</x-admin-dashboard>

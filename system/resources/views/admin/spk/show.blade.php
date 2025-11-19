{{-- resources/views/admin/spk/show.blade.php --}}
<x-admin-dashboard>
  <div class="content-header">
    <div class="container-fluid">
      <div class="d-flex align-items-center justify-content-between mb-2">
        <div>
          <h1 class="m-0 text-primary fw-bold">
            <i class="fas fa-file-contract"></i> Detail SPK
          </h1>
          <small class="text-muted">
            Surat Perintah Kerja untuk pemasangan sambungan baru.
          </small>
        </div>
        <div>
          <a href="{{ url('admin/spk') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
          </a>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">

      @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
          <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
          <button type="button" class="close" data-dismiss="alert">
            <span>&times;</span>
          </button>
        </div>
      @endif
      @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
          <i class="fas fa-exclamation-triangle mr-1"></i> {{ session('error') }}
          <button type="button" class="close" data-dismiss="alert">
            <span>&times;</span>
          </button>
        </div>
      @endif

      @php
        $rab        = $row->rab;
        $spko       = $rab->spko ?? null;
        $pengajuan  = $spko->pengajuan ?? null;
        $status     = $row->status;
        $statusText = $row->status_label;
        $badgeClass = match($status) {
          'draft'          => 'badge-secondary',
          'kirim_direktur' => 'badge-info',
          'disetujui'      => 'badge-success',
          'ditolak'        => 'badge-danger',
          'selesai'        => 'badge-dark',
          default          => 'badge-light',
        };
      @endphp

      <div class="row">
        {{-- IDENTITAS SPK --}}
        <div class="col-md-7 mb-3">
          <div class="card shadow-sm">
            <div class="card-body">
              <h5 class="mb-3">Identitas SPK</h5>

              <dl class="row small mb-0">
                <dt class="col-sm-4">No. SPK</dt>
                <dd class="col-sm-8 text-monospace">{{ $row->nomor_spk ?? '-' }}</dd>

                <dt class="col-sm-4">Pekerjaan</dt>
                <dd class="col-sm-8">{{ $row->pekerjaan ?? '-' }}</dd>

                <dt class="col-sm-4">Nama Pelanggan</dt>
                <dd class="col-sm-8">
                  {{ $row->nama_pelanggan ?? ($pengajuan->pemohon_nama ?? '-') }}
                </dd>

                <dt class="col-sm-4">Alamat</dt>
                <dd class="col-sm-8">
                  {{ $row->alamat ?? ($pengajuan->alamat_pemasangan ?? '-') }}
                </dd>

                <dt class="col-sm-4">Lokasi / Area</dt>
                <dd class="col-sm-8">
                  {{ $row->lokasi ?? '-' }}
                </dd>

                <dt class="col-sm-4">No. Pelanggan / Daftar</dt>
                <dd class="col-sm-8 text-monospace">
                  {{ $row->no_pelanggan ?? ($pengajuan->no_pendaftaran ?? '-') }}
                </dd>

                <dt class="col-sm-4">Status</dt>
                <dd class="col-sm-8">
                  <span class="badge {{ $badgeClass }}">{{ $statusText }}</span>
                </dd>

                <dt class="col-sm-4">Dibuat</dt>
                <dd class="col-sm-8">
                  {{ $row->dibuat_at ? $row->dibuat_at->format('d/m/Y H:i') : '-' }}
                </dd>

                <dt class="col-sm-4">Disetujui</dt>
                <dd class="col-sm-8">
                  {{ $row->disetujui_at ? $row->disetujui_at->format('d/m/Y H:i') : '-' }}
                </dd>

                <dt class="col-sm-4">Catatan</dt>
                <dd class="col-sm-8">
                  <pre class="mb-0 small" style="white-space: pre-wrap;">{{ $row->catatan ?? '-' }}</pre>
                </dd>
              </dl>
            </div>
          </div>
        </div>

        {{-- TERKAIT RAB / SPKO --}}
        <div class="col-md-5 mb-3">
          <div class="card shadow-sm">
            <div class="card-body">
              <h5 class="mb-3">Terkait RAB / SPKO</h5>

              <dl class="row small mb-0">
                <dt class="col-sm-4">No. RAB</dt>
                <dd class="col-sm-8 text-monospace">{{ $rab->nomor_rab ?? '-' }}</dd>

                <dt class="col-sm-4">Total RAB</dt>
                <dd class="col-sm-8">
                  Rp {{ number_format($rab->total ?? 0, 0, ',', '.') }}
                </dd>

                <dt class="col-sm-4">Status Billing</dt>
                <dd class="col-sm-8">
                  @php
                    $bStatus = $rab->billing_status;
                    $bLabel  = $rab->billing_status_label ?? ($bStatus ?? 'Draft Dokumen');
                    $bClass  = match($bStatus) {
                      'PAID' => 'badge-success',
                      'SENT' => 'badge-primary',
                      default => 'badge-secondary',
                    };
                  @endphp

                  <span class="badge {{ $bClass }}">{{ $bLabel }}</span>
                  @if($rab->billing_paid_at)
                    <div class="small text-muted">
                      Lunas: {{ $rab->billing_paid_at->format('d/m/Y H:i') }}
                    </div>
                  @endif
                </dd>

                <dt class="col-sm-4">No. SPKO</dt>
                <dd class="col-sm-8 text-monospace">{{ $spko->nomor_spko ?? '-' }}</dd>

                <dt class="col-sm-4">No. Pendaftaran</dt>
                <dd class="col-sm-8 text-monospace">{{ $pengajuan->no_pendaftaran ?? '-' }}</dd>
              </dl>
            </div>
          </div>

          {{-- AKSI STATUS --}}
          <div class="card shadow-sm">
            <div class="card-body">
              <h5 class="mb-3">Aksi</h5>

              {{-- Kirim ke Direktur (dari admin) --}}
              @if($status === 'draft')
                <form action="{{ url('admin/spk/'.$row->id.'/kirim') }}" method="POST" class="mb-2 d-inline-block">
                  @csrf
                  <button type="submit"
                          class="btn btn-outline-primary btn-sm"
                          onclick="return confirm('Kirim SPK ini ke Direktur untuk disetujui?');">
                    <i class="fas fa-paper-plane"></i> Kirim ke Direktur
                  </button>
                </form>
              @endif

              {{-- Approve / Reject (sementara di sini, nanti bisa dipindah ke modul Direktur) --}}
              @if($status === 'kirim_direktur')
                <form action="{{ url('admin/spk/'.$row->id.'/approve') }}" method="POST" class="mb-2 d-inline-block">
                  @csrf
                  <button type="submit"
                          class="btn btn-success btn-sm"
                          onclick="return confirm('Setujui SPK ini?');">
                    <i class="fas fa-check"></i> Setujui
                  </button>
                </form>

                {{-- Tolak pakai prompt sederhana --}}
                <button type="button"
                        class="btn btn-danger btn-sm mb-2"
                        onclick="tolakSpk{{ $row->id }}()">
                  <i class="fas fa-times"></i> Tolak
                </button>
                <form id="form-tolak-{{ $row->id }}"
                      action="{{ url('admin/spk/'.$row->id.'/reject') }}"
                      method="POST" class="d-none">
                  @csrf
                  <input type="hidden" name="catatan" id="catatan-tolak-{{ $row->id }}">
                </form>
                <script>
                  function tolakSpk{{ $row->id }}() {
                    const note = prompt('Catatan penolakan (opsional):');
                    if (note === null) return;
                    document.getElementById('catatan-tolak-{{ $row->id }}').value = note;
                    document.getElementById('form-tolak-{{ $row->id }}').submit();
                  }
                </script>
              @endif

              {{-- Tandai selesai (Trandis) --}}
              @if($status === 'disetujui')
                <form action="{{ url('admin/spk/'.$row->id.'/selesai') }}" method="POST" class="d-inline-block">
                  @csrf
                  <button type="submit"
                          class="btn btn-outline-success btn-sm"
                          onclick="return confirm('Tandai SPK ini sudah SELESAI pemasangan?');">
                    <i class="fas fa-check-double"></i> Tandai Selesai
                  </button>
                </form>
              @endif

              @if(in_array($status, ['ditolak','selesai']))
                <div class="alert alert-info mt-2 mb-0 py-2">
                  Status SPK: <strong>{{ $statusText }}</strong>.
                </div>
              @endif
            </div>
          </div>

        </div>
      </div>

    </div>
  </section>
</x-admin-dashboard>

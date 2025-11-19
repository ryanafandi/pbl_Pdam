{{-- resources/views/backend/dokumen/show.blade.php --}}
<x-backend>
  <div class="content-header">
    <div class="container-fluid">
      <div class="d-flex align-items-center justify-content-between mb-2">
        <div>
          <h1 class="m-0 text-primary fw-bold">
            <i class="fas fa-file-invoice-dollar"></i> Detail Dokumen Biaya
          </h1>
          <small class="text-muted">
            RNA dan Bukti Persetujuan Biaya Penyambungan.
          </small>
        </div>
        <div>
          <a href="{{ url('backend/dokumen') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
          </a>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">

      {{-- Flash --}}
      @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif
      @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
      @endif

      {{-- IDENTITAS --}}
      <div class="row mb-3">
        <div class="col-md-7">
          <div class="card shadow-sm">
            <div class="card-body">
              <h5 class="mb-3">Identitas RAB</h5>
              <dl class="row small mb-0">
                <dt class="col-sm-4">No. RAB</dt>
                <dd class="col-sm-8 text-monospace">{{ $row->nomor_rab ?? '-' }}</dd>

                <dt class="col-sm-4">No. SPKO</dt>
                <dd class="col-sm-8 text-monospace">{{ $row->spko->nomor_spko ?? '-' }}</dd>

                <dt class="col-sm-4">No. Pendaftaran</dt>
                <dd class="col-sm-8 text-monospace">{{ $row->spko->pengajuan->no_pendaftaran ?? '-' }}</dd>

                <dt class="col-sm-4">Nama Pelanggan</dt>
                <dd class="col-sm-8">
                  {{ $row->nama_pelanggan ?? ($row->spko->pengajuan->pemohon_nama ?? '-') }}
                </dd>

                <dt class="col-sm-4">Alamat</dt>
                <dd class="col-sm-8">
                  {{ $row->alamat ?? ($row->spko->pengajuan->alamat_pemasangan ?? '-') }}
                </dd>

                <dt class="col-sm-4">Total Biaya</dt>
                <dd class="col-sm-8 font-weight-bold">
                  Rp {{ number_format($row->total ?? 0, 0, ',', '.') }}
                </dd>
              </dl>
            </div>
          </div>
        </div>

        {{-- STATUS --}}
        <div class="col-md-5">
          <div class="card shadow-sm">
            <div class="card-body">
              <h5 class="mb-3">Status Tagihan</h5>
              @php
                $status = $row->billing_status;
                $label  = $row->billing_status_label ?? ($status ?? 'Draft');
                $cls    = $status === 'PAID'
                            ? 'badge-success'
                            : ($status === 'SENT' ? 'badge-primary' : 'badge-secondary');
              @endphp

              <p>
                Status:
                <span class="badge {{ $cls }}">{{ $label }}</span>
              </p>

              <dl class="row small mb-0">
                <dt class="col-sm-4">Dikirim</dt>
                <dd class="col-sm-8">
                  {{ $row->billing_sent_at ? $row->billing_sent_at->format('d/m/Y H:i') : '-' }}
                </dd>

                <dt class="col-sm-4">Dibayar</dt>
                <dd class="col-sm-8">
                  {{ $row->billing_paid_at ? $row->billing_paid_at->format('d/m/Y H:i') : '-' }}
                </dd>

                <dt class="col-sm-4">Jatuh Tempo</dt>
                <dd class="col-sm-8">
                  {{ $row->jatuh_tempo ? $row->jatuh_tempo->format('d/m/Y') : '-' }}
                </dd>
              </dl>
            </div>
          </div>
        </div>
      </div>

      {{-- RNA & PERSETUJUAN (READ ONLY) --}}
      <div class="row">
        <div class="col-md-6 mb-3">
          <div class="card shadow-sm">
            <div class="card-header bg-white">
              <strong>Rening Non Air (RNA)</strong>
            </div>
            <div class="card-body">
              <dl class="row small mb-0">
                <dt class="col-sm-4">No. RNA</dt>
                <dd class="col-sm-8 text-monospace">
                  {{ $row->rna_nomor ?? '-' }}
                </dd>

                <dt class="col-sm-4">Tanggal</dt>
                <dd class="col-sm-8">
                  {{ $row->rna_tanggal ? $row->rna_tanggal->format('d/m/Y') : '-' }}
                </dd>
              </dl>

              <div class="mt-3">
                <a href="{{ url('admin/dokumen_biaya/'.$row->id.'/rna') }}"
                   target="_blank"
                   class="btn btn-outline-secondary btn-sm {{ $row->rna_nomor ? '' : 'disabled' }}">
                  <i class="fas fa-print"></i> Cetak RNA
                </a>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-6 mb-3">
          <div class="card shadow-sm">
            <div class="card-header bg-white">
              <strong>Bukti Persetujuan Biaya Penyambungan</strong>
            </div>
            <div class="card-body">
              <dl class="row small mb-0">
                <dt class="col-sm-4">No. Persetujuan</dt>
                <dd class="col-sm-8 text-monospace">
                  {{ $row->persetujuan_nomor ?? '-' }}
                </dd>

                <dt class="col-sm-4">Tanggal</dt>
                <dd class="col-sm-8">
                  {{ $row->persetujuan_tanggal ? $row->persetujuan_tanggal->format('d/m/Y') : '-' }}
                </dd>
              </dl>

              <div class="mt-3">
                <a href="{{ url('admin/dokumen_biaya/'.$row->id.'/persetujuan') }}"
                   target="_blank"
                   class="btn btn-outline-secondary btn-sm {{ $row->persetujuan_nomor ? '' : 'disabled' }}">
                  <i class="fas fa-print"></i> Cetak Bukti Persetujuan
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>

      {{-- Catatan --}}
      @if($row->billing_note)
        <div class="card shadow-sm mb-3">
          <div class="card-header bg-white">
            <strong>Catatan</strong>
          </div>
          <div class="card-body">
            <pre class="mb-0" style="white-space: pre-wrap;">{{ $row->billing_note }}</pre>
          </div>
        </div>
      @endif

    </div>
  </section>
</x-backend>

{{-- resources/views/backend/dokumen/index.blade.php --}}
<x-backend>
  <div class="content-header">
    <div class="container-fluid">
      <div class="d-flex align-items-center justify-content-between mb-2">
        <div>
          <h1 class="m-0 text-primary fw-bold">
            <i class="fas fa-file-invoice-dollar"></i> Tagihan & Dokumen
          </h1>
          <small class="text-muted">
            Dokumen RNA dan Bukti Persetujuan Biaya yang sudah dikirim oleh admin.
          </small>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">

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

      {{-- Filter status sederhana --}}
      <div class="card shadow-sm mb-2">
        <div class="card-body py-2">
          @php $st = $status; @endphp
          <div class="btn-group btn-group-sm" role="group">
            <a href="{{ url('backend/dokumen') }}"
               class="btn {{ !$st ? 'btn-primary' : 'btn-outline-primary' }}">
              <i class="fas fa-list"></i> Semua
            </a>
            <a href="{{ url('backend/dokumen?status=SENT') }}"
               class="btn {{ $st === 'SENT' ? 'btn-primary' : 'btn-outline-primary' }}">
              <i class="fas fa-envelope-open-text"></i> Belum Dibayar
            </a>
            <a href="{{ url('backend/dokumen?status=PAID') }}"
               class="btn {{ $st === 'PAID' ? 'btn-primary' : 'btn-outline-primary' }}">
              <i class="fas fa-check-circle"></i> Sudah Dibayar
            </a>
          </div>
        </div>
      </div>

      <div class="card shadow-sm">
        <div class="card-body table-responsive p-0">
          <table class="table table-hover mb-0">
            <thead class="thead-light">
              <tr>
                <th style="width:60px;">#</th>
                <th>No. RAB</th>
                <th>Pelanggan</th>
                <th>Total</th>
                <th>RNA</th>
                <th>Persetujuan</th>
                <th>Status</th>
                <th style="width:110px;" class="text-right">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($rows as $i => $row)
                @php
                  $status = $row->billing_status;
                  $label  = $row->billing_status_label ?? ($status ?? 'Draft');
                  $cls    = $status === 'PAID'
                              ? 'badge-success'
                              : ($status === 'SENT' ? 'badge-primary' : 'badge-secondary');

                  $hasRna   = !empty($row->rna_nomor);
                  $hasPers  = !empty($row->persetujuan_nomor);
                @endphp
                <tr>
                  <td class="text-muted">{{ $rows->firstItem() + $i }}</td>

                  <td class="text-monospace">
                    {{ $row->nomor_rab ?? '-' }}
                    <div class="small text-muted">
                      SPKO: {{ $row->spko->nomor_spko ?? '-' }}<br>
                      PD: {{ $row->spko->pengajuan->no_pendaftaran ?? '-' }}
                    </div>
                  </td>

                  <td>
                    {{ $row->nama_pelanggan ?? ($row->spko->pengajuan->pemohon_nama ?? '-') }}
                    <div class="small text-muted">
                      {{ $row->alamat ?? ($row->spko->pengajuan->alamat_pemasangan ?? '-') }}
                    </div>
                  </td>

                  <td>
                    Rp {{ number_format($row->total ?? 0, 0, ',', '.') }}
                  </td>

                  {{-- RNA: null / tidak null --}}
                  <td>
                    @if($hasRna)
                      <span class="badge badge-success">Ada</span>
                      <div class="small text-monospace text-muted">
                        {{ $row->rna_nomor }}
                      </div>
                    @else
                      <span class="badge badge-secondary">Belum</span>
                    @endif
                  </td>

                  {{-- Persetujuan: null / tidak null --}}
                  <td>
                    @if($hasPers)
                      <span class="badge badge-success">Ada</span>
                      <div class="small text-monospace text-muted">
                        {{ $row->persetujuan_nomor }}
                      </div>
                    @else
                      <span class="badge badge-secondary">Belum</span>
                    @endif
                  </td>

                  <td>
                    <span class="badge {{ $cls }}">{{ $label }}</span>
                  </td>

                  <td class="text-right">
                    <a href="{{ url('backend/dokumen/'.$row->id) }}"
                       class="btn btn-sm btn-outline-primary">
                      <i class="fas fa-eye"></i> Detail
                    </a>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="8" class="text-center text-muted py-4">
                    <i class="far fa-folder-open fa-2x mb-2"></i><br>
                    Belum ada dokumen biaya yang dikirim oleh admin.
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        @if($rows->hasPages())
          <div class="card-footer d-flex justify-content-between align-items-center">
            <div class="small text-muted">
              Menampilkan {{ $rows->firstItem() }}–{{ $rows->lastItem() }} dari {{ $rows->total() }} dokumen
            </div>
            <div>
              {{ $rows->links() }}
            </div>
          </div>
        @endif
      </div>
    </div>
  </section>
</x-backend>

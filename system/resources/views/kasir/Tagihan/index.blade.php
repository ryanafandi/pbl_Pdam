{{-- resources/views/kasir/tagihan/index.blade.php --}}
<x-kasir>
  <div class="content-header">
    <div class="container-fluid">
      <div class="d-flex align-items-center justify-content-between mb-2">
        <div>
          <h1 class="m-0 text-primary fw-bold">
            <i class="fas fa-file-invoice-dollar"></i> Daftar Tagihan
          </h1>
          <small class="text-muted">
            Tagihan sambungan baru yang dikelola oleh kasir.
          </small>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">

      {{-- Flash messages --}}
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

      {{-- Filter status (tab sederhana) --}}
      <div class="card shadow-sm mb-2">
        <div class="card-body py-2">
          @php $st = $status; @endphp
          <div class="btn-group" role="group" aria-label="filter-status">
            <a href="{{ url('kasir/tagihan') }}"
               class="btn btn-sm {{ !$st ? 'btn-primary' : 'btn-outline-primary' }}">
              <i class="fas fa-list"></i> Tagihan Aktif (SENT)
            </a>
            <a href="{{ url('kasir/tagihan?status=SENT') }}"
               class="btn btn-sm {{ $st === 'SENT' ? 'btn-primary' : 'btn-outline-primary' }}">
              <i class="fas fa-file-invoice"></i> Belum Lunas
            </a>
            <a href="{{ url('kasir/tagihan?status=PAID') }}"
               class="btn btn-sm {{ $st === 'PAID' ? 'btn-primary' : 'btn-outline-primary' }}">
              <i class="fas fa-check-circle"></i> Lunas
            </a>
          </div>
        </div>
      </div>

      {{-- Tabel tagihan --}}
      <div class="card shadow-sm">
        <div class="card-body table-responsive p-0">
          <table class="table table-hover mb-0">
            <thead class="thead-light">
              <tr>
                <th style="width:70px;">#</th>
                <th>No. RAB</th>
                <th>No. RNA</th>
                <th>Pelanggan</th>
                <th>Total Tagihan</th>
                <th>Status</th>
                <th>Dikirim</th>
                <th>Dibayar</th>
                <th style="width:120px;" class="text-right">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($rows as $i => $row)
                @php
                  $status   = $row->billing_status;
                  $label    = $row->billing_status_label ?? ($status ?? 'Draft');
                  $cls      = $status === 'PAID'
                                ? 'badge-success'
                                : ($status === 'SENT' ? 'badge-primary' : 'badge-secondary');
                @endphp
                <tr>
                  <td class="text-muted">{{ $rows->firstItem() + $i }}</td>

                  <td class="text-monospace">
                    {{ $row->nomor_rab ?? '-' }}
                    <div class="small text-muted">
                      SPKO: {{ $row->spko->nomor_spko ?? '-' }}
                    </div>
                  </td>

                  <td class="text-monospace">
                    {{ $row->rna_nomor ?? '-' }}
                    <div class="small text-muted">
                      {{ optional($row->rna_tanggal)->format('d/m/Y') ?? '' }}
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

                  <td>
                    <span class="badge {{ $cls }}">{{ $label }}</span>
                  </td>

                  <td class="small">
                    {{ $row->billing_sent_at ? $row->billing_sent_at->format('d/m/Y H:i') : '-' }}
                  </td>

                  <td class="small">
                    {{ $row->billing_paid_at ? $row->billing_paid_at->format('d/m/Y H:i') : '-' }}
                  </td>

                  <td class="text-right">
                    <a href="{{ url('kasir/tagihan/'.$row->id) }}"
                       class="btn btn-sm btn-outline-primary">
                      <i class="fas fa-eye"></i> Detail
                    </a>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="9" class="text-center text-muted py-4">
                    <i class="far fa-folder-open fa-2x mb-2"></i><br>
                    Tidak ada tagihan untuk ditampilkan.
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        @if($rows->hasPages())
          <div class="card-footer d-flex justify-content-between align-items-center">
            <div class="small text-muted">
              Menampilkan {{ $rows->firstItem() }}–{{ $rows->lastItem() }} dari {{ $rows->total() }} tagihan
            </div>
            <div>
              {{ $rows->links() }}
            </div>
          </div>
        @endif
      </div>
    </div>
  </section>
</x-kasir>

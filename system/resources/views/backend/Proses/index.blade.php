{{-- resources/views/backend/Proses/index.blade.php --}}
<x-backend>
  <div class="content-header">
    <div class="container-fluid">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <div>
          <h1 class="m-0 text-primary fw-bold">
            <i class="fas fa-stream"></i> Status & Proses Pengajuan
          </h1>
          <small class="text-muted">
            Lihat riwayat pengajuan dan perkembangan proses pemasangan.
          </small>
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

      <div class="card shadow-sm">
        <div class="card-body">

          <div class="table-responsive">
            <table class="table table-hover">
              <thead class="thead-light">
                <tr>
                  <th style="width: 50px;">#</th>
                  <th>No. Pendaftaran</th>
                  <th>Nama Pemohon</th>
                  <th>Alamat</th>
                  <th>Status Persetujuan</th>
                  <th>Progres Pemasangan</th>
                  <th>Tanggal</th>
                  <th style="width: 120px;">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @forelse($rows as $i => $row)
                  <tr>
                    <td>{{ $rows->firstItem() + $i }}</td>
                    <td class="text-monospace">{{ $row->no_pendaftaran }}</td>
                    <td>{{ $row->pemohon_nama }}</td>
                    <td>{{ $row->alamat_pemasangan }}</td>
                    <td>
                      <span class="badge {{ $row->status_badge_class }}">
                        {{ $row->status_label }}
                      </span>
                    </td>
                    <td>
                      <span class="badge {{ $row->progress_badge_class }}">
                        {{ $row->progress_label }}
                      </span>
                    </td>
                    <td>{{ $row->created_at?->format('d/m/Y H:i') }}</td>
                    <td>
                      <a href="{{ url('backend/Proses', $row->id) }}"
                         class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-eye mr-1"></i> Detail
                      </a>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="8" class="text-center text-muted p-4">
                      Belum ada pengajuan yang tercatat.
                      <br>
                      <a href="{{ url('backend/Pengajuan') }}" class="btn btn-sm btn-primary mt-2">
                        <i class="fas fa-plus-circle mr-1"></i> Ajukan Pendaftaran
                      </a>
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>

          <div class="mt-2">
            {{ $rows->links() }}
          </div>
        </div>
      </div>
    </div>
  </section>
</x-backend>

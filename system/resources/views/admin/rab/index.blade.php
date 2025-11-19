<x-admin-dashboard>
  <div class="content-header d-flex justify-content-between align-items-center mb-2">
    <div>
      <h1 class="m-0 text-primary fw-bold">
        <i class="fas fa-inbox"></i> Inbox RAB Disetujui
      </h1>
      <small class="text-muted">Daftar RAB yang sudah disetujui Direktur dan siap diproses Admin.</small>
    </div>
  </div>

  <section class="content">
    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
      <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card shadow-sm">
      <div class="card-body table-responsive p-0">
        <table class="table table-hover mb-0">
          <thead class="bg-light">
            <tr>
              <th style="width:60px;">#</th>
              {{-- dulu: No. SPKO --}}
              <th>No. RAB</th>
              <th>Pemohon</th>
              <th>Total RAB</th>
              <th>Disetujui</th>
              <th style="width:140px;" class="text-right">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($rows as $i => $row)
              @php
                $spko      = $row->spko;
                $pengajuan = $spko->pengajuan ?? null;

                $nama = $pengajuan->pemohon_nama
                  ?? $spko->pemilik_nama
                  ?? '-';

                $approvedAt = $row->approved_at
                  ? \Illuminate\Support\Carbon::parse($row->approved_at)->format('d/m/Y H:i')
                  : '-';
              @endphp

              <tr>
                <td>{{ $rows->firstItem() + $i }}</td>

                {{-- ganti SPKO → RAB --}}
                <td class="text-monospace">
                  {{ $row->nomor_rab ?? '-' }}
                </td>

                <td>{{ $nama }}</td>
                <td>Rp {{ number_format($row->total, 0, ',', '.') }}</td>
                <td>{{ $approvedAt }}</td>
                <td class="text-right">
                  <a href="{{ url('admin/rab/'.$row->id) }}"
                     class="btn btn-xs btn-primary">
                    <i class="fas fa-eye"></i> Detail / Proses
                  </a>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="text-center text-muted py-4">
                  Belum ada RAB disetujui yang menunggu diproses admin.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      @if($rows->hasPages())
        <div class="card-footer pb-0">
          {{ $rows->links() }}
        </div>
      @endif
    </div>
  </section>
</x-admin-dashboard>

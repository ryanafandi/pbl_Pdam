<x-direktur>
  <div class="content-header d-flex align-items-center justify-content-between mb-2">
    <div>
      <h1 class="m-0 text-primary fw-bold">
        <i class="fas fa-inbox"></i> Inbox RAB
      </h1>
      <small class="text-muted">Daftar RAB yang masuk ke Direktur</small>
    </div>
  </div>

  <section class="content">
    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
      <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if(session('info'))
      <div class="alert alert-info">{{ session('info') }}</div>
    @endif

    <div class="card shadow-sm">
      <div class="card-body table-responsive p-0">
        <table class="table table-hover mb-0">
          <thead class="bg-light">
            <tr>
              <th style="width:60px;">#</th>
              <th>No. SPKO</th>
              <th>Pemohon</th>
              <th>Status</th>
              <th>Total RAB</th>
              <th>Dikirim</th>
              <th style="width:220px;" class="text-right">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($rab as $i => $r)
              @php
                $spko  = $r->spko;
                $peng  = $spko->pengajuan ?? null;
                $nama  = $peng->pemohon_nama ?? $spko->pemilik_nama ?? '-';

                $badgeMap = [
                  'draft'     => 'badge-secondary',
                  'dikirim'   => 'badge-info',
                  'disetujui' => 'badge-success',
                  'ditolak'   => 'badge-danger',
                ];

                $status = $r->status ?? 'draft';
                $badge  = $badgeMap[$status] ?? 'badge-light';

                $sentAt = $r->sent_to_director_at
                  ? \Illuminate\Support\Carbon::parse($r->sent_to_director_at)->format('d/m/Y H:i')
                  : '-';
              @endphp

              <tr>
                <td>{{ $rab->firstItem() + $i }}</td>
                <td class="text-monospace">{{ $spko->nomor_spko ?? '-' }}</td>
                <td>{{ $nama }}</td>
                <td>
                  <span class="badge {{ $badge }}">{{ strtoupper($status) }}</span>
                </td>
                <td>Rp {{ number_format($r->total, 0, ',', '.') }}</td>
                <td>{{ $sentAt }}</td>

                <td class="text-right">
                  {{-- DETAIL: pakai halaman detail RAB di perencanaan --}}
                  <a href="{{ url('perencanaan/rab/'.$spko->id) }}"
                     class="btn btn-xs btn-light">
                    <i class="fas fa-eye"></i> Detail
                  </a>

                  {{-- SETUJUI / TOLAK hanya kalau status = dikirim --}}
                  @if($status === 'dikirim')
                    <form action="{{ route('direktur.rab.approve', $spko->id) }}"
                          method="POST"
                          class="d-inline"
                          onsubmit="return confirm('Setujui RAB ini?')">
                      @csrf
                      <button class="btn btn-xs btn-success">
                        <i class="fas fa-check"></i> Setujui
                      </button>
                    </form>

                    <a href="{{ url('direktur/rab/'.$spko->id.'#form-reject') }}"
                       class="btn btn-xs btn-danger">
                      <i class="fas fa-times"></i> Tolak
                    </a>
                  @endif
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="text-center text-muted py-4">
                  Belum ada RAB yang masuk ke Direktur.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      @if($rab->hasPages())
        <div class="card-footer pb-0">
          {{ $rab->links() }}
        </div>
      @endif
    </div>
  </section>
</x-direktur>

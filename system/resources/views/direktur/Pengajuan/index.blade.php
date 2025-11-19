{{-- resources/views/direktur/pendaftaran/index.blade.php --}}
<x-direktur>
  <div class="card">
    <div class="card-header d-flex flex-wrap justify-content-between align-items-center">
      <h5 class="mb-2 mb-sm-0">Approval Pendaftaran</h5>

      <form method="get" class="d-flex">
        <input type="hidden" name="tab" value="{{ request('tab','waiting') }}">
        <input
          type="text"
          name="s"
          value="{{ request('s') }}"
          class="form-control form-control-sm mr-2"
          placeholder="Cari nama/alamat..."
        >
        <button class="btn btn-sm btn-primary">Cari</button>
      </form>
    </div>

    <div class="card-body p-0">
      {{-- Tab filter --}}
      <div class="p-2 pl-3">
        @php $tab = request('tab','waiting'); @endphp
        <a href="{{ url('direktur/approval/pendaftaran?tab=waiting') }}"
           class="btn btn-sm {{ $tab==='waiting' ? 'btn-primary' : 'btn-outline-primary' }}">
          Menunggu
        </a>
        <a href="{{ url('direktur/approval/pendaftaran?tab=approved') }}"
           class="btn btn-sm {{ $tab==='approved' ? 'btn-primary' : 'btn-outline-primary' }}">
          Disetujui
        </a>
        <a href="{{ url('direktur/approval/pendaftaran?tab=rejected') }}"
           class="btn btn-sm {{ $tab==='rejected' ? 'btn-primary' : 'btn-outline-primary' }}">
          Ditolak
        </a>
        <a href="{{ url('direktur/approval/pendaftaran?tab=all') }}"
           class="btn btn-sm {{ $tab==='all' ? 'btn-primary' : 'btn-outline-primary' }}">
          Semua
        </a>
      </div>

      <table class="table table-hover table-bordered mb-0">
        <thead class="thead-dark">
          <tr>
            <th width="5%">#</th>
            <th>No. Daftar</th>
            <th>Pemohon</th>
            <th>Alamat</th>
            <th>Status</th>
            <th>Tgl</th>
            <th class="text-right" width="28%">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($data as $row)
            <tr>
              <td>{{ $loop->iteration }}</td>
              <td class="text-monospace">{{ $row->no_pendaftaran }}</td>
              <td>{{ $row->pemohon_nama }}</td>
              <td>{{ $row->alamat_pemasangan }}</td>

              {{-- STATUS: pakai label Bahasa Indonesia --}}
              <td>
                <span class="badge {{ $row->status_badge_class }}">
                  {{ $row->status_label }}
                </span>
              </td>

              <td>{{ $row->created_at->format('d/m/Y H:i') }}</td>

              <td class="text-right">
                {{-- Detail --}}
                <a href="{{ url('direktur/approval/pendaftaran/'.$row->id) }}"
                   class="btn btn-sm btn-outline-secondary">
                  Detail
                </a>

                {{-- Setujui: kalau sudah dikirim ke direktur atau pernah ditolak --}}
                @if($row->status === \App\Models\Pengajuan::ST_SENT_TO_DIRECTOR
                   || $row->status === \App\Models\Pengajuan::ST_REJECTED)
                  <form action="{{ url('direktur/approval/pendaftaran/'.$row->id.'/approve') }}"
                        method="POST"
                        class="d-inline">
                    @csrf
                    <button class="btn btn-sm btn-success"
                            onclick="return confirm('Setujui pengajuan ini?')">
                      Setujui
                    </button>
                  </form>
                @endif

                {{-- Tolak: selain yang sudah APPROVED --}}
                @if($row->status !== \App\Models\Pengajuan::ST_APPROVED)
                  <button class="btn btn-sm btn-danger" onclick="tolak{{ $row->id }}()">
                    Tolak
                  </button>

                  <form id="form-tolak-{{ $row->id }}"
                        action="{{ url('direktur/approval/pendaftaran/'.$row->id.'/reject') }}"
                        method="POST"
                        class="d-none">
                    @csrf
                    <input type="hidden" name="catatan_direktur" id="alasan-{{ $row->id }}">
                  </form>

                  <script>
                    function tolak{{ $row->id }}() {
                      const alasan = prompt('Alasan penolakan (wajib):');
                      if (alasan) {
                        document.getElementById('alasan-{{ $row->id }}').value = alasan;
                        document.getElementById('form-tolak-{{ $row->id }}').submit();
                      }
                    }
                  </script>
                @endif
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="text-center text-muted">Tidak ada data.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="card-footer">
      {{ $data->links() }}
    </div>
  </div>
</x-direktur>

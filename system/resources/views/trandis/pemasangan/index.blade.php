<x-trandis>
    <div class="card card-success card-outline elevation-2">
        <div class="card-header">
            <h3 class="card-title font-weight-bold">
                <i class="fas fa-hard-hat mr-2"></i> Monitoring Pemasangan
            </h3>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap align-middle">
                <thead class="bg-light">
                    <tr>
                        <th class="text-center" style="width: 50px">Status</th>
                        <th>No SPK / Pelanggan</th>
                        <th>Jadwal Rencana</th>
                        <th>Progres Terakhir</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $item)
                    <tr>
                        <td class="text-center align-middle">
                            @if($item->status == 'selesai')
                                <div class="text-success"><i class="fas fa-check-circle fa-2x"></i></div>
                            @elseif($item->status_teknis == 'working')
                                <div class="spinner-grow text-danger spinner-grow-sm" role="status"></div>
                            @elseif($item->status_teknis == 'paused')
                                <div class="text-warning"><i class="fas fa-pause-circle fa-2x"></i></div>
                            @else
                                <div class="text-info"><i class="fas fa-clock fa-2x"></i></div>
                            @endif
                        </td>
                        <td class="align-middle">
                            <div class="font-weight-bold text-dark">{{ $item->nomor_spk }}</div>
                            <small class="text-muted">{{ $item->nama_pelanggan }}</small>
                        </td>
                        <td class="align-middle">
                            <i class="far fa-calendar-alt mr-1 text-secondary"></i> 
                            {{ date('d M Y H:i', strtotime($item->tgl_jadwal)) }}
                        </td>
                        <td class="align-middle">
                            @if($item->status_teknis == 'working')
                                <span class="badge badge-danger">SEDANG DIKERJAKAN</span>
                            @elseif($item->status_teknis == 'scheduled')
                                <span class="badge badge-info">TERJADWAL</span>
                            @elseif($item->status_teknis == 'paused')
                                <span class="badge badge-warning">DIJEDA</span>
                            @elseif($item->status_teknis == 'installed')
                                <span class="badge badge-success">SELESAI</span>
                            @endif
                        </td>
                        <td class="text-right align-middle">
                            @if($item->status != 'selesai')
                                <a href="{{ url('trandis/pemasangan', $item->id) }}" class="btn btn-sm btn-success elevation-1 font-weight-bold">
                                    <i class="fas fa-play-circle mr-1"></i> PROSES
                                </a>
                            @else
                                <a href="{{ url('trandis/pemasangan', $item->id) }}" class="btn btn-sm btn-default">
                                    <i class="fas fa-folder-open mr-1"></i> Arsip
                                </a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            Belum ada jadwal pemasangan aktif.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer clearfix">
            {{ $data->links() }}
        </div>
    </div>
</x-trandis>
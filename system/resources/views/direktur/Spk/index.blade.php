<x-direktur>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Approval Surat Perintah Kerja (SPK)</h3>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap">
                <thead>
                    <tr>
                        <th>Status</th>
                        <th>No SPK</th>
                        <th>Nama Pelanggan</th>
                        <th>Jenis Pekerjaan</th>
                        <th>Tanggal Masuk</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $item)
                    <tr>
                        <td>
                            @if($item->status == 'kirim_direktur')
                                <span class="badge badge-warning">Menunggu Approval</span>
                            @elseif($item->status == 'disetujui')
                                <span class="badge badge-success">Disetujui</span>
                            @elseif($item->status == 'ditolak')
                                <span class="badge badge-danger">Ditolak</span>
                            @elseif($item->status == 'selesai')
                                <span class="badge badge-info">Selesai Dikerjakan</span>
                            @endif
                        </td>
                        <td>{{ $item->nomor_spk }}</td>
                        <td>{{ $item->nama_pelanggan }}</td>
                        <td>{{ $item->pekerjaan }}</td>
                        <td>{{ $item->dibuat_at ? $item->dibuat_at->format('d M Y') : '-' }}</td>
                        <td class="text-right">
                            <a href="{{ url('direktur/spk', $item->id) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-search"></i> Review
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">Belum ada data SPK yang masuk.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer clearfix">
            {{ $data->links() }}
        </div>
    </div>
</x-direktur>
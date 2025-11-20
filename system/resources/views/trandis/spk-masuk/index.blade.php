<x-trandis>
    <div class="row mb-3">
        <div class="col-12">
            <div class="callout callout-info bg-white shadow-sm">
                <h5><i class="fas fa-info-circle text-info mr-2"></i> Menunggu Penjadwalan</h5>
                <p class="text-muted mb-0">Daftar SPK di bawah ini telah disetujui Direktur. Silakan tentukan tanggal pemasangan untuk memindahkannya ke tahap eksekusi.</p>
            </div>
        </div>
    </div>

    <div class="card card-primary card-outline elevation-2">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-inbox mr-1"></i> Daftar SPK Masuk
            </h3>
            <div class="card-tools">
                <div class="input-group input-group-sm" style="width: 200px;">
                    <input type="text" name="table_search" class="form-control float-right" placeholder="Cari No SPK / Nama...">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap align-middle">
                <thead class="bg-light">
                    <tr>
                        <th>No. SPK</th>
                        <th>Data Pelanggan</th>
                        <th>Alamat Pemasangan</th>
                        <th>Tgl. Approval</th>
                        <th class="text-right" style="width: 15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $item)
                    <tr>
                        <td class="align-middle">
                            <span class="text-primary font-weight-bold">{{ $item->nomor_spk }}</span>
                        </td>
                        <td class="align-middle">
                            <div class="user-block">
                                <img class="img-circle img-bordered-sm" src="{{ url('public/backend/dist/img/user1-128x128.jpg') }}" alt="User Image">
                                <span class="username">
                                    <a href="#">{{ $item->nama_pelanggan }}</a>
                                </span>
                                <span class="description">
                                    <i class="fas fa-phone-alt fa-xs mr-1"></i> {{ $item->rab->spko->pengajuan->nomor_telepon ?? '-' }}
                                </span>
                            </div>
                        </td>
                        <td class="align-middle">
                            {{ Str::limit($item->alamat, 40) }} <br>
                            <small class="text-muted"><i class="fas fa-map-marker-alt mr-1"></i> {{ $item->lokasi ?? 'Wilayah tidak diset' }}</small>
                        </td>
                        <td class="align-middle">
                            <span class="badge badge-light border">
                                <i class="far fa-calendar-check mr-1"></i> {{ date('d M Y', strtotime($item->disetujui_at)) }}
                            </span>
                        </td>
                        <td class="align-middle text-right">
                            <a href="{{ url('trandis/spk-masuk', $item->id) }}" class="btn btn-sm btn-outline-info" title="Lihat Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            <button class="btn btn-sm btn-primary elevation-1 ml-1" data-toggle="modal" data-target="#modalJadwal{{ $item->id }}">
                                <i class="fas fa-calendar-plus mr-1"></i> Jadwal
                            </button>
                        </td>
                    </tr>

                    <div class="modal fade" id="modalJadwal{{ $item->id }}">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header bg-primary text-white">
                                    <h5 class="modal-title"><i class="fas fa-clock mr-2"></i> Jadwalkan Pemasangan</h5>
                                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                                </div>
                                <form action="{{ url('trandis/spk-masuk/'.$item->id.'/jadwal') }}" method="POST">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="alert alert-light border">
                                            <strong>Pelanggan:</strong> {{ $item->nama_pelanggan }}
                                        </div>
                                        <div class="form-group">
                                            <label>Rencana Tanggal & Jam</label>
                                            <div class="input-group date" id="reservationdatetime" data-target-input="nearest">
                                                <input type="datetime-local" name="tgl_jadwal" class="form-control" required />
                                            </div>
                                            <small class="text-muted">Status akan berubah menjadi <b>Terjadwal</b> dan pindah ke menu Proses.</small>
                                        </div>
                                    </div>
                                    <div class="modal-footer justify-content-between">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Simpan Jadwal</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <div class="text-muted">
                                <i class="fas fa-clipboard-check fa-4x mb-3" style="color: #e0e0e0;"></i>
                                <h5>Tidak ada SPK Baru</h5>
                                <p>Semua SPK telah dijadwalkan atau belum ada data masuk.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer clearfix bg-white">
            {{ $data->links() }}
        </div>
    </div>
</x-trandis>
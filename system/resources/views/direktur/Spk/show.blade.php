<x-direktur>
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Detail SPK: <strong>{{ $row->nomor_spk }}</strong></h3>
                    <div class="card-tools">
                        <span class="badge badge-secondary">{{ $row->status_label }}</span>
                    </div>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-4">Nama Pelanggan</dt>
                        <dd class="col-sm-8">{{ $row->nama_pelanggan }}</dd>

                        <dt class="col-sm-4">Alamat</dt>
                        <dd class="col-sm-8">{{ $row->alamat }}</dd>

                        <dt class="col-sm-4">Pekerjaan</dt>
                        <dd class="col-sm-8">{{ $row->pekerjaan }}</dd>
                        
                        <dt class="col-sm-4">Lokasi / Kelurahan</dt>
                        <dd class="col-sm-8">{{ $row->lokasi ?? '-' }}</dd>

                        <dt class="col-sm-4">No. Langganan</dt>
                        <dd class="col-sm-8">{{ $row->no_pelanggan ?? '-' }}</dd>

                        <dt class="col-sm-4">Catatan Admin</dt>
                        <dd class="col-sm-8 text-muted">{{ $row->catatan ?? 'Tidak ada catatan' }}</dd>
                    </dl>

                    <hr>
                    <h5>Data Pendukung</h5>
                    <ul>
                        <li><strong>No. RAB:</strong> {{ $row->rab->nomor_rab ?? '-' }}</li>
                        <li><strong>No. Pendaftaran:</strong> {{ $row->rab->spko->pengajuan->no_pendaftaran ?? '-' }}</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Aksi Direktur</h3>
                </div>
                <div class="card-body">
                    
                    <a href="{{ route('direktur.spk.preview', $row->id) }}" target="_blank" class="btn btn-info btn-block mb-4">
                        <i class="fas fa-file-alt"></i> Lihat Surat SPK
                    </a>

                    @if($row->status === 'kirim_direktur')
                        <p class="text-muted text-center">Silakan tinjau surat di atas sebelum memberikan keputusan.</p>
                        
                        <form action="{{ route('direktur.spk.approve', $row->id) }}" method="POST" class="mb-2">
                            @csrf
                            <button type="submit" class="btn btn-success btn-block" onclick="return confirm('Apakah Anda yakin menyetujui SPK ini?')">
                                <i class="fas fa-check"></i> SETUJUI
                            </button>
                        </form>

                        <button type="button" class="btn btn-danger btn-block" data-toggle="modal" data-target="#modalTolak">
                            <i class="fas fa-times"></i> TOLAK
                        </button>

                    @elseif($row->status === 'disetujui')
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i> Disetujui pada: <br>
                            <b>{{ $row->disetujui_at ? date('d-m-Y H:i', strtotime($row->disetujui_at)) : '-' }}</b>
                        </div>
                    @elseif($row->status === 'ditolak')
                        <div class="alert alert-danger">
                            <i class="fas fa-times-circle"></i> Ditolak. <br>
                            <em>"{{ $row->catatan }}"</em>
                        </div>
                    @endif

                    <div class="mt-4">
                        <a href="{{ url('direktur/spk') }}" class="btn btn-default btn-block">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalTolak">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('direktur.spk.reject', $row->id) }}" method="POST">
                    @csrf
                    <div class="modal-header bg-danger">
                        <h4 class="modal-title">Tolak SPK</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Alasan Penolakan:</label>
                            <textarea name="catatan" class="form-control" rows="3" required placeholder="Contoh: Alamat salah, mohon perbaiki..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Kirim Penolakan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-direktur>
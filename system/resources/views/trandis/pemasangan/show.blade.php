<x-trandis>
    <div class="row">
        <div class="col-md-4">
            
            <a href="{{ $back_url }}" class="btn btn-default btn-sm mb-3 shadow-sm">
                <i class="fas fa-arrow-left mr-1"></i> Kembali
            </a>

            @php
                $cardClass = 'primary'; // Default
                if($row->status == 'selesai') $cardClass = 'success';
                elseif($row->status_teknis == 'working') $cardClass = 'danger';
                elseif($row->status_teknis == 'paused') $cardClass = 'warning';
            @endphp

            <div class="card card-outline card-{{ $cardClass }} elevation-3 text-center">
                <div class="card-header">
                    <h5 class="mb-0 font-weight-bold text-uppercase">Kontrol Kerja</h5>
                </div>
                <div class="card-body">
                    
                    @if($row->status == 'selesai')
                        <div class="py-4 text-success">
                            <i class="fas fa-check-circle fa-5x mb-3"></i>
                            <h3 class="font-weight-bold">SELESAI</h3>
                            <p class="text-muted">Pekerjaan telah diverifikasi selesai.</p>
                        </div>
                        <a href="{{ route('trandis.spk.print', $row->id) }}" target="_blank" class="btn btn-default btn-block">
                            <i class="fas fa-print mr-1"></i> Cetak Arsip PDF
                        </a>

                    @else
                        <div class="mb-4">
                            @if($row->status_teknis == 'scheduled')
                                <span class="fa-stack fa-2x text-info">
                                    <i class="fas fa-circle fa-stack-2x"></i>
                                    <i class="fas fa-calendar-alt fa-stack-1x fa-inverse"></i>
                                </span>
                                <h5 class="mt-2 font-weight-bold text-info">SIAP DIKERJAKAN</h5>
                                <p class="small text-muted">Jadwal: {{ date('d M Y H:i', strtotime($row->tgl_jadwal)) }}</p>

                            @elseif($row->status_teknis == 'working')
                                <div class="spinner-grow text-danger mb-2" style="width: 3rem; height: 3rem;" role="status"></div>
                                <h5 class="font-weight-bold text-danger">SEDANG BERLANGSUNG</h5>
                                <p class="small text-muted">Timer berjalan sejak {{ $row->active_log->mulai_pada->format('H:i') ?? 'tadi' }}</p>

                            @elseif($row->status_teknis == 'paused')
                                <span class="fa-stack fa-2x text-warning">
                                    <i class="fas fa-circle fa-stack-2x"></i>
                                    <i class="fas fa-pause fa-stack-1x fa-inverse"></i>
                                </span>
                                <h5 class="mt-2 font-weight-bold text-warning">PEKERJAAN DIJEDA</h5>
                            @endif
                        </div>

                        @if(!$row->is_working)
                            <form action="{{ url('trandis/pemasangan/'.$row->id.'/start') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success btn-lg btn-block font-weight-bold py-3 shadow" onclick="return confirm('Mulai timer sekarang?')">
                                    <i class="fas fa-play mr-2"></i> MULAI / LANJUTKAN
                                </button>
                            </form>
                        @else
                            <button class="btn btn-danger btn-lg btn-block font-weight-bold py-3 shadow" data-toggle="modal" data-target="#modalStop">
                                <i class="fas fa-stop mr-2"></i> STOP / ISTIRAHAT
                            </button>
                        @endif

                        <div class="mt-3">
                            <a href="{{ route('trandis.spk.print', $row->id) }}" target="_blank" class="btn btn-default btn-sm btn-block">
                                <i class="fas fa-file-pdf mr-1"></i> Cetak Surat Jalan
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <h3 class="profile-username text-center">{{ $row->nama_pelanggan }}</h3>
                    <p class="text-muted text-center">{{ $row->nomor_spk }}</p>
                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <b>Alamat</b> <a class="float-right text-dark text-right">{{ Str::limit($row->alamat, 25) }}</a>
                        </li>
                        {{-- <li class="list-group-item">
                            <b>Wilayah</b> <a class="float-right text-dark">{{ $row->lokasi ?? '-' }}</a>
                        </li> --}}
                    </ul>
                </div>
            </div>

            @if($row->status != 'selesai' && $row->status_teknis != 'pending')
                <div class="card shadow-none border border-success bg-light">
                    <div class="card-body text-center">
                        <p class="text-muted text-sm mb-2">Hanya klik tombol ini jika seluruh pekerjaan fisik selesai & foto bukti sudah diupload.</p>
                        <form action="{{ url('trandis/pemasangan/'.$row->id.'/finish') }}" method="POST" onsubmit="return confirm('Konfirmasi pekerjaan selesai total?')">
                            @csrf
                            <button class="btn btn-outline-success btn-block font-weight-bold">
                                <i class="fas fa-flag-checkered mr-2"></i> SELESAI TOTAL
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-md-8">
            <div class="card card-primary card-outline card-tabs elevation-2">
                <div class="card-header p-0 pt-1 border-bottom-0">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="pill" href="#tab-log"><i class="fas fa-history mr-1"></i> Riwayat Log</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="pill" href="#tab-foto"><i class="fas fa-camera mr-1"></i> Dokumentasi</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        
                        <div class="tab-pane fade show active" id="tab-log">
                            @if($row->logs->isEmpty())
                                <div class="text-center py-5 text-muted">
                                    <i class="fas fa-clock fa-3x mb-3 text-light"></i><br>
                                    Belum ada aktivitas pengerjaan.
                                </div>
                            @else
                                <div class="timeline timeline-inverse">
                                    @foreach($row->logs->sortByDesc('created_at') as $log)
                                    <div class="time-label">
                                        <span class="bg-secondary px-3">{{ $log->mulai_pada->format('d M Y') }}</span>
                                    </div>
                                    <div>
                                        <i class="fas fa-tools bg-primary"></i>
                                        <div class="timeline-item shadow-sm">
                                            <span class="time"><i class="far fa-clock"></i> {{ $log->mulai_pada->format('H:i') }}</span>
                                            <h3 class="timeline-header">
                                                Sesi Pengerjaan 
                                                @if(!$log->selesai_pada) 
                                                    <span class="badge badge-danger ml-2">Sedang Berlangsung</span>
                                                @else
                                                    <span class="text-muted ml-1">selesai pukul <b>{{ $log->selesai_pada->format('H:i') }}</b></span>
                                                @endif
                                            </h3>
                                            @if($log->catatan)
                                                <div class="timeline-body bg-light border-top">
                                                    <i class="fas fa-comment-alt text-secondary mr-1"></i> <em>"{{ $log->catatan }}"</em>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    @endforeach
                                    <div>
                                        <i class="far fa-clock bg-gray"></i>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="tab-pane fade" id="tab-foto">
                            @if($row->status != 'selesai')
                                <div class="callout callout-info bg-light mb-4">
                                    <h6 class="text-info font-weight-bold"><i class="fas fa-upload mr-1"></i> Upload Foto Baru</h6>
                                    <form action="{{ url('trandis/pemasangan/'.$row->id.'/photo') }}" method="POST" enctype="multipart/form-data" class="mt-2">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="custom-file">
                                                    <input type="file" name="foto" class="custom-file-input" id="customFile" required accept="image/*">
                                                    <label class="custom-file-label" for="customFile">Pilih file...</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <input type="text" name="keterangan" class="form-control" placeholder="Keterangan (Opsional)">
                                            </div>
                                            <div class="col-md-2">
                                                <button class="btn btn-primary btn-block">Upload</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            @endif

                            <div class="row">
                                @forelse($row->fotos as $foto)
                                    <div class="col-sm-4">
                                        <div class="card mb-4 box-shadow">
                                            <a href="{{ url($foto->foto_path) }}" target="_blank">
                                                <img class="card-img-top" src="{{ url($foto->foto_path) }}" style="height: 180px; width: 100%; display: block; object-fit: cover;" alt="Foto">
                                            </a>
                                            <div class="card-body p-2 text-center">
                                                <p class="card-text small text-muted">{{ $foto->keterangan ?? 'Dokumentasi Lapangan' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12 text-center py-5 text-muted">
                                        <i class="fas fa-images fa-3x mb-3 text-light"></i><br>
                                        Belum ada foto dokumentasi.
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalStop">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ url('trandis/pemasangan/'.$row->id.'/stop') }}" method="POST">
                    @csrf
                    <div class="modal-header bg-danger text-white">
                        <h4 class="modal-title"><i class="fas fa-pause-circle mr-2"></i> Hentikan Sementara</h4>
                        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <p>Timer akan dihentikan. Mohon laporkan progres terkini sebelum istirahat/pulang.</p>
                        <div class="form-group">
                            <label>Laporan Hasil Kerja:</label>
                            <textarea name="catatan" class="form-control" rows="3" required placeholder="Contoh: Pipa 2 meter terpasang, terkendala hujan..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Simpan Log & Stop</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-trandis>

@push('script')
<script>
    // Agar nama file muncul di label input file bootstrap
    $('.custom-file-input').on('change', function() {
        var fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').addClass("selected").html(fileName);
    });
</script>
@endpush
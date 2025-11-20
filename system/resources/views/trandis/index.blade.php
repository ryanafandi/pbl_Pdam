<x-trandis>
    <div class="row mb-2">
        <div class="col-12">
            <div class="alert alert-white border-left border-primary shadow-sm alert-dismissible fade show" role="alert">
                <h5><i class="icon fas fa-hard-hat text-primary"></i> Selamat Datang, <b>{{ Auth::user()->nama ?? 'Tim Lapangan' }}</b>!</h5>
                <p class="mb-0 text-muted">Sistem Manajemen Pelaksanaan Pekerjaan Lapangan PDAM Tirta Pawan.</p>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger shadow-sm">
                <div class="inner">
                    <h3>{{ $data['baru'] }}</h3>
                    <p>SPK Baru Masuk</p>
                </div>
                <div class="icon">
                    <i class="fas fa-inbox"></i>
                </div>
                <a href="{{ url('trandis/spk-masuk') }}" class="small-box-footer">
                    Lihat & Jadwalkan <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-info shadow-sm">
                <div class="inner">
                    <h3>{{ $data['jadwal'] }}</h3>
                    <p>Siap Dikerjakan</p>
                </div>
                <div class="icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <a href="{{ url('trandis/pemasangan') }}" class="small-box-footer">
                    Lihat Daftar <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning shadow-sm">
                <div class="inner text-white">
                    <h3>{{ $data['proses'] }}</h3>
                    <p>Sedang Berlangsung</p>
                </div>
                <div class="icon">
                    <i class="fas fa-tools"></i>
                </div>
                <a href="{{ url('trandis/pemasangan') }}" class="small-box-footer" style="color: white !important;">
                    Monitoring <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-success shadow-sm">
                <div class="inner">
                    <h3>{{ $data['selesai'] }}</h3>
                    <p>Total Selesai</p>
                </div>
                <div class="icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <span class="small-box-footer">
                    Terverifikasi <i class="fas fa-certificate"></i>
                </span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card card-outline card-primary h-100">
                <div class="card-header border-0">
                    <h3 class="card-title font-weight-bold">
                        <i class="far fa-clock mr-1 text-primary"></i> Jadwal Pengerjaan Hari Ini
                    </h3>
                    <div class="card-tools">
                        <span class="badge badge-primary">{{ $today_schedule->count() }} Tugas</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-valign-middle mb-0">
                            <thead>
                            <tr>
                                <th>Pelanggan</th>
                                <th>Jam</th>
                                <th class="text-right">Status</th>
                            </tr>
                            </thead>
                            <tbody>
                                @forelse($today_schedule as $item)
                                <tr>
                                    <td>
                                        <span class="font-weight-bold text-dark">{{ $item->nama_pelanggan }}</span><br>
                                        <small class="text-muted">{{ Str::limit($item->alamat, 25) }}</small>
                                    </td>
                                    <td class="text-primary font-weight-bold">
                                        {{ date('H:i', strtotime($item->tgl_jadwal)) }}
                                    </td>
                                    <td class="text-right">
                                        @if($item->status_teknis == 'working')
                                            <a href="{{ url('trandis/pemasangan', $item->id) }}" class="btn btn-xs btn-danger">
                                                <i class="fas fa-spinner fa-spin"></i> Kerja
                                            </a>
                                        @else
                                            <a href="{{ url('trandis/pemasangan', $item->id) }}" class="btn btn-xs btn-primary">
                                                <i class="fas fa-play"></i> Mulai
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-muted">
                                        <i class="fas fa-calendar-check fa-2x mb-2 text-gray-300"></i><br>
                                        Tidak ada jadwal khusus hari ini.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card card-outline card-secondary h-100">
                <div class="card-header border-0">
                    <h3 class="card-title font-weight-bold">
                        <i class="fas fa-history mr-1 text-secondary"></i> Riwayat SPK Masuk
                    </h3>
                    <div class="card-tools">
                        <a href="{{ url('trandis/spk-masuk') }}" class="btn btn-tool btn-sm">
                            <i class="fas fa-bars"></i> Lihat Semua
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <ul class="products-list product-list-in-card pl-2 pr-2">
                        @forelse($recent_spk as $item)
                        <li class="item">
                            <div class="product-img">
                                <div class="d-flex justify-content-center align-items-center bg-light rounded" style="width:50px; height:50px;">
                                    <i class="fas fa-file-contract text-primary fa-lg"></i>
                                </div>
                            </div>
                            <div class="product-info">
                                <a href="{{ url('trandis/spk-masuk', $item->id) }}" class="product-title text-dark">
                                    {{ $item->nomor_spk }}
                                    @if($item->status_teknis == 'pending')
                                        <span class="badge badge-danger float-right">Baru</span>
                                    @else
                                        <span class="badge badge-info float-right">Terjadwal</span>
                                    @endif
                                </a>
                                <span class="product-description text-muted">
                                    {{ $item->nama_pelanggan }} - {{ $item->pekerjaan }}
                                </span>
                            </div>
                        </li>
                        @empty
                        <li class="item text-center py-4 text-muted">
                            Belum ada data riwayat terbaru.
                        </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-trandis>
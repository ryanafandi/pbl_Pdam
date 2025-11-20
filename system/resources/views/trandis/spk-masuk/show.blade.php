<x-trandis>
    <div class="row">
        <div class="col-md-8">
            
            <div class="card card-outline card-primary elevation-2">
                <div class="card-header">
                    <h3 class="card-title font-weight-bold">
                        <i class="fas fa-user-circle mr-2"></i> Detail Pelanggan
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 border-right">
                            <strong class="text-secondary">Nama Pelanggan</strong>
                            <h5 class="font-weight-bold text-dark mb-3">{{ $row->nama_pelanggan }}</h5>

                            <strong class="text-secondary">Kontak / No. HP</strong>
                            <p class="mb-3">
                                @if(isset($row->rab->spko->pengajuan->nomor_telepon))
                                    <span class="font-weight-bold">{{ $row->rab->spko->pengajuan->nomor_telepon }}</span>
                                    <a href="https://wa.me/{{ preg_replace('/^0/', '62', $row->rab->spko->pengajuan->nomor_telepon) }}" target="_blank" class="btn btn-success btn-xs ml-2">
                                        <i class="fab fa-whatsapp"></i> Chat WA
                                    </a>
                                @else
                                    <span class="text-muted font-italic">- Tidak ada nomor -</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6 pl-md-4">
                            <strong class="text-secondary">Alamat Pemasangan</strong>
                            <p class="font-weight-bold text-dark mb-1">{{ $row->alamat }}</p>
                            <p class="text-muted small"><i class="fas fa-map-marker-alt text-danger mr-1"></i> {{ $row->lokasi ?? 'Wilayah belum diset' }}</p>
                        </div>
                    </div>
                    
                    <hr>

                    <div class="mt-3">
                        <strong class="text-primary mb-2 d-block"><i class="fas fa-map-marked-alt mr-1"></i> Titik Lokasi Pemasangan</strong>
                        
                        @php
                            // Cek apakah data survei dan koordinat tersedia
                            $survei = $row->rab->spko->survei ?? null;
                            $hasMap = $survei && $survei->latitude && $survei->longitude;
                        @endphp

                        <div class="border rounded overflow-hidden shadow-sm">
                            @if($hasMap)
                                {{-- OPSI 1: TAMPILKAN GOOGLE MAPS JIKA ADA KOORDINAT --}}
                                <div style="width: 100%; height: 350px; position: relative;">
                                    <iframe 
                                        width="100%" 
                                        height="100%" 
                                        frameborder="0" 
                                        scrolling="no" 
                                        marginheight="0" 
                                        marginwidth="0" 
                                        src="https://maps.google.com/maps?q={{ $survei->latitude }},{{ $survei->longitude }}&hl=id&z=17&output=embed">
                                    </iframe>
                                </div>
                                <div class="bg-light p-2 text-center border-top">
                                    <span class="text-muted small mr-2"><i class="fas fa-map-pin text-danger"></i> {{ $survei->latitude }}, {{ $survei->longitude }}</span>
                                    <a href="https://www.google.com/maps/search/?api=1&query={{ $survei->latitude }},{{ $survei->longitude }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-external-link-alt mr-1"></i> Buka di Google Maps
                                    </a>
                                </div>

                            @elseif(!empty($row->rab->spko->pengajuan->denah_url))
                                {{-- OPSI 2: FALLBACK KE GAMBAR DENAH JIKA KOORDINAT KOSONG --}}
                                <div class="p-3 text-center bg-light">
                                    <span class="badge badge-warning mb-2"><i class="fas fa-exclamation-triangle"></i> Koordinat belum diinput Perencanaan</span>
                                    <div class="d-block mt-2">
                                        <a href="{{ url($row->rab->spko->pengajuan->denah_url) }}" target="_blank">
                                            <img src="{{ url($row->rab->spko->pengajuan->denah_url) }}" class="img-fluid rounded" style="max-height: 350px;" alt="Denah Lokasi">
                                        </a>
                                    </div>
                                    <p class="text-muted small mt-2 font-italic">Menampilkan gambar denah dari pemohon.</p>
                                </div>

                            @else
                                {{-- OPSI 3: JIKA TIDAK ADA DATA SAMA SEKALI --}}
                                <div class="p-5 text-center bg-light">
                                    <i class="fas fa-map-signs fa-3x text-gray-300 mb-3"></i>
                                    <p class="text-muted">Tidak ada data lokasi (Koordinat maupun Denah).</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-info card-outline collapsed-card elevation-1">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-tools mr-2"></i> Daftar Kebutuhan Material (RAB)</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i></button>
                    </div>
                </div>
                <div class="card-body p-0 table-responsive">
                    <table class="table table-striped table-sm">
                        <thead class="bg-light">
                            <tr>
                                <th class="pl-4">Uraian Pekerjaan / Material</th>
                                <th class="text-center" style="width: 100px">Volume</th>
                                <th class="text-center" style="width: 100px">Satuan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($row->rab && $row->rab->details->isNotEmpty())
                                @foreach($row->rab->details as $detail)
                                <tr>
                                    <td class="pl-4">{{ $detail->uraian_pekerjaan }}</td>
                                    <td class="text-center font-weight-bold">{{ $detail->volume + 0 }}</td> {{-- +0 untuk menghilangkan desimal .00 jika bulat --}}
                                    <td class="text-center text-muted">{{ $detail->satuan }}</td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="3" class="text-center py-3 text-muted">Data rincian RAB tidak ditemukan.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            
            <a href="{{ $back_url ?? url('trandis/spk-masuk') }}" class="btn btn-default btn-block mb-3 shadow-sm">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar
            </a>

            <div class="card card-primary shadow-none border">
                <div class="card-header text-center">
                    <h5 class="mb-0 font-weight-bold">STATUS TUGAS</h5>
                </div>
                <div class="card-body text-center pt-4 pb-4">
                    <div class="mb-4">
                        <h2 class="text-dark font-weight-bold">{{ $row->nomor_spk }}</h2>
                        <span class="badge badge-warning px-3 py-2 mt-1" style="font-size: 0.9rem;">BELUM DIJADWALKAN</span>
                    </div>

                    <ul class="list-group list-group-unbordered mb-4 text-left">
                        <li class="list-group-item py-2">
                            <b>Tgl Disetujui</b> <span class="float-right text-muted">{{ date('d M Y', strtotime($row->disetujui_at)) }}</span>
                        </li>
                        <li class="list-group-item py-2">
                            <b>Jenis Pekerjaan</b> <span class="float-right text-muted">{{ $row->pekerjaan }}</span>
                        </li>
                    </ul>

                    @if($row->catatan)
                    <div class="alert alert-light text-left border p-2 mb-4 small">
                        <strong class="text-muted"><i class="fas fa-sticky-note mr-1"></i> Catatan Admin:</strong><br>
                        "{{ $row->catatan }}"
                    </div>
                    @endif

                    <p class="small text-muted mb-2">Silakan buat jadwal untuk memulai proses pemasangan.</p>
                    
                    <button class="btn btn-primary btn-lg btn-block shadow" data-toggle="modal" data-target="#modalJadwal">
                        <i class="fas fa-calendar-plus mr-2"></i> ATUR JADWAL
                    </button>

                    <a href="{{ route('trandis.spk.print', $row->id) }}" target="_blank" class="btn btn-outline-secondary btn-block mt-3">
                        <i class="fas fa-print mr-2"></i> Preview Surat SPK
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalJadwal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fas fa-calendar-alt mr-2"></i> Jadwalkan Pemasangan</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <form action="{{ url('trandis/spk-masuk/'.$row->id.'/jadwal') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle mr-1"></i> Anda akan menjadwalkan pemasangan untuk pelanggan: <br>
                            <strong>{{ $row->nama_pelanggan }}</strong>
                        </div>
                        <div class="form-group">
                            <label>Pilih Tanggal & Jam Rencana Pengerjaan</label>
                            <input type="datetime-local" name="tgl_jadwal" class="form-control form-control-lg" required>
                        </div>
                        <p class="text-muted small mb-0">
                            * Setelah disimpan, SPK ini akan berpindah ke menu <strong>"Proses Pemasangan"</strong> dan siap untuk dieksekusi (Start Timer).
                        </p>
                    </div>
                    <div class="modal-footer justify-content-between bg-light">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary font-weight-bold px-4">
                            <i class="fas fa-save mr-2"></i> SIMPAN JADWAL
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-trandis>
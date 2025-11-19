<x-perencanaan>
    @php
        $sv = $row->survei ?? null;

        // Fallback dari SPKO (jika tabel survei belum diisi)
        $jadwal = $sv?->scheduled_at ?? $row->survey_scheduled_at;
        $petugasNama = $sv?->petugas_nama ?? $row->disurvey_oleh_nama;
        $petugasNipp = $sv?->petugas_nipp ?? $row->disurvey_oleh_nipp;

        $fmt = fn($dt) => $dt ? \Illuminate\Support\Carbon::parse($dt)->format('d/m/Y H:i') : '-';

        $lat = $sv?->latitude;
        $lng = $sv?->longitude;
        $hasCoords = is_numeric($lat) && is_numeric($lng);
        $mapUrl = $hasCoords ? 'https://www.google.com/maps?q=' . $lat . ',' . $lng : null;
    @endphp

    <div class="content-header d-flex align-items-center justify-content-between mb-3">
        <div>
            <h1 class="m-0 text-primary fw-bold">
                <i class="fas fa-clipboard-check"></i> Detail Survei
            </h1>
            <small class="text-muted">
                SPKO: <span class="text-monospace">{{ $row->nomor_spko }}</span>
            </small>
        </div>
        <div class="d-flex">
            <a href="{{ url('perencanaan/survei/' . $row->id . '/edit') }}" class="btn btn-primary mr-2">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ url('perencanaan/survei') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    {{-- Ringkasan cepat --}}
    <div class="row mb-3">
        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <span class="badge badge-info mr-2"><i class="fas fa-calendar-alt"></i></span>
                        <div>
                            <div class="text-muted small mb-1">Jadwal Survei</div>
                            <div class="font-weight-bold">{{ $fmt($jadwal) }}</div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="badge badge-primary mr-2"><i class="fas fa-user-check"></i></span>
                        <div>
                            <div class="text-muted small mb-1">Petugas</div>
                            <div class="font-weight-bold">
                                {{ $petugasNama ?? '-' }}
                                @if ($petugasNipp)
                                    <small class="text-muted">({{ $petugasNipp }})</small>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Status --}}
        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small mb-1">Status Survei</div>
                    @if (!empty($sv?->done_at))
                        <span class="badge badge-success">
                            <i class="fas fa-check-circle"></i> Selesai {{ $fmt($sv->done_at) }}
                        </span>
                    @else
                        <span class="badge badge-secondary">
                            <i class="fas fa-hourglass-half"></i> Belum Selesai
                        </span>
                    @endif

                    <div class="mt-3">
                        <div class="text-muted small mb-1">Validasi</div>
                        <div>
                            <span class="badge badge-light border mr-2">
                                <i class="fas fa-user-tie"></i> {{ $sv?->disetujui_oleh ?: '—' }}
                            </span>
                            <span class="badge badge-light border">
                                <i class="far fa-clock"></i> {{ $fmt($sv?->disetujui_at) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Identitas Pemohon --}}
        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small mb-1">Pemohon</div>
                    <div class="font-weight-bold">
                        {{ $row->pengajuan->pemohon_nama ?? ($row->pemilik_nama ?? '-') }}
                    </div>
                    <div class="text-muted small mb-1 mt-2">Alamat</div>
                    <div>{{ $row->alamat ?? '-' }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Detail dua kolom --}}
    <section class="content">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="row">
                    {{-- Kolom kiri: lokasi & foto --}}
                    <div class="col-md-6 mb-4 mb-md-0">
                        <h5 class="mb-3 text-primary">
                            <i class="fas fa-map-marked-alt"></i> Lokasi
                        </h5>

                        <dl class="row mb-0">
                            <dt class="col-sm-4">Koordinat</dt>
                            <dd class="col-sm-8">
                                @if ($hasCoords)
                                    <a href="{{ $mapUrl }}" target="_blank" class="text-decoration-none">
                                        <i class="fas fa-map-pin"></i> {{ $lat }}, {{ $lng }}
                                        <small class="text-muted">(Buka di Maps)</small>
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </dd>

                            <dt class="col-sm-4">Foto</dt>
                            <dd class="col-sm-8">
                                @if (!empty($sv?->lokasi_foto))
                                    <div class="border rounded p-2 d-inline-block">
                                        <a href="{{ url('public/app/survei/' . $sv->lokasi_foto) }}" target="_blank"
                                            title="Lihat ukuran penuh">
                                            <img src="{{ url('public/app/survei/' . $sv->lokasi_foto) }}"
                                                class="img-fluid rounded" style="max-width: 280px" alt="Foto lokasi">
                                        </a>
                                    </div>
                                    <div class="mt-2">
                                        <a href="{{ url('public/app/survei/' . $sv->lokasi_foto) }}" target="_blank">
                                            <i class="fas fa-external-link-alt"></i> Lihat ukuran penuh
                                        </a>
                                    </div>
                                @else
                                    <span class="text-muted">Belum ada foto</span>
                                @endif
                            </dd>
                        </dl>
                    </div>

                    {{-- Kolom kanan: data teknis (termasuk PENSIL) --}}
                    <div class="col-md-6">
                        <h5 class="mb-3 text-primary">
                            <i class="fas fa-tools"></i> Data Teknis
                        </h5>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="mb-2 small text-muted">Pipa Dinas</div>
                                <div class="p-2 rounded border bg-light">
                                    {{ $sv?->jenis_pipa_dinas ?? '-' }}
                                    <span class="text-muted">•</span>
                                    {{ $sv?->panjang_pipa_dinas ?? '0' }} m
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="mb-2 small text-muted">Pipa Persil</div>
                                <div class="p-2 rounded border bg-light">
                                    {{ $sv?->jenis_pipa_persil ?? '-' }}
                                    <span class="text-muted">•</span>
                                    {{ $sv?->panjang_pipa_persil ?? '0' }} m
                                </div>
                            </div>

                            <div class="col-sm-6 mt-3">
                                <div class="mb-2 small text-muted">Jenis Sambungan</div>
                                <div class="p-2 rounded border">{{ $sv?->jenis_sambungan ?? '-' }}</div>
                            </div>
                            <div class="col-sm-6 mt-3">
                                <div class="mb-2 small text-muted">Meter Air</div>
                                <div class="p-2 rounded border">{{ $sv?->jenis_meter_air ?? '-' }}</div>
                            </div>

                            <div class="col-sm-6 mt-3">
                                <div class="mb-2 small text-muted">Jenis Tanah</div>
                                <div class="p-2 rounded border">{{ $sv?->jenis_tanah ?? '-' }}</div>
                            </div>
                            <div class="col-sm-6 mt-3">
                                <div class="mb-2 small text-muted">Kondisi Jalan</div>
                                <div class="p-2 rounded border">{{ $sv?->kondisi_jalan ?? '-' }}</div>
                            </div>

                            <div class="col-sm-6 mt-3">
                                <div class="mb-2 small text-muted">Kedalaman Galian</div>
                                <div class="p-2 rounded border">
                                    {{ $sv?->kedalaman_galian ?? '-' }} <span class="text-muted">m</span>
                                </div>
                            </div>
                            <div class="col-sm-6 mt-3">
                                <div class="mb-2 small text-muted">Kendala Lapangan</div>
                                <div class="p-2 rounded border">{{ $sv?->kendala_lapangan ?? '-' }}</div>
                            </div>

                            <div class="col-12 mt-3">
                                <div class="mb-2 small text-muted">Catatan Teknis</div>
                                <div class="p-2 rounded border">{{ $sv?->catatan_teknis ?? '-' }}</div>
                            </div>
                            <div class="mt-3">
                                <div class="text-muted small mb-1">Terobos</div>
                                @if (is_null($sv?->terobos))
                                    <span class="badge badge-secondary"><i class="fas fa-minus-circle"></i> Belum
                                        ditentukan</span>
                                @elseif ($sv->terobos)
                                    <span class="badge badge-danger"><i class="fas fa-exclamation-triangle"></i> Ya,
                                        terobos</span>
                                @else
                                    <span class="badge badge-success"><i class="fas fa-check-circle"></i> Tidak</span>
                                @endif
                            </div>

                        </div>
                    </div>
                </div> {{-- row --}}
            </div>
        </div>
    </section>
</x-perencanaan>

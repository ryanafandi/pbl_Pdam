<x-perencanaan>
    @php
        $sv = $row->survei ?? null;

        // Fallback jadwal & petugas: pakai SPKO kalau SURVEI kosong
        $defaultScheduled = $sv?->scheduled_at ?? $row->survey_scheduled_at;
        $defaultPetugas = $sv->petugas_nama ?? $row->disurvey_oleh_nama;
        $defaultNipp = $sv->petugas_nipp ?? $row->disurvey_oleh_nipp;

        $fmtLocal = fn($dt) => $dt ? \Illuminate\Support\Carbon::parse($dt)->format('Y-m-d\TH:i') : '';
        $latOld = old('latitude', $sv->latitude ?? '');
        $lngOld = old('longitude', $sv->longitude ?? '');
        $hasCoords = is_numeric($latOld) && is_numeric($lngOld);
        $mapUrl = $hasCoords ? 'https://www.google.com/maps?q=' . trim($latOld) . ',' . trim($lngOld) : null;
    @endphp

    <div class="content-header d-flex align-items-center justify-content-between mb-3">
        <div>
            <h1 class="m-0 text-primary fw-bold">
                <i class="fas fa-pen-square"></i> Input / Edit Hasil Survei
            </h1>
            <small class="text-muted">
                SPKO: <span class="text-monospace">{{ $row->nomor_spko }}</span>
            </small>
        </div>
        <a href="{{ url('perencanaan/survei') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <section class="content">
        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Periksa lagi:</strong>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form action="{{ url('perencanaan/survei/' . $row->id) }}" method="POST" enctype="multipart/form-data"
            id="formSurvei">
            @csrf @method('PUT')

            {{-- 1) JADWAL & PETUGAS --}}
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-white d-flex align-items-center">
                    <strong><i class="fas fa-calendar-alt mr-1"></i> Jadwal & Petugas</strong>
                </div>
                <div class="card-body">
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label class="mb-1">Jadwal</label>
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text"><i
                                            class="far fa-clock"></i></span></div>
                                <input type="datetime-local" name="scheduled_at" class="form-control"
                                    value="{{ old('scheduled_at', $fmtLocal($defaultScheduled)) }}">
                            </div>
                            <small class="text-muted">Kosongkan bila belum ditentukan.</small>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="mb-1">Petugas</label>
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text"><i
                                            class="fas fa-user-check"></i></span></div>
                                <input type="text" name="petugas_nama" class="form-control"
                                    value="{{ old('petugas_nama', $defaultPetugas) }}" placeholder="Nama petugas">
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="mb-1">NIPP</label>
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text"><i
                                            class="fas fa-id-card"></i></span></div>
                                <input type="text" name="petugas_nipp" class="form-control"
                                    value="{{ old('petugas_nipp', $defaultNipp) }}" placeholder="Nomor induk pegawai">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2) LOKASI & FOTO --}}
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-white d-flex align-items-center justify-content-between">
                    <strong><i class="fas fa-map-marked-alt mr-1"></i> Lokasi & Foto</strong>
                    <div>
                        <button type="button" class="btn btn-sm btn-outline-secondary" id="btnSwap">
                            <i class="fas fa-exchange-alt"></i> Tukar Lat/Lng
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-dark" id="btnCopy">
                            <i class="fas fa-copy"></i> Salin Koordinat
                        </button>
                        @if ($hasCoords)
                            <a href="{{ $mapUrl }}" target="_blank" class="btn btn-sm btn-outline-info"
                                id="btnMaps">
                                <i class="fas fa-map"></i> Buka Maps
                            </a>
                        @else
                            <button type="button" class="btn btn-sm btn-outline-info" id="btnMaps" disabled>
                                <i class="fas fa-map"></i> Buka Maps
                            </button>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label class="mb-1">Latitude</label>
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text"><i
                                            class="fas fa-map-pin"></i></span></div>
                                <input type="text" name="latitude" id="latInput" class="form-control"
                                    inputmode="decimal" value="{{ $latOld }}" placeholder="-6.2">
                            </div>
                            <small class="text-muted">Rentang: -90 s/d 90</small>
                        </div>
                        <div class="form-group col-md-3">
                            <label class="mb-1">Longitude</label>
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text"><i
                                            class="fas fa-map-pin"></i></span></div>
                                <input type="text" name="longitude" id="lngInput" class="form-control"
                                    inputmode="decimal" value="{{ $lngOld }}" placeholder="106.8">
                            </div>
                            <small class="text-muted">Rentang: -180 s/d 180</small>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="mb-1">Foto Lokasi</label>
                            <div class="d-flex align-items-start">
                                <div class="mr-3">
                                    <img id="previewFoto"
                                        src="{{ !empty($sv?->lokasi_foto) ? url('public/app/survei/' . $sv->lokasi_foto) : url('public/images.jpg') }}"
                                        class="rounded border" style="width: 160px; height: 120px; object-fit: cover;"
                                        alt="Preview foto">
                                </div>
                                <div class="flex-grow-1">
                                    <input type="file" name="lokasi_foto" id="lokasi_foto"
                                        class="form-control mb-2" accept="image/*">
                                    <small class="text-muted d-block mb-2">Format: JPG/PNG ≤ 2MB. Preview tampil
                                        otomatis.</small>
                                    @if (!empty($sv?->lokasi_foto))
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="hapus_foto"
                                                value="1" id="hapusFoto">
                                            <label class="form-check-label" for="hapusFoto">Hapus foto saat
                                                disimpan</label>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 3) DATA TEKNIS (PENSIL) --}}
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-white">
                    <strong><i class="fas fa-tools mr-1"></i> Data Teknis</strong>
                </div>
                <div class="card-body">
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label class="mb-1">Jenis Pipa Dinas</label>
                            <input name="jenis_pipa_dinas" class="form-control"
                                value="{{ old('jenis_pipa_dinas', $sv->jenis_pipa_dinas ?? '') }}"
                                placeholder="PE 1/2&quot;">
                        </div>
                        <div class="form-group col-md-3">
                            <label class="mb-1">Panjang Pipa Dinas</label>
                            <div class="input-group">
                                <input name="panjang_pipa_dinas" class="form-control" inputmode="decimal"
                                    value="{{ old('panjang_pipa_dinas', $sv->panjang_pipa_dinas ?? '') }}"
                                    placeholder="0">
                                <div class="input-group-append"><span class="input-group-text">m</span></div>
                            </div>
                        </div>

                        {{-- PENSIL (sesuai tabel kamu) --}}
                        <div class="form-group col-md-3">
                            <label class="mb-1">Jenis Pipa Persil</label>
                            <input name="jenis_pipa_persil" class="form-control"
                                value="{{ old('jenis_pipa_persil', $sv->jenis_pipa_persil ?? '') }}"
                                placeholder="PVC 1/2&quot;">
                        </div>
                        <div class="form-group col-md-3">
                            <label class="mb-1">Panjang Pipa Persil</label>
                            <div class="input-group">
                                <input name="panjang_pipa_persil" class="form-control" inputmode="decimal"
                                    value="{{ old('panjang_pipa_persil', $sv->panjang_pipa_persil ?? '') }}"
                                    placeholder="0">
                                <div class="input-group-append"><span class="input-group-text">m</span></div>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label class="mb-1">Jenis Sambungan</label>
                            <input name="jenis_sambungan" class="form-control"
                                value="{{ old('jenis_sambungan', $sv->jenis_sambungan ?? '') }}"
                                placeholder="Saddle / T">
                        </div>
                        <div class="form-group col-md-3">
                            <label class="mb-1">Meter Air</label>
                            <input name="jenis_meter_air" class="form-control"
                                value="{{ old('jenis_meter_air', $sv->jenis_meter_air ?? '') }}" placeholder="DN 15">
                        </div>
                        <div class="form-group col-md-3">
                            <label class="mb-1">Jenis Tanah</label>
                            <input name="jenis_tanah" class="form-control"
                                value="{{ old('jenis_tanah', $sv->jenis_tanah ?? '') }}"
                                placeholder="Berpasir / Lempung">
                        </div>
                        <div class="form-group col-md-3">
                            <label class="mb-1">Kondisi Jalan</label>
                            <input name="kondisi_jalan" class="form-control"
                                value="{{ old('kondisi_jalan', $sv->kondisi_jalan ?? '') }}"
                                placeholder="Aspal / Beton / Tanah">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label class="mb-1">Kedalaman Galian</label>
                            <div class="input-group">
                                <input name="kedalaman_galian" class="form-control" inputmode="decimal"
                                    value="{{ old('kedalaman_galian', $sv->kedalaman_galian ?? '') }}"
                                    placeholder="0.60">
                                <div class="input-group-append"><span class="input-group-text">m</span></div>
                            </div>
                        </div>
                        <div class="form-group col-md-9">
                            <label class="mb-1">Kendala Lapangan</label>
                            <input name="kendala_lapangan" class="form-control"
                                value="{{ old('kendala_lapangan', $sv->kendala_lapangan ?? '') }}"
                                placeholder="Contoh: utilitas padat, akses sempit, dll.">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="mb-1">Catatan Teknis</label>
                        <textarea name="catatan_teknis" class="form-control" rows="3"
                            placeholder="Catatan tambahan teknis (opsional)">{{ old('catatan_teknis', $sv->catatan_teknis ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-4">
                    <label class="mb-1 d-block">Terobos?</label>
                    @php
                        $valTerobos = old('terobos', is_null($sv?->terobos) ? null : (string) (int) $sv->terobos);
                    @endphp
                    <div class="btn-group btn-group-toggle" data-toggle="buttons">
                        <label class="btn btn-outline-success {{ $valTerobos === '1' ? 'active' : '' }}">
                            <input type="radio" name="terobos" value="1"
                                {{ $valTerobos === '1' ? 'checked' : '' }}> Ya
                        </label>
                        <label class="btn btn-outline-danger {{ $valTerobos === '0' ? 'active' : '' }}">
                            <input type="radio" name="terobos" value="0"
                                {{ $valTerobos === '0' ? 'checked' : '' }}> Tidak
                        </label>
                        <label class="btn btn-outline-secondary {{ is_null($valTerobos) ? 'active' : '' }}">
                            <input type="radio" name="terobos" value=""
                                onclick="this.form.querySelectorAll('input[name=terobos]').forEach(el=>el.removeAttribute('checked'));">
                            Kosongkan
                        </label>
                    </div>
                    <small class="text-muted d-block mt-1">Kosongkan jika belum ditentukan.</small>
                </div>
            </div>


            {{-- 4) VALIDASI --}}
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-white">
                    <strong><i class="fas fa-check-circle mr-1"></i> Validasi</strong>
                </div>
                <div class="card-body">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="mb-1">Disetujui Oleh</label>
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text"><i
                                            class="fas fa-user-tie"></i></span></div>
                                <input name="disetujui_oleh" class="form-control"
                                    value="{{ old('disetujui_oleh', $sv->disetujui_oleh ?? '') }}"
                                    placeholder="Nama pejabat">
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="mb-1">Tanggal Disetujui</label>
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text"><i
                                            class="far fa-calendar-check"></i></span></div>
                                <input type="datetime-local" name="disetujui_at" class="form-control"
                                    value="{{ old('disetujui_at', $fmtLocal($sv?->disetujui_at)) }}">
                            </div>
                        </div>
                        <div class="form-group col-md-2 d-flex align-items-end">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="tandai_selesai" value="1"
                                    id="doneCheck" {{ old('tandai_selesai') ? 'checked' : '' }}>
                                <label class="form-check-label" for="doneCheck">Tandai selesai</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-right">
                <button class="btn btn-primary btn-lg px-4">
                    <i class="fas fa-save"></i> Simpan
                </button>
            </div>
        </form>
    </section>

    {{-- Preview foto + helper koordinat --}}
    <script>
        (function() {
            const input = document.getElementById('lokasi_foto');
            const preview = document.getElementById('previewFoto');
            const lat = document.getElementById('latInput');
            const lng = document.getElementById('lngInput');
            const btnSwap = document.getElementById('btnSwap');
            const btnCopy = document.getElementById('btnCopy');
            const btnMaps = document.getElementById('btnMaps');

            if (input && preview) {
                input.addEventListener('change', function(e) {
                    const file = e.target.files && e.target.files[0];
                    if (!file) return;
                    const reader = new FileReader();
                    reader.onload = ev => {
                        preview.src = ev.target.result;
                    };
                    reader.readAsDataURL(file);
                });
            }

            function coordsValid() {
                const la = parseFloat(lat.value),
                    lo = parseFloat(lng.value);
                return !isNaN(la) && la >= -90 && la <= 90 && !isNaN(lo) && lo >= -180 && lo <= 180;
            }

            function updateMapsBtn() {
                if (!btnMaps) return;
                if (coordsValid()) {
                    btnMaps.removeAttribute('disabled');
                    btnMaps.setAttribute('href', 'https://www.google.com/maps?q=' + lat.value.trim() + ',' + lng.value
                        .trim());
                } else {
                    btnMaps.setAttribute('disabled', 'disabled');
                    btnMaps.removeAttribute('href');
                }
            }

            if (btnSwap && lat && lng) {
                btnSwap.addEventListener('click', () => {
                    const t = lat.value;
                    lat.value = lng.value;
                    lng.value = t;
                    updateMapsBtn();
                });
            }

            if (btnCopy && lat && lng) {
                btnCopy.addEventListener('click', async () => {
                    const text = (lat.value || '') + ',' + (lng.value || '');
                    try {
                        await navigator.clipboard.writeText(text);
                        btnCopy.classList.add('btn-success');
                        setTimeout(() => btnCopy.classList.remove('btn-success'), 800);
                    } catch (e) {
                        alert('Gagal menyalin');
                    }
                });
            }

            if (lat && lng) {
                lat.addEventListener('input', updateMapsBtn);
                lng.addEventListener('input', updateMapsBtn);
            }
        })();
    </script>
</x-perencanaan>

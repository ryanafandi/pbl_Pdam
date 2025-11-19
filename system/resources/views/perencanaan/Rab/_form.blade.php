@php
    $rab       = $row->rab ?? null;
    $details   = $rab?->details ?? collect();
    $survei    = $row->survei ?? null;
    $pengajuan = $row->pengajuan ?? null;
    $isEdit    = $rab && $rab->exists;
@endphp

{{-- ========================= --}}
{{--  IDENTITAS DOKUMEN --}}
{{-- ========================= --}}
<div class="row mb-3">

    {{-- IDENTITAS --}}
    <div class="col-md-6">
        <div class="card shadow-sm h-100 border-0">
            <div class="card-body">
                <h6 class="text-uppercase text-muted mb-3">Identitas Dokumen</h6>

                <dl class="row mb-0 small">
                    <dt class="col-sm-4">No. SPKO</dt>
                    <dd class="col-sm-8 text-monospace">{{ $row->nomor_spko }}</dd>

                    <dt class="col-sm-4">No. Pendaftaran</dt>
                    <dd class="col-sm-8 text-monospace">
                        {{ $pengajuan->no_pendaftaran ?? '-' }}
                    </dd>

                    <dt class="col-sm-4">Nama Pemohon</dt>
                    <dd class="col-sm-8">
                        {{ $pengajuan->pemohon_nama ?? ($row->pemilik_nama ?? '-') }}
                    </dd>

                    <dt class="col-sm-4">Alamat</dt>
                    <dd class="col-sm-8">
                        {{ $pengajuan->alamat_pemasangan ?? ($row->alamat ?? '-') }}
                    </dd>
                </dl>
            </div>
        </div>
    </div>

    {{-- INFO TAMBAHAN --}}
    <div class="col-md-6">
        <div class="card shadow-sm h-100 border-0">
            <div class="card-body">
                <h6 class="text-uppercase text-muted mb-3">Info Tambahan</h6>

                <dl class="row mb-0 small">
                    <dt class="col-sm-4">Kategori Tarif</dt>
                    <dd class="col-sm-8">
                        <input type="text" name="kategori_tarif" class="form-control form-control-sm"
                            value="{{ old('kategori_tarif', $rab->kategori_tarif ?? '') }}"
                            placeholder="Contoh: Rumah Tangga B">
                    </dd>

                    <dt class="col-sm-4">Terobos</dt>
                    <dd class="col-sm-8">
                        @php
                            $terobosVal = old(
                                'pemasangan_terobos',
                                $rab->pemasangan_terobos ?? ($survei?->terobos ?? 0),
                            );
                        @endphp

                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio"
                                id="terobosYa" name="pemasangan_terobos" value="1"
                                {{ $terobosVal == 1 ? 'checked' : '' }}>
                            <label class="form-check-label" for="terobosYa">Ya</label>
                        </div>

                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio"
                                id="terobosTidak" name="pemasangan_terobos" value="0"
                                {{ $terobosVal == 0 ? 'checked' : '' }}>
                            <label class="form-check-label" for="terobosTidak">Tidak</label>
                        </div>
                    </dd>

                    <dt class="col-sm-4">Biaya Pendaftaran</dt>
                    <dd class="col-sm-8">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="biaya_pendaftaran" step="0.01" min="0"
                                class="form-control"
                                value="{{ old('biaya_pendaftaran', $rab->biaya_pendaftaran ?? 0) }}">
                        </div>
                    </dd>

                    <dt class="col-sm-4">Biaya Admin</dt>
                    <dd class="col-sm-8">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="biaya_admin" step="0.01" min="0"
                                class="form-control"
                                value="{{ old('biaya_admin', $rab->biaya_admin ?? 0) }}">
                        </div>
                    </dd>

                </dl>
            </div>
        </div>
    </div>

</div>

{{-- ========================= --}}
{{--  RINGKASAN HASIL SURVEI --}}
{{-- ========================= --}}
@if ($survei)
<div class="row mb-3">
    <div class="col-md-12">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6 class="text-uppercase text-muted mb-3">
                    Ringkasan Hasil Survei
                </h6>

                <dl class="row mb-0 small">
                    <dt class="col-sm-3">Pipa Dinas</dt>
                    <dd class="col-sm-9">
                        {{ $survei->jenis_pipa_dinas ?? '-' }}
                        @if (!is_null($survei->panjang_pipa_dinas))
                            ({{ $survei->panjang_pipa_dinas }} m)
                        @endif
                    </dd>

                    <dt class="col-sm-3">Pipa Persil</dt>
                    <dd class="col-sm-9">
                        {{ $survei->jenis_pipa_persil ?? '-' }}
                        @if (!is_null($survei->panjang_pipa_persil))
                            ({{ $survei->panjang_pipa_persil }} m)
                        @endif
                    </dd>
                </dl>
            </div>
        </div>
    </div>
</div>
@endif


{{-- ========================= --}}
{{--  DETAIL RAB --}}
{{-- ========================= --}}
<div class="card shadow-sm mb-3 border-0">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <strong><i class="fas fa-list-ul"></i> Rincian RAB</strong>
        <button type="button" class="btn btn-sm btn-outline-primary" onclick="addRabRow()">
            <i class="fas fa-plus"></i> Tambah Baris
        </button>
    </div>

    <div class="card-body p-0 table-responsive">
        <table class="table table-sm table-hover mb-0" id="rabTable">
            <thead class="bg-light">
                <tr>
                    <th style="width: 140px;">Kategori</th>
                    <th>Uraian</th>
                    <th style="width: 80px;">Satuan</th>
                    <th style="width: 120px;">Volume</th>
                    <th style="width: 140px;">Harga Satuan</th>
                    <th style="width: 140px;">Jumlah</th>
                    <th></th>
                </tr>
            </thead>

            <tbody>
                {{-- ambil data dari old() jika validasi gagal --}}
                @php $oldKategori = old('kategori'); @endphp

                @if (is_array($oldKategori))
                    @foreach ($oldKategori as $idx => $kat)
                        @include('perencanaan.rab._row', [
                            'kategoriVal' => $kat,
                            'uraianVal'   => old('uraian')[$idx] ?? '',
                            'satuanVal'   => old('satuan')[$idx] ?? '',
                            'volumeVal'   => old('volume')[$idx] ?? 0,
                            'hargaVal'    => old('harga_satuan')[$idx] ?? 0,
                        ])
                    @endforeach

                @elseif ($details->count())
                    @foreach ($details as $d)
                        @include('perencanaan.rab._row', [
                            'kategoriVal' => $d->kategori,
                            'uraianVal'   => $d->uraian,
                            'satuanVal'   => $d->satuan,
                            'volumeVal'   => $d->volume,
                            'hargaVal'    => $d->harga_satuan,
                        ])
                    @endforeach

                @else
                    {{-- baris kosong default --}}
                    @include('perencanaan.rab._row', [
                        'kategoriVal' => 'pipa_dinas',
                        'uraianVal'   => '',
                        'satuanVal'   => '',
                        'volumeVal'   => 0,
                        'hargaVal'    => 0,
                    ])

                    
                @endif
            </tbody>

            <tfoot>
                <tr class="bg-light">
                    <th colspan="5" class="text-right">Subtotal Pipa Dinas</th>
                    <th><span id="subtotalDinas">0</span></th>
                    <th></th>
                </tr>
                <tr class="bg-light">
                    <th colspan="5" class="text-right">Subtotal Pipa Persil</th>
                    <th><span id="subtotalPersil">0</span></th>
                    <th></th>
                </tr>
                <tr class="bg-primary text-white">
                    <th colspan="5" class="text-right">Total RAB (otomatis)</th>
                    <th><span id="totalRab">0</span></th>
                    <th></th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<div class="text-right">
    <button type="submit" class="btn btn-primary btn-lg px-4">
        <i class="fas fa-save"></i> Simpan RAB
    </button>
</div>

@include('perencanaan.rab._script')

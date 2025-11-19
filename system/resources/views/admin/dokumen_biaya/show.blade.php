{{-- resources/views/admin/dokumen_biaya/show.blade.php --}}
<x-admin-dashboard>
    <div class="content-header">
        <div class="container-fluid">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <div>
                    <h1 class="m-0 text-primary fw-bold">
                        <i class="fas fa-file-invoice-dollar"></i> Dokumen Biaya
                    </h1>
                    <small class="text-muted">
                        Pengelolaan RNA &amp; Bukti Persetujuan Biaya Penyambungan.
                    </small>
                </div>
                <div>
                    <a href="{{ url('admin/dokumen_biaya') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">

            {{-- Flash message --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                    <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                    <i class="fas fa-exclamation-triangle mr-1"></i> {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            {{-- ERROR VALIDASI --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @php
                $status    = $row->billing_status;
                $label     = $row->billing_status_label ?? ($status ?? 'Draft Dokumen');
                $cls       = match ($status) {
                    'PAID' => 'badge-success',
                    'SENT' => 'badge-primary',
                    default => 'badge-secondary',
                };
                $isPaid    = $status === 'PAID';
                $isSent    = $status === 'SENT';
                $isLocked  = $isPaid; // kalau sudah lunas, form dikunci
            @endphp

            {{-- IDENTITAS RAB + STATUS --}}
            <div class="row mb-3">
                {{-- IDENTITAS --}}
                <div class="col-md-7">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="mb-3">Identitas RAB</h5>

                            <dl class="row mb-0 small">
                                <dt class="col-sm-4">No. RAB</dt>
                                <dd class="col-sm-8 text-monospace">{{ $row->nomor_rab ?? '-' }}</dd>

                                <dt class="col-sm-4">No. SPKO</dt>
                                <dd class="col-sm-8 text-monospace">{{ $row->spko->nomor_spko ?? '-' }}</dd>

                                <dt class="col-sm-4">No. Pendaftaran</dt>
                                <dd class="col-sm-8 text-monospace">
                                    {{ $row->spko->pengajuan->no_pendaftaran ?? '-' }}
                                </dd>

                                <dt class="col-sm-4">Nama Pelanggan</dt>
                                <dd class="col-sm-8">
                                    {{ $row->nama_pelanggan ?? ($row->spko->pengajuan->pemohon_nama ?? '-') }}
                                </dd>

                                <dt class="col-sm-4">Alamat</dt>
                                <dd class="col-sm-8">
                                    {{ $row->alamat ?? ($row->spko->pengajuan->alamat_pemasangan ?? '-') }}
                                </dd>

                                <dt class="col-sm-4">Total RAB</dt>
                                <dd class="col-sm-8 font-weight-bold">
                                    Rp {{ number_format($row->total ?? 0, 0, ',', '.') }}
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>

                {{-- STATUS BILLING --}}
                <div class="col-md-5">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="mb-3">Status Dokumen Biaya</h5>

                            <p class="mb-2">
                                Status:
                                <span class="badge {{ $cls }}">{{ $label }}</span>
                            </p>

                            <dl class="row mb-2 small">
                                <dt class="col-sm-4">Dikirim</dt>
                                <dd class="col-sm-8">
                                    {{ $row->billing_sent_at ? $row->billing_sent_at->format('d/m/Y H:i') : '-' }}
                                </dd>

                                <dt class="col-sm-4">Dibayar</dt>
                                <dd class="col-sm-8">
                                    {{ $row->billing_paid_at ? $row->billing_paid_at->format('d/m/Y H:i') : '-' }}
                                </dd>
                            </dl>

                            <small class="text-muted d-block mb-2">
                                Tagihan akan muncul di panel <strong>Kasir</strong> setelah dikirim ke pelanggan.
                                Setelah kasir menandai <strong>LUNAS</strong>, data di bawah akan terkunci.
                            </small>

                            {{-- Tombol aksi --}}
                            <div class="mt-2">
                                {{-- KIRIM KE PELANGGAN --}}
                                @if (!$isSent && !$isPaid)
                                    <form action="{{ url('admin/dokumen_biaya/' . $row->id . '/kirim') }}"
                                          method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-primary btn-sm"
                                                onclick="return confirm('Tandai dokumen ini sudah dikirim ke pelanggan & tampil di kasir?');">
                                            <i class="fas fa-paper-plane"></i> Kirim ke Pelanggan
                                        </button>
                                    </form>
                                @else
                                    <button type="button" class="btn btn-outline-secondary btn-sm" disabled>
                                        <i class="fas fa-paper-plane"></i>
                                        {{ $isPaid ? 'Sudah Lunas' : 'Sudah Dikirim' }}
                                    </button>
                                @endif

                                {{-- Info tandai lunas: dilakukan di modul kasir --}}
                                <button type="button" class="btn btn-outline-success btn-sm" disabled>
                                    <i class="fas fa-cash-register"></i>
                                    Pembayaran dicatat oleh Kasir
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- FORM RNA & PERSETUJUAN --}}
            {{-- route: POST admin/dokumen_biaya/{rab} --}}
            <form action="{{ url('admin/dokumen_biaya/' . $row->id) }}" method="POST">
                @csrf
                {{-- tidak pakai @method('PUT') karena route-nya POST --}}

                <div class="row">
                    {{-- RNA --}}
                    <div class="col-md-6 mb-3">
                        <div class="card shadow-sm">
                            <div class="card-header bg-white">
                                <strong>Rincian RNA (Rening Non Air)</strong>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label>No. RNA</label>
                                    <input type="text"
                                           name="rna_nomor"
                                           class="form-control form-control-sm @error('rna_nomor') is-invalid @enderror"
                                           value="{{ old('rna_nomor', $row->rna_nomor) }}"
                                           placeholder="Kosongkan jika ingin dibuat otomatis"
                                           {{ $isLocked ? 'readonly' : '' }}>
                                    @error('rna_nomor')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Jika dikosongkan dan disimpan, sistem akan mengisi nomor RNA otomatis
                                        (format: RNA-YYYYMM-XXXX).
                                    </small>
                                </div>

                                <div class="form-group">
                                    <label>Tanggal RNA</label>
                                    <input type="date"
                                           name="rna_tanggal"
                                           class="form-control form-control-sm @error('rna_tanggal') is-invalid @enderror"
                                           value="{{ old('rna_tanggal', optional($row->rna_tanggal)->format('Y-m-d')) }}"
                                           {{ $isLocked ? 'readonly' : '' }}>
                                    @error('rna_tanggal')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- BUKTI PERSETUJUAN --}}
                    <div class="col-md-6 mb-3">
                        <div class="card shadow-sm">
                            <div class="card-header bg-white">
                                <strong>Bukti Persetujuan Biaya Penyambungan</strong>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label>No. Persetujuan</label>
                                    <input type="text"
                                           name="persetujuan_nomor"
                                           class="form-control form-control-sm @error('persetujuan_nomor') is-invalid @enderror"
                                           value="{{ old('persetujuan_nomor', $row->persetujuan_nomor) }}"
                                           placeholder="Opsional – bisa dibuat otomatis oleh sistem"
                                           {{ $isLocked ? 'readonly' : '' }}>
                                    @error('persetujuan_nomor')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Tanggal Persetujuan</label>
                                    <input type="date"
                                           name="persetujuan_tanggal"
                                           class="form-control form-control-sm @error('persetujuan_tanggal') is-invalid @enderror"
                                           value="{{ old('persetujuan_tanggal', optional($row->persetujuan_tanggal)->format('Y-m-d')) }}"
                                           {{ $isLocked ? 'readonly' : '' }}>
                                    @error('persetujuan_tanggal')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Jatuh Tempo Pembayaran</label>
                                    <input type="date"
                                           name="jatuh_tempo"
                                           class="form-control form-control-sm @error('jatuh_tempo') is-invalid @enderror"
                                           value="{{ old('jatuh_tempo', optional($row->jatuh_tempo)->format('Y-m-d')) }}"
                                           {{ $isLocked ? 'readonly' : '' }}>
                                    @error('jatuh_tempo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- CATATAN ADMIN --}}
                <div class="card shadow-sm mb-3">
                    <div class="card-header bg-white">
                        <strong>Catatan / Keterangan</strong>
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-0">
                            <textarea name="billing_note"
                                      rows="3"
                                      class="form-control @error('billing_note') is-invalid @enderror"
                                      placeholder="Catatan tambahan terkait pembayaran (opsional)"
                                      {{ $isLocked ? 'readonly' : '' }}>{{ old('billing_note', $row->billing_note) }}</textarea>
                            @error('billing_note')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- TOMBOL SIMPAN & CETAK --}}
                <div class="d-flex justify-content-between mb-4">
                    <div>
                        {{-- Cetak RNA --}}
                        <a href="{{ url('admin/dokumen_biaya/' . $row->id . '/rna') }}"
                           target="_blank"
                           class="btn btn-outline-secondary">
                            <i class="fas fa-print"></i> Cetak RNA
                        </a>

                        {{-- Cetak Bukti Persetujuan --}}
                        <a href="{{ url('admin/dokumen_biaya/' . $row->id . '/persetujuan') }}"
                           target="_blank"
                           class="btn btn-outline-secondary">
                            <i class="fas fa-print"></i> Cetak Bukti Persetujuan
                        </a>
                    </div>

                    <div>
                        <button type="submit"
                                class="btn btn-primary btn-lg"
                                {{ $isLocked ? 'disabled' : '' }}>
                            <i class="fas fa-save"></i> Simpan Dokumen Biaya
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </section>
</x-admin-dashboard>

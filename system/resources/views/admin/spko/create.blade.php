<x-admin-dashboard>
    <div class="content-header">
        <div class="container-fluid d-flex justify-content-between align-items-center mb-2">
            <h1 class="m-0 text-primary fw-bold"><i class="fas fa-file-alt"></i> Buat SPKO</h1>
            <a href="{{ url('admin/spko') }}" class="btn btn-light"><i class="fas fa-arrow-left"></i> Kembali</a>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">

            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card shadow-sm">
                <form method="POST" action="{{ url('admin/spko') }}">
                    @csrf

                    <div class="card-body">
                        <div class="row">
                            {{-- KOLUM KIRI --}}
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Pengajuan (APPROVED) <span class="text-danger">*</span></label>
                                    <select name="pengajuan_id" class="form-control" required>
                                        <option value="">-- pilih --</option>
                                        @foreach ($pengajuan as $p)
                                            <option value="{{ $p->id }}" data-no="{{ e($p->no_pendaftaran) }}"
                                                data-nama="{{ e($p->pemohon_nama) }}"
                                                data-alamat="{{ e($p->alamat_pemasangan) }}"
                                                {{ old('pengajuan_id', $selected) == $p->id ? 'selected' : '' }}>
                                                {{ $p->no_pendaftaran }} — {{ $p->pemohon_nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted d-block">Hanya pengajuan berstatus
                                        <strong>APPROVED</strong> (disarankan belum punya SPKO).</small>
                                </div>

                                <div class="form-group">
                                    <label>Nomor SPKO</label>
                                    <input type="text" class="form-control" value="(otomatis saat disimpan)"
                                        readonly>
                                </div>

                                <div class="form-group">
                                    <label>Tanggal SPKO <span class="text-danger">*</span></label>
                                    <input type="date" name="tanggal_spko" class="form-control"
                                        value="{{ old('tanggal_spko', now()->toDateString()) }}" required>
                                </div>

                                <div class="form-group">
                                    <label>Tujuan <span class="text-danger">*</span></label>
                                    <input type="text" name="tujuan" class="form-control"
                                        value="{{ old('tujuan', 'PEMASANGAN SAMBUNGAN BARU') }}" required>
                                </div>

                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i>
                                    Nama & Alamat akan <strong>diambil otomatis</strong> dari data pengajuan terpilih
                                    dan
                                    tetap <strong>dipastikan</strong> oleh server saat disimpan.
                                </div>
                            </div>

                            {{-- KOLUM KANAN --}}
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Nama Pemohon (auto)</label>
                                    <input type="text" name="pemilik_nama" class="form-control"
                                        value="{{ old('pemilik_nama') }}" readonly>
                                </div>

                                <div class="form-group">
                                    <label>Alamat (auto)</label>
                                    <input type="text" name="alamat" class="form-control"
                                        value="{{ old('alamat') }}" readonly>
                                </div>

                                <div class="form-group">
                                    <label>Kepada (Jabatan)</label>
                                    <input type="text" name="kepada_jabatan" class="form-control"
                                        value="{{ old('kepada_jabatan', 'Tim Perencanaan') }}">
                                </div>

                                {{-- Preview pengajuan terpilih --}}
                                <div class="border rounded p-3 bg-light">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-id-card mr-2 text-primary"></i>
                                        <strong class="mb-0">Ringkas Pengajuan</strong>
                                    </div>
                                    <dl class="row mb-0">
                                        <dt class="col-sm-4">No. Daftar</dt>
                                        <dd class="col-sm-8" id="pv-no">-</dd>
                                        <dt class="col-sm-4">Nama</dt>
                                        <dd class="col-sm-8" id="pv-nama">-</dd>
                                        <dt class="col-sm-4">Alamat</dt>
                                        <dd class="col-sm-8" id="pv-alamat">-</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="form-group">
                            <label>Laporan Hasil Opname</label>
                            <textarea name="laporan_ringkas" class="form-control" rows="4">{{ old('laporan_ringkas') }}</textarea>
                        </div>

                        {{-- <div class="form-group">
                            <label>Terobos?</label><br>
                            <label class="mr-3">
                                <input type="radio" name="terobos" value="1"
                                    {{ old('terobos') === '1' ? 'checked' : '' }}> Ya
                            </label>
                            <label>
                                <input type="radio" name="terobos" value="0"
                                    {{ old('terobos', '0') === '0' ? 'checked' : '' }}> Tidak
                            </label>
                        </div> --}}

                        {{-- <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Disurvey oleh (Nama)</label>
                                    <input type="text" name="disurvey_oleh_nama" class="form-control"
                                        value="{{ old('disurvey_oleh_nama') }}">
                                </div>
                                <div class="form-group">
                                    <label>Disurvey oleh (NIPP/NIK)</label>
                                    <input type="text" name="disurvey_oleh_nipp" class="form-control"
                                        value="{{ old('disurvey_oleh_nipp') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Kabag Teknik (Nama)</label>
                                    <input type="text" name="kabag_teknik_nama" class="form-control"
                                        value="{{ old('kabag_teknik_nama') }}">
                                </div>
                                <div class="form-group">
                                    <label>Kabag Teknik (NIPP)</label>
                                    <input type="text" name="kabag_teknik_nipp" class="form-control"
                                        value="{{ old('kabag_teknik_nipp') }}">
                                </div>
                            </div>
                        </div> --}}
                    </div>

                    <div class="card-footer text-right">
                        <button class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    {{-- Auto-fill Nama & Alamat dari pilihan Pengajuan --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sel = document.querySelector('select[name="pengajuan_id"]');
            const iNama = document.querySelector('input[name="pemilik_nama"]');
            const iAlmt = document.querySelector('input[name="alamat"]');
            const pvNo = document.getElementById('pv-no');
            const pvNama = document.getElementById('pv-nama');
            const pvAlmt = document.getElementById('pv-alamat');

            function applyFromSelected() {
                const opt = sel.options[sel.selectedIndex];
                if (!opt || !opt.value) {
                    iNama.value = '';
                    iAlmt.value = '';
                    pvNo.textContent = '-';
                    pvNama.textContent = '-';
                    pvAlmt.textContent = '-';
                    return;
                }
                const no = opt.getAttribute('data-no') || '';
                const nama = opt.getAttribute('data-nama') || '';
                const alamat = opt.getAttribute('data-alamat') || '';

                iNama.value = nama;
                iAlmt.value = alamat;

                pvNo.textContent = no || '-';
                pvNama.textContent = nama || '-';
                pvAlmt.textContent = alamat || '-';
            }

            sel.addEventListener('change', applyFromSelected);
            applyFromSelected(); // jalankan saat pertama load (untuk preselect)
        });
    </script>
</x-admin-dashboard>

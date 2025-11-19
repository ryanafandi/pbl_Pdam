{{-- resources/views/admin/spk/create.blade.php --}}
<x-admin-dashboard>
  <div class="content-header">
    <div class="container-fluid">
      <div class="d-flex align-items-center justify-content-between mb-2">
        <div>
          <h1 class="m-0 text-primary fw-bold">
            <i class="fas fa-file-signature"></i> Buat SPK
          </h1>
          <small class="text-muted">
            Membuat Surat Perintah Kerja dari RAB yang sudah LUNAS.
          </small>
        </div>
        <div>
          <a href="{{ url('admin/dokumen_biaya') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali ke Dokumen Biaya
          </a>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">

      @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
      @endif

      @if($errors->any())
        <div class="alert alert-danger">
          <ul class="mb-0">
            @foreach($errors->all() as $err)
              <li>{{ $err }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <div class="row">
        {{-- Identitas RAB/Pelanggan --}}
        <div class="col-md-6 mb-3">
          <div class="card shadow-sm">
            <div class="card-body">
              <h5 class="mb-3">Informasi RAB & Pelanggan</h5>
              @php
                $spko      = $rab->spko ?? null;
                $pengajuan = $spko->pengajuan ?? null;
              @endphp

              <dl class="row small mb-0">
                <dt class="col-sm-4">No. RAB</dt>
                <dd class="col-sm-8 text-monospace">{{ $rab->nomor_rab ?? '-' }}</dd>

                <dt class="col-sm-4">No. SPKO</dt>
                <dd class="col-sm-8 text-monospace">{{ $spko->nomor_spko ?? '-' }}</dd>

                <dt class="col-sm-4">No. Pendaftaran</dt>
                <dd class="col-sm-8 text-monospace">{{ $pengajuan->no_pendaftaran ?? '-' }}</dd>

                <dt class="col-sm-4">Nama Pelanggan</dt>
                <dd class="col-sm-8">
                  {{ $rab->nama_pelanggan ?? ($pengajuan->pemohon_nama ?? '-') }}
                </dd>

                <dt class="col-sm-4">Alamat</dt>
                <dd class="col-sm-8">
                  {{ $rab->alamat ?? ($pengajuan->alamat_pemasangan ?? '-') }}
                </dd>

                <dt class="col-sm-4">Total RAB</dt>
                <dd class="col-sm-8 font-weight-bold">
                  Rp {{ number_format($rab->total ?? 0, 0, ',', '.') }}
                </dd>

                <dt class="col-sm-4">Status Tagihan</dt>
                <dd class="col-sm-8">
                  <span class="badge badge-success">LUNAS</span>
                </dd>
              </dl>
            </div>
          </div>
        </div>

        {{-- Form SPK --}}
        <div class="col-md-6 mb-3">
          <div class="card shadow-sm">
            <div class="card-body">
              <h5 class="mb-3">Form SPK</h5>

              <form action="{{ url('admin/spk/store/'.$rab->id) }}" method="POST">
                @csrf

                <div class="form-group">
                  <label>No. SPK</label>
                  <input type="text"
                         name="nomor_spk"
                         class="form-control form-control-sm @error('nomor_spk') is-invalid @enderror"
                         value="{{ old('nomor_spk') }}"
                         placeholder="Kosongkan jika ingin dibuat otomatis">
                  @error('nomor_spk')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="form-group">
                  <label>Pekerjaan</label>
                  <input type="text"
                         name="pekerjaan"
                         class="form-control form-control-sm @error('pekerjaan') is-invalid @enderror"
                         value="{{ old('pekerjaan', 'Pemasangan Sambungan Baru') }}">
                  @error('pekerjaan')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="form-group">
                  <label>Nama Pelanggan</label>
                  <input type="text"
                         name="nama_pelanggan"
                         class="form-control form-control-sm @error('nama_pelanggan') is-invalid @enderror"
                         value="{{ old('nama_pelanggan', $rab->nama_pelanggan ?? ($pengajuan->pemohon_nama ?? '')) }}">
                  @error('nama_pelanggan')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="form-group">
                  <label>Alamat</label>
                  <textarea name="alamat"
                            rows="2"
                            class="form-control form-control-sm @error('alamat') is-invalid @enderror">{{ old('alamat', $rab->alamat ?? ($pengajuan->alamat_pemasangan ?? '')) }}</textarea>
                  @error('alamat')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="form-group">
                  <label>Lokasi / Kelurahan / Area Kerja</label>
                  <input type="text"
                         name="lokasi"
                         class="form-control form-control-sm @error('lokasi') is-invalid @enderror"
                         value="{{ old('lokasi') }}"
                         placeholder="Contoh: Kel. Sungai Jawi / Zona Barat">
                  @error('lokasi')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="form-group">
                  <label>No. Pelanggan / No. Pendaftaran</label>
                  <input type="text"
                         name="no_pelanggan"
                         class="form-control form-control-sm @error('no_pelanggan') is-invalid @enderror"
                         value="{{ old('no_pelanggan', $pengajuan->no_pendaftaran ?? '') }}">
                  @error('no_pelanggan')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="form-group mb-0">
                  <label>Catatan</label>
                  <textarea name="catatan"
                            rows="2"
                            class="form-control form-control-sm @error('catatan') is-invalid @enderror"
                            placeholder="Catatan tambahan (opsional)">{{ old('catatan') }}</textarea>
                  @error('catatan')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="mt-3 text-right">
                  <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan & Buat SPK
                  </button>
                </div>
              </form>

            </div>
          </div>
        </div>
      </div>

    </div>
  </section>
</x-admin-dashboard>

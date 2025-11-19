{{-- resources/views/admin/spk/edit.blade.php
<x-admin-dashboard>
  <div class="content-header">
    <div class="container-fluid">
      <div class="d-flex align-items-center justify-content-between mb-2">
        <div>
          <h1 class="m-0 text-primary fw-bold">
            <i class="fas fa-edit"></i> Edit SPK
          </h1>
          <small class="text-muted">
            Mengubah data Surat Perintah Kerja.
          </small>
        </div>
        <div>
          <a href="{{ url('admin/spk/'.$row->id) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
          </a>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">

      @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif
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

      <div class="card shadow-sm">
        <div class="card-body">
          <form action="{{ url('admin/spk/'.$row->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>No. SPK</label>
                  <input type="text"
                         name="nomor_spk"
                         class="form-control form-control-sm @error('nomor_spk') is-invalid @enderror"
                         value="{{ old('nomor_spk', $row->nomor_spk) }}">
                  @error('nomor_spk')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="form-group">
                  <label>Pekerjaan</label>
                  <input type="text"
                         name="pekerjaan"
                         class="form-control form-control-sm @error('pekerjaan') is-invalid @enderror"
                         value="{{ old('pekerjaan', $row->pekerjaan) }}">
                  @error('pekerjaan')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="form-group">
                  <label>Nama Pelanggan</label>
                  <input type="text"
                         name="nama_pelanggan"
                         class="form-control form-control-sm @error('nama_pelanggan') is-invalid @enderror"
                         value="{{ old('nama_pelanggan', $row->nama_pelanggan) }}">
                  @error('nama_pelanggan')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group">
                  <label>Alamat</label>
                  <textarea name="alamat"
                            rows="2"
                            class="form-control form-control-sm @error('alamat') is-invalid @enderror">{{ old('alamat', $row->alamat) }}</textarea>
                  @error('alamat')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="form-group">
                  <label>Lokasi / Area</label>
                  <input type="text"
                         name="lokasi"
                         class="form-control form-control-sm @error('lokasi') is-invalid @enderror"
                         value="{{ old('lokasi', $row->lokasi) }}">
                  @error('lokasi')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="form-group">
                  <label>No. Pelanggan</label>
                  <input type="text"
                         name="no_pelanggan"
                         class="form-control form-control-sm @error('no_pelanggan') is-invalid @enderror"
                         value="{{ old('no_pelanggan', $row->no_pelanggan) }}">
                  @error('no_pelanggan')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>
            </div>

            <div class="form-group">
              <label>Catatan</label>
              <textarea name="catatan"
                        rows="3"
                        class="form-control form-control-sm @error('catatan') is-invalid @enderror">{{ old('catatan', $row->catatan) }}</textarea>
              @error('catatan')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="text-right">
              <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Simpan Perubahan
              </button>
            </div>
          </form>
        </div>
      </div>

    </div>
  </section>
</x-admin-dashboard> --}}

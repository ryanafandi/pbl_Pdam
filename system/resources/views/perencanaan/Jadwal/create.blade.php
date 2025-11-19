<x-perencanaan>
  <div class="content-header d-flex align-items-center justify-content-between mb-2">
    <div>
      <h1 class="m-0">Buat Jadwal Survei</h1>
      <small class="text-muted">SPKO: <span class="text-monospace">{{ $row->nomor_spko }}</span></small>
    </div>
    <a href="{{ url('perencanaan/jadwal?spko='.$row->id) }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
  </div>

  <section class="content">
    @if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif

    <div class="card">
      <div class="card-body">
        <form action="{{ url('perencanaan/jadwal') }}" method="POST">
          @csrf
          <input type="hidden" name="spko_id" value="{{ $row->id }}">

          <div class="form-row">
            <div class="form-group col-md-4">
              <label>Tanggal</label>
              <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror" value="{{ old('tanggal') }}" required>
              @error('tanggal') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="form-group col-md-3">
              <label>Jam</label>
              <input type="time" name="jam" class="form-control @error('jam') is-invalid @enderror" value="{{ old('jam') }}" required>
              @error('jam') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-md-5">
              <label>Petugas Survei</label>
              <input type="text" name="petugas_nama" class="form-control @error('petugas_nama') is-invalid @enderror" value="{{ old('petugas_nama') }}" required>
              @error('petugas_nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="form-group col-md-3">
              <label>NIPP</label>
              <input type="text" name="petugas_nipp" class="form-control @error('petugas_nipp') is-invalid @enderror" value="{{ old('petugas_nipp') }}">
              @error('petugas_nipp') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
          </div>

          <div class="form-group">
            <label>Catatan</label>
            <textarea name="catatan" rows="3" class="form-control @error('catatan') is-invalid @enderror" placeholder="Catatan tambahan (opsional)">{{ old('catatan') }}</textarea>
            @error('catatan') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="text-right">
            <button class="btn btn-primary"><i class="fas fa-save"></i> Simpan Jadwal</button>
          </div>
        </form>
      </div>
    </div>
  </section>
</x-perencanaan>

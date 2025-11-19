<x-perencanaan>
  <div class="content-header d-flex align-items-center justify-content-between mb-2">
    <div>
      <h1 class="m-0">Ubah Jadwal Survei</h1>
      <small class="text-muted">
        SPKO: <span class="text-monospace">{{ $row->nomor_spko }}</span>
      </small>
    </div>

    <div class="d-flex gap-2">
      {{-- Form HAPUS (BERDIRI SENDIRI) --}}
      <form action="{{ url('perencanaan/jadwal/'.$row->id) }}" method="POST"
            onsubmit="return confirm('Hapus jadwal survei untuk {{ $row->nomor_spko }} ?')"
            class="mr-2">
        @csrf @method('DELETE')
        <button class="btn btn-outline-danger">
          <i class="fas fa-trash"></i> Hapus Jadwal
        </button>
      </form>

      <a href="{{ url('perencanaan/jadwal?spko='.$row->id) }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
      </a>
    </div>
  </div>

  <section class="content">
    @if(session('error'))   <div class="alert alert-danger">{{ session('error') }}</div> @endif
    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif

    <div class="card shadow-sm">
      <div class="card-body">

        {{-- Form SIMPAN (HANYA SATU FORM) --}}
        <form action="{{ url('perencanaan/jadwal/'.$row->id) }}" method="POST" novalidate>
          @csrf @method('PUT')

          <div class="form-row">
            <div class="form-group col-md-4">
              <label class="font-weight-semibold">Tanggal</label>
              <input
                type="date"
                name="tanggal"
                class="form-control @error('tanggal') is-invalid @enderror"
                value="{{ old('tanggal', optional($row->survey_scheduled_at)->format('Y-m-d')) }}"
                required
              >
              @error('tanggal') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="form-group col-md-3">
              <label class="font-weight-semibold">Jam</label>
              <input
                type="time"
                name="jam"
                class="form-control @error('jam') is-invalid @enderror"
                value="{{ old('jam', optional($row->survey_scheduled_at)->format('H:i')) }}"
                required
              >
              @error('jam') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-md-5">
              <label class="font-weight-semibold">Petugas Survei</label>
              <input
                type="text"
                name="petugas_nama"
                class="form-control @error('petugas_nama') is-invalid @enderror"
                value="{{ old('petugas_nama', $row->disurvey_oleh_nama) }}"
                required
              >
              @error('petugas_nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="form-group col-md-3">
              <label class="font-weight-semibold">NIPP</label>
              <input
                type="text"
                name="petugas_nipp"
                class="form-control @error('petugas_nipp') is-invalid @enderror"
                value="{{ old('petugas_nipp', $row->disurvey_oleh_nipp) }}"
              >
              @error('petugas_nipp') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
          </div>

          <div class="form-group">
            <label class="font-weight-semibold">Catatan</label>
            <textarea
              name="catatan"
              rows="3"
              class="form-control @error('catatan') is-invalid @enderror"
              placeholder="Catatan tambahan (opsional)"
            >{{ old('catatan', $row->catatan) }}</textarea>
            @error('catatan') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="text-right">
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-save"></i> Simpan Perubahan
            </button>
          </div>
        </form>

      </div>
    </div>
  </section>
</x-perencanaan>

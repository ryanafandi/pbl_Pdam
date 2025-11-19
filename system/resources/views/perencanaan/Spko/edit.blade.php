{{-- resources/views/perencanaan/spko/edit.blade.php --}}
<x-perencanaan>
  <div class="content-header">
    <div class="container-fluid">
      <div class="d-flex align-items-center justify-content-between mb-2">
        <div>
          <h1 class="m-0 text-primary fw-bold">
            <i class="far fa-calendar-check"></i> Atur Jadwal Survei
          </h1>
          <small class="text-muted">
            SPKO: {{ $row->nomor_spko }}
          </small>
        </div>

        <a href="{{ url('perencanaan/spko/'.$row->id) }}" class="btn btn-secondary">
          <i class="fas fa-arrow-left"></i> Kembali
        </a>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">

      {{-- tampilkan error validasi supaya kelihatan kalau gagal --}}
      @if($errors->any())
        <div class="alert alert-danger">
          <strong>Terjadi kesalahan:</strong>
          <ul class="mb-0">
            @foreach($errors->all() as $err)
              <li>{{ $err }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif

      <div class="card shadow-sm">
        <div class="card-body">

          <form method="POST" action="{{ url('perencanaan/spko/'.$row->id.'/edit-jadwal') }}">
            @csrf
            @method('PUT')

            <div class="form-row">
              {{-- Tanggal Survei --}}
              <div class="form-group col-md-4">
                <label for="tanggal_survei">Tanggal Survei</label>
                <input type="date"
                       id="tanggal_survei"
                       name="tanggal_survei"
                       class="form-control @error('tanggal_survei') is-invalid @enderror"
                       value="{{ old('tanggal_survei', optional($row->survey_scheduled_at)->format('Y-m-d')) }}">
                @error('tanggal_survei')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              {{-- Jam --}}
              <div class="form-group col-md-4">
                <label for="jam_survei">Jam</label>
                <input type="time"
                       id="jam_survei"
                       name="jam_survei"
                       class="form-control @error('jam_survei') is-invalid @enderror"
                       value="{{ old('jam_survei', optional($row->survey_scheduled_at)->format('H:i')) }}">
                @error('jam_survei')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <div class="form-row">
              {{-- Petugas --}}
              <div class="form-group col-md-6">
                <label for="petugas">Petugas Survei</label>
                <input type="text"
                       id="petugas"
                       name="petugas"
                       class="form-control @error('petugas') is-invalid @enderror"
                       value="{{ old('petugas', $row->disurvey_oleh_nama) }}"
                       placeholder="Nama petugas survei">
                @error('petugas')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              {{-- NIPP --}}
              <div class="form-group col-md-6">
                <label for="nipp">NIPP</label>
                <input type="text"
                       id="nipp"
                       name="nipp"
                       class="form-control @error('nipp') is-invalid @enderror"
                       value="{{ old('nipp', $row->disurvey_oleh_nipp) }}"
                       placeholder="Nomor induk / NIPP">
                @error('nipp')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>

            {{-- Catatan --}}
            <div class="form-group">
              <label for="catatan">Catatan</label>
              <textarea id="catatan"
                        name="catatan"
                        class="form-control @error('catatan') is-invalid @enderror"
                        rows="3"
                        placeholder="Catatan tambahan (opsional)">{{ old('catatan', optional($row->survei)->catatan) }}</textarea>
              @error('catatan')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="text-right">
              <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Simpan Jadwal
              </button>
            </div>

          </form>

        </div>
      </div>
    </div>
  </section>
</x-perencanaan>

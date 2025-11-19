<x-perencanaan>

  <div class="content-header d-flex align-items-center justify-content-between mb-2">
    <div>
      <h1 class="m-0"><i class="fas fa-plus"></i> Buat Survei</h1>
      <small class="text-muted">Buat catatan survei untuk SPKO tertentu</small>
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
          @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
        </ul>
      </div>
    @endif

    <div class="card shadow-sm">
      <div class="card-body">
        {{-- Contoh minimal: kamu bisa tambahkan select SPKO/field lain sesuai kebutuhan --}}
        <form action="{{ url('perencanaan/survei') }}" method="POST" enctype="multipart/form-data">
          @csrf

          <div class="alert alert-info">
            *Jika alurmu selalu melalui tombol <b>Input / Edit</b> di daftar SPKO, file ini tidak wajib dipakai.
          </div>

          <div class="form-group">
            <label>ID SPKO</label>
            <input type="number" name="spko_id" class="form-control" value="{{ old('spko_id') }}" placeholder="Masukkan ID SPKO">
          </div>

          <div class="form-row">
            <div class="form-group col-md-4">
              <label>Jadwal</label>
              <input type="datetime-local" name="scheduled_at" class="form-control" value="{{ old('scheduled_at') }}">
            </div>
            <div class="form-group col-md-4">
              <label>Petugas</label>
              <input type="text" name="petugas_nama" class="form-control" value="{{ old('petugas_nama') }}">
            </div>
            <div class="form-group col-md-4">
              <label>NIPP</label>
              <input type="text" name="petugas_nipp" class="form-control" value="{{ old('petugas_nipp') }}">
            </div>
          </div>

          <div class="text-right">
            <button class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </section>

</x-perencanaan>

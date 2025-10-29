<x-backend>
  <div class="card shadow-lg border-0 rounded-3 mt-4">
    <div class="card-header text-white" 
         style="background: linear-gradient(90deg, #1976d2, #2196f3);">
      <h3 class="card-title mb-0">
        <i class="fas fa-file-alt"></i> Form Pengajuan Saluran Baru
      </h3>
    </div>

    <form action="{{ url('backend/Pengajuan') }}" method="POST" enctype="multipart/form-data" class="p-4">
      @csrf

      <div class="row">
        <div class="col-md-6">
          <div class="form-group mb-3">
            <label class="fw-semibold">Nama Lengkap</label>
            <input type="text" class="form-control form-control-lg" name="nama" placeholder="Masukkan nama lengkap">
          </div>

          <div class="form-group mb-3">
            <label class="fw-semibold">NIK</label>
            <input type="text" class="form-control form-control-lg" name="nik" placeholder="Masukkan NIK">
          </div>

          <div class="form-group mb-3">
            <label class="fw-semibold">Email</label>
            <input type="email" class="form-control form-control-lg" name="email" placeholder="Masukkan email aktif">
          </div>

          <div class="form-group mb-3">
            <label class="fw-semibold">No Handphone</label>
            <input type="text" class="form-control form-control-lg" name="no_handphone" placeholder="Nomor handphone aktif">
          </div>

          <div class="form-group mb-3">
            <label class="fw-semibold">Alamat</label>
            <input type="text" class="form-control form-control-lg" name="alamat" placeholder="Masukkan alamat lengkap">
          </div>

          <div class="form-group mb-3">
            <label class="fw-semibold">Kecamatan</label>
            <input type="text" class="form-control form-control-lg" name="kecamatan" placeholder="Masukkan kecamatan">
          </div>

          <div class="form-group mb-3">
            <label class="fw-semibold">Kelurahan</label>
            <input type="text" class="form-control form-control-lg" name="kelurahan" placeholder="Masukkan kelurahan">
          </div>
        </div>

        <div class="col-md-6">
          <div class="form-group mb-3">
            <label class="fw-semibold">RT</label>
            <input type="text" class="form-control form-control-lg" name="rt" placeholder="RT">
          </div>

          <div class="form-group mb-3">
            <label class="fw-semibold">RW</label>
            <input type="text" class="form-control form-control-lg" name="rw" placeholder="RW">
          </div>

          <div class="form-group mb-3">
            <label class="fw-semibold">Pekerjaan</label>
            <input type="text" class="form-control form-control-lg" name="pekerjaan" placeholder="Masukkan pekerjaan">
          </div>

          <div class="form-group mb-3">
            <label class="fw-semibold">Scan KTP (PDF)</label>
            <input type="file" class="form-control form-control-lg" name="ktp" accept=".pdf">
          </div>

          <div class="form-group mb-3">
            <label class="fw-semibold">Scan KK (PDF)</label>
            <input type="file" class="form-control form-control-lg" name="kk" accept=".pdf">
          </div>

          <div class="form-group mb-3">
            <label class="fw-semibold">Scan Surat Permohonan (PDF)</label>
            <input type="file" class="form-control form-control-lg" name="surat_permohonan" accept=".pdf">
          </div>

          <div class="form-group mb-4">
            <label class="fw-semibold">Foto Rumah (JPG/PNG)</label>
            <input type="file" class="form-control form-control-lg" name="foto_rumah" accept=".jpg, .png, .jpeg">
          </div>
        </div>
      </div>

      <div class="text-end">
        <button type="submit" class="btn btn-lg text-white px-5" 
                style="background: linear-gradient(90deg, #2196f3, #42a5f5); border-radius: 8px;">
          <i class="fas fa-paper-plane"></i> Kirim Pengajuan
        </button>
      </div>
    </form>
  </div>
</x-backend>

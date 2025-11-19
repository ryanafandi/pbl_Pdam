<x-backend>
  <div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between mb-3">
      <h4 class="mb-0 text-primary">
        <i class="fas fa-file-signature mr-2"></i> Form Pengajuan Pendaftaran Baru
      </h4>
      <a href="{{ url('backend/Pengajuan') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
      </a>
    </div>

    {{-- Alert sukses/eror --}}
    @if(session('success'))
      <div class="alert alert-success shadow-sm"><i class="fas fa-check-circle mr-1"></i>{{ session('success') }}</div>
    @endif
    @if($errors->any())
      <div class="alert alert-danger shadow-sm">
        <i class="fas fa-exclamation-triangle mr-2"></i>Periksa isian di bawah ini.
      </div>
    @endif

    <form action="{{ url('backend/Pengajuan') }}" method="POST" enctype="multipart/form-data" id="formPengajuan">
      @csrf

      {{-- 1) DATA PEMOHON --}}
      <div class="card card-outline card-primary mb-3">
        <div class="card-header py-2">
          <strong><i class="fas fa-user mr-1"></i> Data Pemohon</strong>
        </div>
        <div class="card-body">

          <div class="form-row">
            <div class="form-group col-md-6">
              <label>Nama Lengkap Pemohon <span class="text-danger">*</span></label>
              <div class="input-group">
                <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-id-card"></i></span></div>
                <input type="text" name="pemohon_nama" class="form-control @error('pemohon_nama') is-invalid @enderror"
                       value="{{ old('pemohon_nama', auth()->user()->nama ?? '') }}" required maxlength="255" placeholder="Nama sesuai KTP">
                @error('pemohon_nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>
            </div>

            <div class="form-group col-md-6">
              <label>Nomor Telepon/HP <span class="text-danger">*</span></label>
              <div class="input-group">
                <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-phone"></i></span></div>
                <input type="text" name="nomor_telepon" class="form-control @error('nomor_telepon') is-invalid @enderror"
                       value="{{ old('nomor_telepon') }}" required maxlength="30" placeholder="08xxxxxxxxxx">
                @error('nomor_telepon') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>
              <small class="text-muted">Pastikan nomor aktif untuk dihubungi petugas survei.</small>
            </div>
          </div>

          <div class="form-group">
            <label>Alamat Rumah yang Akan Dipasang <span class="text-danger">*</span></label>
            <textarea name="alamat_pemasangan" rows="2" class="form-control @error('alamat_pemasangan') is-invalid @enderror" required placeholder="Nama jalan, RT/RW, kelurahan/kecamatan">{{ old('alamat_pemasangan') }}</textarea>
            @error('alamat_pemasangan') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="form-row">
            <div class="form-group col-md-6">
              <label>Pekerjaan</label>
              <input type="text" name="pekerjaan" class="form-control @error('pekerjaan') is-invalid @enderror" value="{{ old('pekerjaan') }}" maxlength="100" placeholder="Opsional">
              @error('pekerjaan') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="form-group col-md-3">
              <label>Penghuni Tetap</label>
              <div class="input-group">
                <input type="number" min="0" name="penghuni_tetap" class="form-control @error('penghuni_tetap') is-invalid @enderror" value="{{ old('penghuni_tetap', 0) }}">
                <div class="input-group-append"><span class="input-group-text">org</span></div>
                @error('penghuni_tetap') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
              </div>
            </div>
            <div class="form-group col-md-3">
              <label>Penghuni Tidak Tetap</label>
              <div class="input-group">
                <input type="number" min="0" name="penghuni_tidak_tetap" class="form-control @error('penghuni_tidak_tetap') is-invalid @enderror" value="{{ old('penghuni_tidak_tetap', 0) }}">
                <div class="input-group-append"><span class="input-group-text">org</span></div>
                @error('penghuni_tidak_tetap') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
              </div>
            </div>
          </div>

          <div class="form-group mb-0">
            <label>Email (opsional)</label>
            <div class="input-group">
              <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-envelope"></i></span></div>
              <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                     value="{{ old('email', auth()->user()->email ?? '') }}" maxlength="120" placeholder="contoh@mail.com">
              @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
          </div>

        </div>
      </div>

      {{-- 2) PERUNTUKAN & KEBUTUHAN --}}
      <div class="card card-outline card-primary mb-3">
        <div class="card-header py-2">
          <strong><i class="fas fa-home mr-1"></i> Peruntukan & Kebutuhan</strong>
        </div>
        <div class="card-body">

          <div class="form-row">
            <div class="form-group col-md-6">
              <label>Peruntukan Rumah <span class="text-danger">*</span></label>
              <select name="peruntukan" class="form-control @error('peruntukan') is-invalid @enderror" required>
                <option value="" disabled {{ old('peruntukan') ? '' : 'selected' }}>— pilih —</option>
                @foreach(['Perusahaan','Kantor','Tempat Tinggal','Asrama','Penginapan/Hotel','Pabrik','Rumah Makan','Poliklinik','Rumah Sakit','Rumah Ibadah','Panti Asuhan'] as $opt)
                  <option value="{{ $opt }}" {{ old('peruntukan')==$opt ? 'selected':'' }}>{{ $opt }}</option>
                @endforeach
              </select>
              @error('peruntukan') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="form-group col-md-3">
              <label>Jumlah Kran Dipasang</label>
              <div class="input-group">
                <input type="number" min="0" name="jumlah_kran" class="form-control @error('jumlah_kran') is-invalid @enderror" value="{{ old('jumlah_kran', 0) }}">
                <div class="input-group-append"><span class="input-group-text">titik</span></div>
                @error('jumlah_kran') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
              </div>
            </div>

            <div class="form-group col-md-3">
              <label>Berlangganan ke orang lain?</label>
              <div class="d-flex align-items-center h-100">
                <div class="custom-control custom-radio mr-3">
                  <input class="custom-control-input" id="rYa" type="radio" name="langganan_ke_orang_lain" value="Ya" {{ old('langganan_ke_orang_lain')=='Ya'?'checked':'' }}>
                  <label class="custom-control-label" for="rYa">Ya</label>
                </div>
                <div class="custom-control custom-radio">
                  <input class="custom-control-input" id="rTidak" type="radio" name="langganan_ke_orang_lain" value="Tidak" {{ old('langganan_ke_orang_lain','Tidak')=='Tidak'?'checked':'' }}>
                  <label class="custom-control-label" for="rTidak">Tidak</label>
                </div>
              </div>
            </div>
          </div>

          <div class="form-group mb-0">
            <label>Nama Pelanggan PDAM Terdekat</label>
            <input type="text" name="pelanggan_terdekat" class="form-control @error('pelanggan_terdekat') is-invalid @enderror" value="{{ old('pelanggan_terdekat') }}" placeholder="Opsional">
            @error('pelanggan_terdekat') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

        </div>
      </div>

      {{-- 3) LAMPIRAN DOKUMEN --}}
      <div class="card card-outline card-primary mb-3">
        <div class="card-header py-2">
          <strong><i class="fas fa-paperclip mr-1"></i> Lampiran Dokumen</strong>
        </div>
        <div class="card-body">

          <div class="form-row">
            <div class="form-group col-md-6">
              <label>Foto KTP / Identitas <span class="text-danger">*</span></label>
              <div class="custom-file">
                <input type="file" name="lampiran_ktp" id="lampiran_ktp" class="custom-file-input @error('lampiran_ktp') is-invalid @enderror" accept=".jpg,.jpeg,.png,.pdf" required>
                <label class="custom-file-label" for="lampiran_ktp">Pilih berkas...</label>
                @error('lampiran_ktp') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
              </div>
              <small class="text-muted">JPG/PNG/PDF, maks. 2MB.</small>
            </div>

            <div class="form-group col-md-6">
              <label>Foto KK / Dokumen Pendukung</label>
              <div class="custom-file">
                <input type="file" name="lampiran_kk" id="lampiran_kk" class="custom-file-input @error('lampiran_kk') is-invalid @enderror" accept=".jpg,.jpeg,.png,.pdf">
                <label class="custom-file-label" for="lampiran_kk">Pilih berkas (opsional)...</label>
                @error('lampiran_kk') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
              </div>
              <small class="text-muted">JPG/PNG/PDF, maks. 2MB.</small>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-md-6">
              <label>Foto Rumah (tampak depan)</label>
              <div class="custom-file">
                <input type="file" name="foto_rumah" id="foto_rumah" class="custom-file-input @error('foto_rumah') is-invalid @enderror" accept=".jpg,.jpeg,.png">
                <label class="custom-file-label" for="foto_rumah">Pilih foto (opsional)...</label>
                @error('foto_rumah') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
              </div>
              <small class="text-muted d-block mb-2">JPG/PNG, maks. 2MB.</small>
              <img id="previewRumah" src="" alt="" style="display:none;max-width:220px;border:1px solid #e9ecef;border-radius:.25rem;">
            </div>

            {{-- <div class="form-group col-md-6">
              <label>Denah Lokasi Rumah (gambar/foto sketsa)</label>
              <div class="custom-file">
                <input type="file" name="denah_lokasi" id="denah_lokasi" class="custom-file-input @error('denah_lokasi') is-invalid @enderror" accept=".jpg,.jpeg,.png,.pdf">
                <label class="custom-file-label" for="denah_lokasi">Pilih berkas (opsional)...</label>
                @error('denah_lokasi') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
              </div>
              <small class="text-muted">JPG/PNG/PDF, maks. 2MB.</small>
            </div> --}}
          </div>

        </div>
      </div>

      {{-- 4) PERNYATAAN --}}
      <div class="card card-outline card-danger mb-5">
        <div class="card-header py-2">
          <strong><i class="fas fa-shield-alt mr-1"></i> Pernyataan Persetujuan Pemohon</strong>
        </div>
        <div class="card-body">
          <ol class="mb-3">
            <li>Saya menyatakan bahwa apabila di kemudian hari timbul sengketa hak atas tanah maupun bangunan yang mengakibatkan pipa persil dibongkar, maka hal tersebut di luar tanggung jawab PERUMDAM TIRTA PAWAN Kabupaten Ketapang.</li>
            <li>Saya setuju bahwa saluran pipa dinas yang dipasang menjadi milik PERUMDAM TIRTA PAWAN dan dapat dimanfaatkan sesuai kebutuhan teknis.</li>
            <li>Perubahan jaringan pipa persil tanpa izin dapat menyebabkan sambungan pipa dinas ke rumah saya dicabut.</li>
            <li>Saya mematuhi seluruh ketentuan umum, peraturan, dan tarif yang berlaku.</li>
            <li>Pernyataan ini saya buat dengan sebenar-benarnya tanpa paksaan pihak mana pun.</li>
          </ol>
          <div class="custom-control custom-checkbox">
            <input class="custom-control-input" type="checkbox" id="setuju" name="setuju" value="1" required>
            <label class="custom-control-label" for="setuju">Saya telah membaca dan menyetujui pernyataan di atas.</label>
          </div>
          <small class="text-muted d-block mt-2">Isian tidak lengkap/tidak jelas tidak akan diproses.</small>
        </div>
      </div>

      {{-- Sticky action bar --}}
      <div class="bg-white border-top py-2 position-sticky" style="bottom:0; z-index: 9;">
        <div class="d-flex justify-content-end">
          <button type="submit" class="btn btn-primary">
            <i class="fas fa-paper-plane mr-1"></i> Kirim Pengajuan
          </button>
          <a href="{{ url('backend/Pengajuan') }}" class="btn btn-light ml-2">Batal</a>
        </div>
      </div>

    </form>
  </div>

  {{-- Enhancements: tampil nama file + preview foto --}}
  <script>
    (function(){
      // tampilkan nama file di custom-file-label
      document.querySelectorAll('.custom-file-input').forEach(function(inp){
        inp.addEventListener('change', function(){
          let label = this.nextElementSibling;
          if (label && this.files && this.files.length) {
            label.innerText = this.files[0].name;
          }
        });
      });

      // preview foto_rumah
      const foto = document.getElementById('foto_rumah');
      const preview = document.getElementById('previewRumah');
      if (foto && preview) {
        foto.addEventListener('change', function(){
          const f = this.files && this.files[0];
          if (!f) return;
          if (!f.type.match(/^image\//)) { preview.style.display='none'; return; }
          const reader = new FileReader();
          reader.onload = e => { preview.src = e.target.result; preview.style.display = 'block'; };
          reader.readAsDataURL(f);
        });
      }
    })();
  </script>
</x-backend>

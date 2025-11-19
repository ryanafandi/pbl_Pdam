<x-backend>
  {{-- HEADER --}}
  <section class="content-header">
    <div class="container-fluid d-flex justify-content-between align-items-center">
      <h1 class="m-0 fw-bold">Persyaratan Pendaftaran Saluran Baru</h1>
      <ol class="breadcrumb float-sm-right mb-0">
        <li class="breadcrumb-item"><a href="{{ url('backend') }}">Home</a></li>
        <li class="breadcrumb-item active">Persyaratan yang diperlukan</li>
      </ol>
    </div>
  </section>

  {{-- BODY --}}
  <section class="content">
    <div class="container-fluid">

      <div class="card shadow-sm border-0">
        <div class="card-body">

          {{-- Judul seksi + deskripsi singkat --}}
          <div class="d-flex align-items-center mb-2">
            <span class="mr-2 text-primary" style="font-size:1.25rem;">
              <i class="fas fa-paperclip"></i>
            </span>
            <h5 class="m-0 text-primary">
              Persyaratan Lampiran Dokumen Pengajuan PDAM Tirta Pawan
            </h5>
          </div>
          <p class="text-muted mb-4" style="max-width:880px;">
            Berikut daftar lampiran dokumen yang perlu disiapkan untuk melakukan pengajuan saluran air baru.
            Pastikan setiap berkas jelas, terbaca, dan sesuai dengan format yang ditentukan.
          </p>

          {{-- Tabel daftar lampiran --}}
          <div class="table-responsive">
            <table class="table table-hover mb-0">
              <thead class="bg-primary text-white">
                <tr>
                  <th style="width:70px;">No</th>
                  <th>Nama Dokumen / Lampiran</th>
                  <th style="width:40%;">Keterangan</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td class="align-middle">1</td>
                  <td class="align-middle">Foto KTP (Identitas Pemohon)</td>
                  <td class="align-middle">Wajib diunggah dalam format <code>JPG</code> atau <code>PNG</code>, ukuran maksimal 2MB.</td>
                </tr>
                <tr>
                  <td class="align-middle">2</td>
                  <td class="align-middle">Foto Rumah (Tampak Depan)</td>
                  <td class="align-middle">Menunjukkan lokasi bangunan yang akan dipasang sambungan air. Format <code>JPG/PNG</code>, maks. 2MB.</td>
                </tr>
                <tr>
                  <td class="align-middle">3</td>
                  <td class="align-middle">Foto KK / Dokumen Pendukung</td>
                  <td class="align-middle">Opsional. Dapat berupa <code>JPG</code>, <code>PNG</code>, atau <code>PDF</code> (maks. 2MB).</td>
                </tr>
                {{-- <tr>
                  <td class="align-middle">4</td>
                  <td class="align-middle">Denah Lokasi Rumah / Sketsa</td>
                  <td class="align-middle">Opsional. Membantu petugas survei menemukan lokasi dengan tepat. Format <code>JPG/PNG/PDF</code>, maks. 2MB.</td>
                </tr> --}}
              </tbody>
            </table>
          </div>

          {{-- Catatan & tombol --}}
          <div class="mt-4">
            <p class="text-muted mb-3">
              Pastikan semua dokumen di atas telah disiapkan dengan benar sebelum melanjutkan proses pendaftaran.
              Setelah dokumen lengkap, klik tombol berikut untuk mengisi formulir pengajuan.
            </p>
            <a href="{{ url('backend/Pengajuan/create') }}" class="btn btn-primary">
              <i class="fas fa-file-upload"></i> Ajukan Pendaftaran
            </a>
          </div>

        </div>
      </div>

    </div>
  </section>

  <style>
    .table thead th { border: none; }
    .table tbody tr + tr td { border-top: 1px solid #f0f1f3; }
    .table-hover tbody tr:hover { background: #f8fbff; }
  </style>
</x-backend>

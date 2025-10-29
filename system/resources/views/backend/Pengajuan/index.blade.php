<x-backend>
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Persyaratan Pendaftaran Saluran Baru</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Persyaratan yang diperlukan</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">

                    <!-- Konten utama -->
                    <div class="card p-3 mb-3">
                        <!-- Judul -->
                        <div class="row">
                            <div class="col-12">
                                <h4>
                                    <i class="fas fa-tint"></i>
                                    Persyaratan Pengajuan Saluran Baru PDAM Tirta Pawan
                                </h4>
                                <p class="mt-2">
                                    Berikut daftar dokumen dan persyaratan yang harus dilengkapi oleh calon pelanggan
                                    untuk melakukan pendaftaran saluran air baru di PDAM Tirta Pawan.
                                </p>
                            </div>
                        </div>

                        <!-- Tabel Persyaratan -->
                        <div class="row mt-4">
                            <div class="col-12 table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Persyaratan</th>
                                            <th>Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>Fotokopi KTP Pemohon</td>
                                            <td>Wajib, masih berlaku</td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>Fotokopi Kartu Keluarga</td>
                                            <td>Wajib</td>
                                        </tr>
                                        <tr>
                                            <td>3</td>
                                            <td>Surat Permohonan Pemasangan Baru</td>
                                            <td>Ditandatangani pemohon</td>
                                        </tr>
                                        <tr>
                                            <td>4</td>
                                            <td>Foto Lokasi Rumah / Bangunan</td>
                                            <td>Menunjukkan titik lokasi pemasangan pipa</td>
                                        </tr>
                                        <tr>
                                            <td>5</td>
                                            <td>Nomor Telepon yang Aktif</td>
                                            <td>Untuk dihubungi oleh petugas survei</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Petunjuk tambahan -->
                        <div class="row mt-3">
                            <div class="col-12">
                                <p class="text-muted">
                                    Pastikan seluruh dokumen sudah lengkap dan sesuai. Setelah semua persyaratan
                                    dipenuhi, silakan klik tombol di bawah ini untuk melanjutkan proses pengajuan.
                                </p>
                            </div>
                        </div>

                        <!-- Tombol aksi -->
                        <div class="row no-print mt-3">
                            <div class="col-12">
                                <a href="{{ url('backend/Pengajuan/create') }}"
                                    class="btn btn-primary float-right ml-2">
                                    <i class="fas fa-file-upload"></i> Ajukan Pendaftaran
                                </a>
                                <a href="{{ route('Pengajuan.print') }}" target="_blank"
                                    class="btn btn-default float-right">
                                    <i class="fas fa-file-alt"></i> Cetak Surat Permohonan
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- /.card -->

                </div>
            </div>
        </div>
    </section>
</x-backend>

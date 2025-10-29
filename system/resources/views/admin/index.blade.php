<x-admin-dashboard>
  <!-- Header -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-3 align-items-center">
        <div class="col-sm-6">
          <h1 class="m-0 text-primary fw-bold">
            <i class="fas fa-tachometer-alt"></i> Dashboard Admin
          </h1>
          <small class="text-muted">Selamat datang kembali di sistem PDAM Tirta Pawan</small>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right bg-transparent mb-0">
            <li class="breadcrumb-item"><a href="{{ url('backend') }}">Home</a></li>
            <li class="breadcrumb-item active">Dashboard</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <!-- Main Content -->
  <section class="content">
    <div class="container-fluid">

      <!-- Statistik Utama -->
      <div class="row">
        <!-- Total Pengguna -->
        <div class="col-lg-3 col-md-6 col-12 mb-4">
          <div class="small-box shadow-sm border-0 position-relative overflow-hidden"
               style="background: linear-gradient(135deg, #007bff, #0056b3);">
            <div class="inner text-white">
              <h3 class="fw-bold">120</h3>
              <p>Total Pengguna</p>
            </div>
            <div class="icon">
              <i class="fas fa-users"></i>
            </div>
            <a href="#" class="small-box-footer text-white">
              Lihat Detail <i class="fas fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>

        <!-- Pengajuan Masuk -->
        <div class="col-lg-3 col-md-6 col-12 mb-4">
          <div class="small-box shadow-sm border-0"
               style="background: linear-gradient(135deg, #17a2b8, #0d6efd);">
            <div class="inner text-white">
              <h3 class="fw-bold">45</h3>
              <p>Pengajuan Masuk</p>
            </div>
            <div class="icon">
              <i class="fas fa-file-alt"></i>
            </div>
            <a href="#" class="small-box-footer text-white">
              Lihat Detail <i class="fas fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>

        <!-- Dalam Proses -->
        <div class="col-lg-3 col-md-6 col-12 mb-4">
          <div class="small-box shadow-sm border-0"
               style="background: linear-gradient(135deg, #ffc107, #e0a800);">
            <div class="inner text-white">
              <h3 class="fw-bold">15</h3>
              <p>Dalam Proses</p>
            </div>
            <div class="icon">
              <i class="fas fa-sync-alt"></i>
            </div>
            <a href="#" class="small-box-footer text-white">
              Lihat Detail <i class="fas fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>

        <!-- Pengajuan Selesai -->
        <div class="col-lg-3 col-md-6 col-12 mb-4">
          <div class="small-box shadow-sm border-0"
               style="background: linear-gradient(135deg, #28a745, #218838);">
            <div class="inner text-white">
              <h3 class="fw-bold">30</h3>
              <p>Pengajuan Selesai</p>
            </div>
            <div class="icon">
              <i class="fas fa-check-circle"></i>
            </div>
            <a href="#" class="small-box-footer text-white">
              Lihat Detail <i class="fas fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
      </div>

      <!-- Informasi dan Pengumuman -->
      <div class="row">
        <div class="col-lg-8">
          <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white">
              <h3 class="card-title mb-0">
                <i class="fas fa-info-circle mr-2"></i> Informasi Terbaru
              </h3>
            </div>
            <div class="card-body">
              <p>Selamat datang di <strong>Dashboard Admin PDAM Tirta Pawan</strong>.</p>
              <p>Melalui halaman ini, Anda dapat memantau seluruh aktivitas pengguna, pengajuan, laporan, dan status terkini sistem.</p>
              <ul class="mb-0">
                <li>Pastikan data pengguna dan pengajuan selalu diperbarui secara berkala.</li>
                <li>Periksa laporan masuk setiap hari untuk memastikan kelancaran layanan.</li>
                <li>Gunakan menu di sidebar untuk mengakses fitur administrasi.</li>
              </ul>
            </div>
          </div>
        </div>

        <!-- Card Aktivitas -->
        <div class="col-lg-4">
          <div class="card shadow-sm border-0">
            <div class="card-header bg-info text-white">
              <h3 class="card-title mb-0">
                <i class="fas fa-bell mr-2"></i> Aktivitas Terakhir
              </h3>
            </div>
            <div class="card-body">
              <ul class="list-unstyled mb-0">
                <li><i class="fas fa-user-plus text-primary mr-2"></i> 5 pengguna baru terdaftar</li>
                <li><i class="fas fa-file-alt text-success mr-2"></i> 3 pengajuan baru diterima</li>
                <li><i class="fas fa-check-circle text-success mr-2"></i> 1 pengajuan disetujui</li>
                <li><i class="fas fa-times-circle text-danger mr-2"></i> 2 pengajuan ditolak</li>
              </ul>
            </div>
          </div>
        </div>
      </div>

    </div>
  </section>
</x-admin-dashboard>

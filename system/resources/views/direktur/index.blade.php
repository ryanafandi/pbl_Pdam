<x-direktur>
  <!-- Content Header -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-primary">
            <i class="fas fa-home mr-2"></i>Dashboard Pengguna
          </h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
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

      <!-- Statistik Pengajuan -->
      <div class="row">

        <!-- Pengajuan Baru -->
        <div class="col-lg-3 col-6">
          <div class="small-box bg-gradient-info">
            <div class="inner text-white">
              <h3>12</h3>
              <p>Pengajuan Baru</p>
            </div>
            <div class="icon">
              <i class="fas fa-faucet"></i>
            </div>
            <a href="{{ url('backend/pengajuan') }}" class="small-box-footer text-white">
              Lihat Detail <i class="fas fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>

        <!-- Pengajuan Diproses -->
        <div class="col-lg-3 col-6">
          <div class="small-box bg-gradient-warning">
            <div class="inner text-white">
              <h3>5</h3>
              <p>Dalam Proses</p>
            </div>
            <div class="icon">
              <i class="fas fa-tasks"></i>
            </div>
            <a href="{{ url('backend/pengajuan/proses') }}" class="small-box-footer text-white">
              Lihat Detail <i class="fas fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>

        <!-- Pengajuan Selesai -->
        <div class="col-lg-3 col-6">
          <div class="small-box bg-gradient-success">
            <div class="inner text-white">
              <h3>8</h3>
              <p>Pengajuan Selesai</p>
            </div>
            <div class="icon">
              <i class="fas fa-check-circle"></i>
            </div>
            <a href="{{ url('backend/pengajuan/selesai') }}" class="small-box-footer text-white">
              Lihat Detail <i class="fas fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>

        <!-- Riwayat Pengajuan -->
        <div class="col-lg-3 col-6">
          <div class="small-box bg-gradient-primary">
            <div class="inner text-white">
              <h3>25</h3>
              <p>Riwayat Pengajuan</p>
            </div>
            <div class="icon">
              <i class="fas fa-history"></i>
            </div>
            <a href="{{ url('backend/pengajuan/riwayat') }}" class="small-box-footer text-white">
              Lihat Detail <i class="fas fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>

      </div>

      <!-- Informasi / Pengumuman -->
      <div class="row mt-4">
        <div class="col-lg-12">
          <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white">
              <h3 class="card-title"><i class="fas fa-info-circle mr-2"></i>Informasi Terbaru</h3>
            </div>
            <div class="card-body">
              <p>Selamat datang di sistem pengajuan sambungan baru <strong>PDAM Tirta Pawan</strong>.</p>
              <p>
                Anda dapat mengajukan sambungan air baru, memantau prosesnya, dan melihat riwayat pengajuan secara langsung
                dari dashboard ini.
              </p>
              <ul class="mb-0">
                <li>Pastikan semua dokumen sudah lengkap sebelum mengajukan.</li>
                <li>Pantau status pengajuan Anda secara berkala.</li>
                <li>Hubungi admin jika mengalami kendala.</li>
              </ul>
            </div>
          </div>
        </div>
      </div>

    </div>
  </section>
</x-direktur>

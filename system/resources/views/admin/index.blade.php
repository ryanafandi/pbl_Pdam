<x-admin-dashboard>
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
            <li class="breadcrumb-item"><a href="{{ url('admin') }}">Home</a></li>
            <li class="breadcrumb-item active">Dashboard</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">

      <div class="row">
        @if($hasUsers)
        <div class="col-lg-3 col-md-6 col-12 mb-4">
          <div class="small-box shadow-sm border-0 position-relative overflow-hidden"
               style="background: linear-gradient(135deg, #007bff, #0056b3);">
            <div class="inner text-white">
              <h3 class="fw-bold">{{ number_format($totalUsers) }}</h3>
              <p>Total Pengguna</p>
            </div>
            <div class="icon"><i class="fas fa-users"></i></div>
            <a href="#" class="small-box-footer text-white">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        @endif

        <div class="col-lg-3 col-md-6 col-12 mb-4">
          <div class="small-box shadow-sm border-0" style="background: linear-gradient(135deg, #17a2b8, #0d6efd);">
            <div class="inner text-white">
              <h3 class="fw-bold">{{ number_format($pengajuanMasuk) }}</h3>
              <p>Pengajuan Masuk</p>
            </div>
            <div class="icon"><i class="fas fa-file-alt"></i></div>
            <a href="{{ url('admin/pengajuan') . '?status=SUBMITTED' }}" class="small-box-footer text-white">
              Lihat Detail <i class="fas fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>

        <div class="col-lg-3 col-md-6 col-12 mb-4">
          <div class="small-box shadow-sm border-0" style="background: linear-gradient(135deg, #ffc107, #e0a800);">
            <div class="inner text-white">
              <h3 class="fw-bold">{{ number_format($pengajuanProses) }}</h3>
              <p>Dalam Proses</p>
            </div>
            <div class="icon"><i class="fas fa-sync-alt"></i></div>
            <a href="{{ url('admin/pengajuan') . '?progress_status=INSTALLING' }}" class="small-box-footer text-white">
              Lihat Detail <i class="fas fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>

        <div class="col-lg-3 col-md-6 col-12 mb-4">
          <div class="small-box shadow-sm border-0" style="background: linear-gradient(135deg, #28a745, #218838);">
            <div class="inner text-white">
              <h3 class="fw-bold">{{ number_format($pengajuanSelesai) }}</h3>
              <p>Pengajuan Selesai</p>
            </div>
            <div class="icon"><i class="fas fa-check-circle"></i></div>
            <a href="{{ url('admin/pengajuan') . '?progress_status=INSTALLED' }}" class="small-box-footer text-white">
              Lihat Detail <i class="fas fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-lg-8">
          <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white">
              <h3 class="card-title mb-0"><i class="fas fa-info-circle mr-2"></i> Informasi Terbaru</h3>
            </div>
            <div class="card-body">
              <p>Selamat datang di <strong>Dashboard Admin PDAM Tirta Pawan</strong>.</p>
              <p>Pantau aktivitas pengajuan dan progres pemasangan dari sini.</p>
              <ul class="mb-0">
                <li>Periksa <a href="{{ url('admin/pengajuan') . '?status=SUBMITTED' }}">Pengajuan Masuk</a> setiap hari.</li>
                <li>Update progres pemasangan agar pelanggan bisa memantau status.</li>
                <li>Gunakan menu di sidebar untuk fitur administrasi lainnya.</li>
              </ul>
            </div>
          </div>
        </div>

        <div class="col-lg-4">
          <div class="card shadow-sm border-0">
            <div class="card-header bg-info text-white">
              <h3 class="card-title mb-0"><i class="fas fa-bell mr-2"></i> Aktivitas Terakhir</h3>
            </div>
            <div class="card-body">
              @if($aktivitas->isEmpty())
                <p class="text-muted mb-0">Belum ada aktivitas.</p>
              @else
                <ul class="list-unstyled mb-0">
                  @foreach($aktivitas as $a)
                    <li class="mb-2">
                      <i class="fas fa-angle-right text-primary mr-2"></i>
                      <a href="{{ url('admin/pengajuan/'.$a->id) }}">
                        {{ $a->no_pendaftaran }} — {{ $a->pemohon_nama }}
                      </a>
                      <br>
                      <small class="text-muted">
                        Status: <strong>{{ $a->status }}</strong>
                        @if($a->progress_status) | Progres: <strong>{{ $a->progress_status }}</strong> @endif
                        • {{ $a->updated_at?->format('d/m/Y H:i') }}
                      </small>
                    </li>
                  @endforeach
                </ul>
              @endif
            </div>
          </div>
        </div>
      </div>

    </div>
  </section>
</x-admin-dashboard>

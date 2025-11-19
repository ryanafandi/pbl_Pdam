<nav class="main-header navbar navbar-expand navbar-light border-0 shadow-sm"
     style="background: linear-gradient(90deg, #0056b3, #007bff); color: white;">
  
  <!-- Left Navbar -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link text-white" data-widget="pushmenu" href="#" role="button">
        <i class="fas fa-bars"></i>
      </a>
    </li>
    {{-- <li class="nav-item d-none d-sm-inline-block">
      <a href="{{ url('admin') }}" class="nav-link text-white fw-semibold">
        <i class="fas fa-home"></i> 
      </a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
      <a href="#" class="nav-link text-white fw-semibold">
        <i class="fas fa-envelope"></i> 
      </a>
    </li> --}}
  </ul>

  <!-- Right Navbar -->
  <ul class="navbar-nav ml-auto">

    <!-- Search -->
    <li class="nav-item dropdown">
      <a class="nav-link text-white" data-widget="navbar-search" href="#" role="button">
        <i class="fas fa-search"></i>
      </a>
      <div class="navbar-search-block">
        <form class="form-inline">
          <div class="input-group input-group-sm">
            <input class="form-control form-control-navbar" type="search" placeholder="Cari data..." aria-label="Search">
            <div class="input-group-append">
              <button class="btn btn-navbar" type="submit">
                <i class="fas fa-search"></i>
              </button>
              <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                <i class="fas fa-times"></i>
              </button>
            </div>
          </div>
        </form>
      </div>
    </li>

    <!-- Messages -->
    <li class="nav-item dropdown">
      <a class="nav-link text-white" data-toggle="dropdown" href="#">
        <i class="far fa-comments"></i>
        <span class="badge badge-danger navbar-badge">3</span>
      </a>
      <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right shadow-lg">
        <span class="dropdown-item dropdown-header">Pesan Terbaru</span>
        <div class="dropdown-divider"></div>

        <a href="#" class="dropdown-item">
          <div class="media">
            <img src="{{ url('public/backend/dist/img/user2-160x160.jpg') }}" alt="User Avatar"
                 class="img-size-50 mr-3 img-circle">
            <div class="media-body">
              <h3 class="dropdown-item-title">Bagian Pelayanan
                <span class="float-right text-sm text-success"><i class="fas fa-circle"></i></span>
              </h3>
              <p class="text-sm">Permohonan baru telah diterima</p>
              <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 5 menit lalu</p>
            </div>
          </div>
        </a>

        <div class="dropdown-divider"></div>
        <a href="#" class="dropdown-item dropdown-footer text-center text-primary fw-semibold">
          Lihat semua pesan
        </a>
      </div>
    </li>

    <!-- Notifications -->
    <li class="nav-item dropdown">
      <a class="nav-link text-white" data-toggle="dropdown" href="#">
        <i class="far fa-bell"></i>
        <span class="badge badge-warning navbar-badge">5</span>
      </a>
      <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right shadow-lg">
        <span class="dropdown-item dropdown-header">5 Notifikasi</span>
        <div class="dropdown-divider"></div>

        <a href="#" class="dropdown-item">
          <i class="fas fa-user-check mr-2 text-primary"></i> 2 akun baru diverifikasi
          <span class="float-right text-muted text-sm">1 jam</span>
        </a>

        <div class="dropdown-divider"></div>
        <a href="#" class="dropdown-item">
          <i class="fas fa-file-alt mr-2 text-success"></i> 1 pengajuan selesai
          <span class="float-right text-muted text-sm">2 jam</span>
        </a>

        <div class="dropdown-divider"></div>
        <a href="#" class="dropdown-item dropdown-footer text-center text-primary fw-semibold">
          Lihat semua notifikasi
        </a>
      </div>
    </li>

    <!-- Fullscreen -->
    <li class="nav-item">
      <a class="nav-link text-white" data-widget="fullscreen" href="#" role="button">
        <i class="fas fa-expand-arrows-alt"></i>
      </a>
    </li>

    <!-- Profile Dropdown -->
    <li class="nav-item dropdown">
      <a class="nav-link text-white" data-toggle="dropdown" href="#">
        <i class="fas fa-user-circle"></i>
      </a>
      <div class="dropdown-menu dropdown-menu-right shadow-lg">
        <a href="#" class="dropdown-item">
          <i class="fas fa-user-cog mr-2 text-primary"></i> Profil Admin
        </a>
        <div class="dropdown-divider"></div>
        <a href="{{ url('logout') }}" class="dropdown-item text-danger">
          <i class="fas fa-sign-out-alt mr-2"></i> Logout
        </a>
      </div>
    </li>
  </ul>
</nav>

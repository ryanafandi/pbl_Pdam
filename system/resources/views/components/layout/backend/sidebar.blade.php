<aside class="main-sidebar sidebar-dark-primary elevation-4" style="background: linear-gradient(180deg, #0d47a1, #1976d2);">
  <!-- Brand Logo -->
  <a href="{{ url('backend') }}" class="brand-link d-flex align-items-center justify-content-center">
  <!-- Logo -->
  <div style="
      width: 45px;
      height: 45px;
      border-radius: 50%;
      overflow: hidden;
      background: white;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-right: 10px;
  ">
    <img src="{{ url('public') }}/images.jpg" 
         alt="Logo PDAM"
         style="width: 100%; height: 100%; object-fit: cover;">
  </div>

  <!-- Teks -->
  <span class="brand-text font-weight-bold text-white" style="font-size: 18px;">
    PDAM Tirta Pawan
  </span>
</a>



  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user panel -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex align-items-center border-bottom border-secondary">
      <div class="image position-relative">
        <img src="{{ url('public/backend/dist/img/user2-160x160.jpg') }}" 
             class="img-circle elevation-2 border border-light" 
             alt="User Image" 
             style="width: 45px; height: 45px;">
        <span class="position-absolute bottom-0 end-0 translate-middle p-1 bg-success border border-light rounded-circle"></span>
      </div>
      <div class="info ml-2">
        <a href="#" class="d-block text-white font-weight-bold">Ryan Afandi</a>
        <small class="text-light">Pengguna</small>
      </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2 d-flex flex-column" style="height: 100%;">
      <ul class="nav nav-pills nav-sidebar flex-column flex-grow-1" data-widget="treeview" role="menu">

        <li class="nav-header text-uppercase text-white-50 small">Main Menu</li>

        <!-- Dashboard -->
        <li class="nav-item">
          <a href="{{ url('backend') }}" class="nav-link {{ request()->is('backend') ? 'active' : '' }}">
            <i class="nav-icon fas fa-home text-info"></i>
            <p>Dashboard</p>
          </a>
        </li>

        <!-- Pengajuan Saluran Baru -->
        <li class="nav-item">
          <a href="{{ url('backend/Pengajuan') }}" class="nav-link {{ request()->is('backend/Pengajuan*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-faucet text-primary"></i>
            <p>Pengajuan Saluran Baru</p>
          </a>
        </li>

        <!-- Lihat Proses Pengajuan -->
        <li class="nav-item">
          <a href="{{ url('backend/Proses') }}" class="nav-link {{ request()->is('backend/Proses*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-tasks text-warning"></i>
            <p>Lihat Proses Pengajuan</p>
          </a>
        </li>

        <!-- Riwayat Pengajuan -->
        <li class="nav-item">
          <a href="{{ url('backend/Riwayat') }}" class="nav-link {{ request()->is('backend/Riwayat*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-history text-success"></i>
            <p>Riwayat Pengajuan</p>
          </a>
        </li>

        <li class="flex-grow-1"></li>

        <!-- Logout -->
        <li class="nav-item border-top border-secondary mt-2">
          <a href="{{ url('logout') }}" class="nav-link text-danger">
            <i class="nav-icon fas fa-sign-out-alt"></i>
            <p>Logout</p>
          </a>
        </li>

      </ul>
    </nav>
  </div>
</aside>

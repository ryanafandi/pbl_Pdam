<aside class="main-sidebar elevation-4" style="background: linear-gradient(180deg, #00264d, #004080); color: #fff;">
    <!-- Brand Logo -->
     <a href="{{ url('admin') }}" class="brand-link d-flex align-items-center justify-content-center">
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
        <!-- User Panel -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex align-items-center border-bottom border-light">
            <div class="image">
                <img src="{{ url('public/backend/dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2"
                    alt="User Image">
            </div>
            <div class="info ml-2">
                <a href="#" class="d-block text-white font-weight-semibold">Admin</a>
                <small class="text-light">Administrator</small>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2 d-flex flex-column" style="height: 100%;">
            <ul class="nav nav-pills nav-sidebar flex-column flex-grow-1" data-widget="treeview" role="menu">

                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="{{ url('admin') }}" class="nav-link {{ request()->is('admin') ? 'active bg-primary' : '' }}">
                        <i class="nav-icon fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- Verifikasi Akun -->
                <li class="nav-item">
                    <a href="{{ url('') }}" class="nav-link {{ request()->is('admin/verifikasi*') ? 'active bg-primary' : '' }}">
                        <i class="nav-icon fas fa-user-check"></i>
                        <p>Verifikasi Akun</p>
                    </a>
                </li>

                <!-- Kelola Data Pengajuan -->
                <li class="nav-item">
                    <a href="{{ url('admin/pengajuan') }}" class="nav-link {{ request()->is('admin/pengajuan*') ? 'active bg-primary' : '' }}">
                        <i class="nav-icon fas fa-faucet"></i>
                        <p>Kelola Data Pengajuan</p>
                    </a>
                </li>

                <!-- SPK -->
                <li class="nav-item">
                    <a href="{{ url('') }}" class="nav-link {{ request()->is('admin/spk*') ? 'active bg-primary' : '' }}">
                        <i class="nav-icon fas fa-file-contract"></i>
                        <p>SPK</p>
                    </a>
                </li>

                <!-- Kirim Notifikasi -->
                <li class="nav-item">
                    <a href="{{ url('') }}" class="nav-link {{ request()->is('admin/notifikasi*') ? 'active bg-primary' : '' }}">
                        <i class="nav-icon fas fa-bell"></i>
                        <p>Kirim Notifikasi</p>
                    </a>
                </li>

                <!-- Spacer agar logout tetap di bawah -->
                <li class="flex-grow-1"></li>

                <!-- Logout -->
                <li class="nav-item border-top border-light pt-2">
                    <a href="{{ url('logout') }}" class="nav-link text-danger">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>Logout</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>

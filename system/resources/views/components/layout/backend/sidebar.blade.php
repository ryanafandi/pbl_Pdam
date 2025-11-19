<aside class="main-sidebar sidebar-dark-primary elevation-4" style="background: linear-gradient(180deg, #0d47a1, #1976d2);">
  <!-- Brand Logo -->
  <a href="{{ url('backend') }}" class="brand-link d-flex align-items-center justify-content-center" style="border-bottom: 1px solid rgba(255,255,255,0.2); padding: 14px 10px;">
    <div style="width:45px; height:45px; border-radius:50%; overflow:hidden; background:white; display:flex; align-items:center; justify-content:center; margin-right:10px;">
      <img src="{{ url('public') }}/images.jpg" alt="Logo PDAM" style="width:100%; height:100%; object-fit:cover;">
    </div>
    <span class="brand-text font-weight-bold text-white" style="font-size:18px; letter-spacing:0.3px;">PDAM Tirta Pawan</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- User Panel -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex align-items-center border-bottom border-secondary">
      <div class="image position-relative" style="margin-left:5px;">
        <img src="{{ url('public/backend/dist/img/user2-160x160.jpg') }}"
             class="img-circle elevation-2 border border-light"
             alt="User Image"
             style="width:45px; height:45px; object-fit:cover;">
        <span class="position-absolute bottom-0 end-0 translate-middle p-1 bg-success border border-light rounded-circle"></span>
      </div>
      <div class="info ml-2">
        <a href="#" class="d-block text-white font-weight-bold mb-0">{{ auth()->user()->nama ?? 'Pelanggan' }}</a>
        <small class="text-light">Pelanggan</small>
      </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
        <li class="nav-header text-uppercase text-white-50 small">Main Menu</li>

        <!-- Dashboard -->
        <li class="nav-item">
          <a href="{{ url('backend') }}" 
             class="nav-link {{ request()->is('backend') ? 'active' : '' }}" 
             style="{{ request()->is('backend') ? 'background-color:#e3f2fd; color:#0d47a1; font-weight:600;' : 'color:#e3f2fd;' }}">
            <i class="nav-icon fas fa-home" style="color:{{ request()->is('backend') ? '#0d47a1' : '#81d4fa' }};"></i>
            <p style="white-space: nowrap;">Dashboard</p>
          </a>
        </li>

        <!-- Ajukan Pendaftaran -->
        <li class="nav-item">
          <a href="{{ url('backend/Pengajuan') }}" 
             class="nav-link {{ request()->is('backend/Pengajuan/create') ? 'active' : '' }}" 
             style="{{ request()->is('backend/pendaftaran/create') ? 'background-color:#e3f2fd; color:#0d47a1; font-weight:600;' : 'color:#e3f2fd;' }}">
            <i class="nav-icon fas fa-file-signature" style="color:{{ request()->is('backend/Pengajuan/create') ? '#0d47a1' : '#bbdefb' }};"></i>
            <p style="white-space: nowrap;">Ajukan Pendaftaran</p>
          </a>
        </li>

        <!-- Status & Proses Pengajuan -->
        <li class="nav-item">
          <a href="{{ url('backend/Proses') }}" 
             class="nav-link {{ request()->is('backend/pendaftaran') || request()->is('backend/pendaftaran/*') ? 'active' : '' }}" 
             style="{{ request()->is('backend/pendaftaran') || request()->is('backend/pendaftaran/*') ? 'background-color:#e3f2fd; color:#0d47a1; font-weight:600;' : 'color:#e3f2fd;' }}">
            <i class="nav-icon fas fa-clipboard-list" style="color:{{ request()->is('backend/pendaftaran') || request()->is('backend/pendaftaran/*') ? '#0d47a1' : '#ffecb3' }};"></i>
            <p style="white-space: nowrap;">Status & Proses Pendaftaran</p>
          </a>
        </li>

        <!-- Tagihan & Dokumen -->
        <li class="nav-item">
          <a href="{{ url('backend/dokumen') }}" 
             class="nav-link {{ request()->is('backend/tagihan*') ? 'active' : '' }}" 
             style="{{ request()->is('backend/tagihan*') ? 'background-color:#e3f2fd; color:#0d47a1; font-weight:600;' : 'color:#e3f2fd;' }}">
            <i class="nav-icon fas fa-receipt" style="color:{{ request()->is('backend/tagihan*') ? '#0d47a1' : '#c8e6c9' }};"></i>
            <p style="white-space: nowrap;">
              Tagihan & Dokumen
              @if(($outstandingCount ?? 0) > 0)
                <span class="right badge badge-danger">{{ $outstandingCount }}</span>
              @endif
            </p>
          </a>
        </li>

        <!-- Riwayat Pengajuan -->
        <li class="nav-item">
          <a href="{{ url('backend/riwayat') }}" 
             class="nav-link {{ request()->is('backend/riwayat*') ? 'active' : '' }}" 
             style="{{ request()->is('backend/riwayat*') ? 'background-color:#e3f2fd; color:#0d47a1; font-weight:600;' : 'color:#e3f2fd;' }}">
            <i class="nav-icon fas fa-history" style="color:{{ request()->is('backend/riwayat*') ? '#0d47a1' : '#b3e5fc' }};"></i>
            <p style="white-space: nowrap;">Riwayat Pengajuan</p>
          </a>
        </li>

        <!-- Profil & Bantuan -->
        <li class="nav-item">
          <a href="{{ url('backend/profil') }}" 
             class="nav-link {{ request()->is('backend/profil*') ? 'active' : '' }}" 
             style="{{ request()->is('backend/profil*') ? 'background-color:#e3f2fd; color:#0d47a1; font-weight:600;' : 'color:#e3f2fd;' }}">
            <i class="nav-icon fas fa-user-cog" style="color:{{ request()->is('backend/profil*') ? '#0d47a1' : '#cfd8dc' }};"></i>
            <p style="white-space: nowrap;">Profil & Bantuan</p>
          </a>
        </li>

        <!-- Logout -->
        <li class="nav-item mt-2 border-top border-secondary">
          <a href="{{ url('logout') }}" class="nav-link text-danger">
            <i class="nav-icon fas fa-sign-out-alt"></i>
            <p style="white-space: nowrap;">Logout</p>
          </a>
        </li>

      </ul>
    </nav>
  </div>
</aside>

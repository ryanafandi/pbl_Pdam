<aside class="main-sidebar elevation-4" style="background: linear-gradient(180deg, #00264d, #004080); color: #fff;">
    <!-- Brand Logo -->
    <a href="{{ url('direktur') }}" class="brand-link d-flex align-items-center justify-content-center">
        <div style="width:45px;height:45px;border-radius:50%;overflow:hidden;background:white;display:flex;align-items:center;justify-content:center;margin-right:10px;">
            <img src="{{ url('public') }}/images.jpg" alt="Logo PDAM" style="width:100%;height:100%;object-fit:cover;">
        </div>
        <span class="brand-text font-weight-bold text-white" style="font-size:18px;">PDAM Tirta Pawan</span>
    </a>

    <div class="sidebar">
        <!-- User Panel -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex align-items-center border-bottom border-light">
            <div class="image">
                <img src="{{ url('public/backend/dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info ml-2">
                <a href="#" class="d-block text-white font-weight-semibold">Direktur</a>
                <small class="text-light">Approval</small>
            </div>
        </div>

        @php
            $is = fn($pattern) => request()->is($pattern);

            $dashboardActive  = $is('direktur');
            $pendaftaranActive= $is('direktur/pendaftaran*');
            $spkoActive       = $is('direktur/spko*');
            $rabActive        = $is('direktur/rab*');
            $spkActive        = $is('direktur/spk*');
            $pemasanganActive = $is('direktur/pemasangan*');
        @endphp

        <nav class="mt-2 d-flex flex-column" style="height:100%;">
            <ul class="nav nav-pills nav-sidebar flex-column flex-grow-1" data-widget="treeview" role="menu">

                <!-- Dashboard Approval -->
                <li class="nav-item">
                    <a href="{{ url('direktur') }}" class="nav-link {{ $dashboardActive ? 'active bg-primary' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard Approval</p>
                    </a>
                </li>

                <!-- Approval Pendaftaran -->
                <li class="nav-item">
                    <a href="{{ url('direktur/pendaftaran') }}" class="nav-link {{ $pendaftaranActive ? 'active bg-primary' : '' }}">
                        <i class="nav-icon fas fa-id-card"></i>
                        <p>Approval Pendaftaran</p>
                    </a>
                </li>

                <!-- Approval SPKO -->
               

                <!-- Approval RAB & Dokumen Biaya -->
                <li class="nav-item">
                    <a href="{{ url('direktur/rab') }}" class="nav-link {{ $rabActive ? 'active bg-primary' : '' }}">
                        <i class="nav-icon fas fa-file-invoice-dollar"></i>
                        <p>Approval RAB</p>
                    </a>
                </li>

                <!-- Approval SPK -->
                <li class="nav-item">
                    <a href="{{ url('direktur/spk') }}" class="nav-link {{ $spkActive ? 'active bg-primary' : '' }}">
                        <i class="nav-icon fas fa-file-contract"></i>
                        <p>Approval SPK</p>
                    </a>
                </li>

                <!-- Pemasangan (monitor & hasil) -->
                <li class="nav-item">
                    <a href="{{ url('direktur/pemasangan') }}" class="nav-link {{ $pemasanganActive ? 'active bg-primary' : '' }}">
                        <i class="nav-icon fas fa-tools"></i>
                        <p>Pemasangan (Monitor &amp; Hasil)</p>
                    </a>
                </li>

                <!-- Spacer -->
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

<aside class="main-sidebar elevation-4" style="background: linear-gradient(180deg, #00264d, #004080); color: #fff;">
    <!-- Brand Logo -->
    <a href="{{ url('admin') }}" class="brand-link d-flex align-items-center justify-content-center">
        <div
            style="width:45px;height:45px;border-radius:50%;overflow:hidden;background:white;display:flex;align-items:center;justify-content:center;margin-right:10px;">
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
                <a href="#" class="d-block text-white font-weight-semibold">Admin</a>
                <small class="text-light">Administrator</small>
            </div>
        </div>

        @php
            /*
             |-----------------------------------------------------------------------
             | Penentuan status aktif menu
             |-----------------------------------------------------------------------
             | Catatan:
             | - Gunakan pola yang spesifik untuk mencegah "spk" ikut aktif di "spko".
             | - Pisahkan RAB dan Dokumen Biaya (URL & status aktif berbeda).
             | - Jika nantinya Anda memakai named route, tersedia contoh alternatif
             |   dengan routeIs di bawah (tinggal ganti).
             */

            // === Versi berbasis path URL (aman untuk struktur Anda sekarang) ===
            $dashboardActive    = request()->is('admin');
            $pengajuanActive    = request()->is('admin/pengajuan') || request()->is('admin/pengajuan/*');

            // Hindari tabrakan: jangan pakai 'spk*' yang bisa menangkap 'spko'
            $spkoActive         = request()->is('admin/spko') || request()->is('admin/spko/*');
            $spkActive          = request()->is('admin/spk')  || request()->is('admin/spk/*');

            // Pisahkan RAB dan Dokumen Biaya
            $rabActive          = request()->is('admin/rab') || request()->is('admin/rab/*');
            $dokBiayaActive     = request()->is('admin/dokumen-biaya') || request()->is('admin/dokumen-biaya/*');

            $pemasanganActive   = request()->is('admin/pemasangan') || request()->is('admin/pemasangan/*');

            /*
            // === Alternatif (jika sudah pakai named routes) ===
            $dashboardActive    = request()->routeIs('admin.dashboard');
            $pengajuanActive    = request()->routeIs('admin.pengajuan.*');

            $spkoActive         = request()->routeIs('admin.spko.*');     // ex: admin.spko.index, admin.spko.show
            $spkActive          = request()->routeIs('admin.spk.*');      // ex: admin.spk.index, admin.spk.show

            $rabActive          = request()->routeIs('admin.rab.*');
            $dokBiayaActive     = request()->routeIs('admin.dokumen_biaya.*');

            $pemasanganActive   = request()->routeIs('admin.pemasangan.*');
            */
        @endphp

        <nav class="mt-2 d-flex flex-column" style="height:100%;">
            <ul class="nav nav-pills nav-sidebar flex-column flex-grow-1" data-widget="treeview" role="menu">

                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="{{ url('admin') }}" class="nav-link {{ $dashboardActive ? 'active bg-primary' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard <small class="ml-1">/ Antrian &amp; SLA</small></p>
                    </a>
                </li>

                <!-- Pendaftaran -->
                <li class="nav-item">
                    <a href="{{ url('admin/pengajuan') }}" class="nav-link {{ $pengajuanActive ? 'active bg-primary' : '' }}">
                        <i class="nav-icon fas fa-id-card"></i>
                        <p>Pendaftaran</p>
                    </a>
                </li>

                <!-- SPKO -->
                <li class="nav-item">
                    <a href="{{ url('admin/spko') }}" class="nav-link {{ $spkoActive ? 'active bg-primary' : '' }}">
                        <i class="nav-icon fas fa-file-alt"></i>
                        <p>SPKO</p>
                    </a>
                </li>

                <!-- RAB -->
                <li class="nav-item">
                    <a href="{{ url('admin/rab') }}" class="nav-link {{ $rabActive ? 'active bg-primary' : '' }}">
                        <i class="nav-icon fas fa-file-invoice-dollar"></i>
                        <p>RAB</p>
                    </a>
                </li>

                <!-- Dokumen Biaya -->
                <li class="nav-item">
                    <a href="{{ url('admin/dokumen_biaya') }}" class="nav-link {{ $dokBiayaActive ? 'active bg-primary' : '' }}">
                        <i class="nav-icon fas fa-file-invoice-dollar"></i>
                        <p>Dokumen Biaya</p>
                    </a>
                </li>

                <!-- SPK -->
                <li class="nav-item">
                    <a href="{{ url('admin/spk') }}" class="nav-link {{ $spkActive ? 'active bg-primary' : '' }}">
                        <i class="nav-icon fas fa-file-contract"></i>
                        <p>SPK</p>
                    </a>
                </li>

                <!-- Pemasangan -->
                <li class="nav-item">
                    <a href="{{ url('admin/pemasangan') }}" class="nav-link {{ $pemasanganActive ? 'active bg-primary' : '' }}">
                        <i class="nav-icon fas fa-tools"></i>
                        <p>Pemasangan</p>
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

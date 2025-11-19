<aside class="main-sidebar sidebar-dark-primary sidebar-no-expand elevation-4" 
       style="background: linear-gradient(180deg, #00264d, #004080); color: #fff;">

    <!-- Brand Logo -->
    <a href="{{ url('perencanaan') }}" class="brand-link d-flex align-items-center justify-content-center">
        <div style="width:45px;height:45px;border-radius:50%;overflow:hidden;background:white;
                    display:flex;align-items:center;justify-content:center;margin-right:10px;">
            <img src="{{ url('public/images.jpg') }}" alt="Logo PDAM" 
                 style="width:100%;height:100%;object-fit:cover;">
        </div>
        <span class="brand-text font-weight-bold text-white" style="font-size:18px;">PDAM Tirta Pawan</span>
    </a>

    <div class="sidebar">
        <!-- User Panel -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex align-items-center border-bottom border-light">
            <div class="image">
                <img src="{{ url('public/backend/dist/img/user2-160x160.jpg') }}" 
                     class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info ml-2">
                <a href="#" class="d-block text-white font-weight-semibold">Tim Perencanaan</a>
                <small class="text-light">Survey &amp; RAB</small>
            </div>
        </div>

        @php
            $is = fn($pattern) => request()->is($pattern);

            $spkoActive     = $is('perencanaan/spko*');
            $kalenderActive = $is('perencanaan/jadwal*');
            $surveiActive   = $is('perencanaan/survei*');
            $rabActive      = $is('perencanaan/rab') && request('filter') != 'menunggu_approval';
            $approvalActive = $is('perencanaan/rab') && request('filter') == 'menunggu_approval';
        @endphp

        <!-- Menu Navigasi -->
        <nav class="mt-2 d-flex flex-column" style="height:100%;">
            <ul class="nav nav-pills nav-sidebar flex-column flex-grow-1" 
                data-widget="treeview" role="menu">

                <!-- Antrian SPKO -->
                <li class="nav-item">
                    <a href="{{ url('perencanaan/spko') }}" 
                       class="nav-link {{ $spkoActive ? 'active bg-primary' : '' }}">
                        <i class="nav-icon fas fa-inbox"></i>
                        <p>Antrian SPKO</p>
                    </a>
                </li>

                <!-- Penjadwalan Survei -->
                <li class="nav-item">
                    <a href="{{ url('perencanaan/jadwal') }}" 
                       class="nav-link {{ $kalenderActive ? 'active bg-primary' : '' }}">
                        <i class="nav-icon fas fa-calendar-alt"></i>
                        <p>Penjadwalan Survei</p>
                    </a>
                </li>

                <!-- Input Hasil Survei -->
                <li class="nav-item">
                    <a href="{{ url('perencanaan/survei') }}" 
                       class="nav-link {{ $surveiActive ? 'active bg-primary' : '' }}">
                        <i class="nav-icon fas fa-map-marked-alt"></i>
                        <p>Input Hasil Survei</p>
                    </a>
                </li>

                <!-- Susun RAB -->
                <li class="nav-item">
                    <a href="{{ url('perencanaan/rab') }}" 
                       class="nav-link {{ $rabActive ? 'active bg-primary' : '' }}">
                        <i class="nav-icon fas fa-file-invoice-dollar"></i>
                        <p>Susun RAB</p>
                    </a>
                </li>

                <!-- Kirim RAB untuk Approval -->
                {{-- <li class="nav-item">
                    <a href="{{ url('perencanaan/rab?filter=menunggu_approval') }}" 
                       class="nav-link {{ $approvalActive ? 'active bg-primary' : '' }}">
                        <i class="nav-icon fas fa-paper-plane"></i>
                        <p>Kirim RAB untuk Approval</p>
                    </a>
                </li> --}}

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

<aside class="main-sidebar elevation-4" style="background: linear-gradient(180deg, #00264d, #004080); color: #fff;">
    <a href="{{ url('trandis') }}" class="brand-link d-flex align-items-center justify-content-center">
        <div style="width:45px;height:45px;border-radius:50%;overflow:hidden;background:white;display:flex;align-items:center;justify-content:center;margin-right:10px;">
            <img src="{{ url('public') }}/images.jpg" alt="Logo PDAM" style="width:100%;height:100%;object-fit:cover;">
        </div>
        <span class="brand-text font-weight-bold text-white" style="font-size:18px;">PDAM Tirta Pawan</span>
    </a>

    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex align-items-center border-bottom border-light">
            <div class="image">
                <img src="{{ url('public/backend/dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info ml-2">
                <a href="#" class="d-block text-white font-weight-semibold">
                    @if(Auth::check())
                        {{ Auth::user()->nama }}
                    @else
                        Tim Lapangan
                    @endif
                </a>
                <small class="text-light">Divisi Trandis</small>
            </div>
        </div>

        @php
            /*
             |-----------------------------------------------------------------------
             | Logika Aktif Menu & Counter Badge (Trandis)
             |-----------------------------------------------------------------------
             */
            
            // 1. Status Aktif Menu
            $dashboardActive  = request()->is('trandis') || request()->is('trandis/dashboard');
            $spkMasukActive   = request()->is('trandis/spk-masuk') || request()->is('trandis/spk-masuk/*');
            $pemasanganActive = request()->is('trandis/pemasangan') || request()->is('trandis/pemasangan/*');

            // 2. Counter Badge (Notifikasi Angka)
            // Hitung SPK Baru (Disetujui Direktur, Belum Dijadwal)
            $countNew = \App\Models\SpkHeader::where('status', 'disetujui')
                        ->where('status_teknis', 'pending')
                        ->count();

            // Hitung SPK Sedang Dikerjakan (Working)
            $countWorking = \App\Models\SpkHeader::where('status_teknis', 'working')->count();
        @endphp

        <nav class="mt-2 d-flex flex-column" style="height:100%;">
            <ul class="nav nav-pills nav-sidebar flex-column flex-grow-1" data-widget="treeview" role="menu">

                <li class="nav-item">
                    <a href="{{ url('trandis/dashboard') }}" class="nav-link {{ $dashboardActive ? 'active bg-primary' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-header text-white-50">MANAJEMEN TUGAS</li>

                <li class="nav-item">
                    <a href="{{ url('trandis/spk-masuk') }}" class="nav-link {{ $spkMasukActive ? 'active bg-primary' : '' }}">
                        <i class="nav-icon fas fa-inbox"></i>
                        <p>
                            SPK Masuk
                            @if($countNew > 0)
                                <span class="right badge badge-danger">{{ $countNew }}</span>
                            @endif
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ url('trandis/pemasangan') }}" class="nav-link {{ $pemasanganActive ? 'active bg-primary' : '' }}">
                        <i class="nav-icon fas fa-tools"></i>
                        <p>
                            Proses Pemasangan
                            @if($countWorking > 0)
                                <span class="right badge badge-warning text-dark">{{ $countWorking }} Aktif</span>
                            @endif
                        </p>
                    </a>
                </li>

                <li class="flex-grow-1"></li>

                <li class="nav-item border-top border-light pt-2">
                    <a href="{{ url('logout') }}" class="nav-link text-danger" onclick="return confirm('Yakin ingin keluar?')">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>Logout</p>
                    </a>
                </li>

            </ul>
        </nav>
    </div>
</aside>
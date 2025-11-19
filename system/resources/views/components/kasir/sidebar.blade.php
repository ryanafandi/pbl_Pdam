<aside class="main-sidebar sidebar-dark-primary elevation-4" style="background-color:#042954;">
    
    {{-- BRAND — sama seperti sidebar perencanaan --}}
    <a href="{{ url('kasir/tagihan') }}" class="brand-link" style="background-color:#063970;">
        <img src="{{ url('public/images.jpg') }}"
             alt="Logo"
             class="brand-image img-circle elevation-3"
             style="opacity:.9; background:white; padding:3px;">
        <span class="brand-text font-weight-light" style="color:white; font-weight:600;">
            PDAM Tirta Pawan
        </span>
    </a>

    {{-- SIDEBAR --}}
    <div class="sidebar">

        {{-- USER PANEL --}}
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ url('public/backend/dist/img/user2-160x160.jpg') }}"
                     class="img-circle elevation-2"
                     style="width:38px; height:38px;">
            </div>
            <div class="info">
                <a href="#" class="d-block" style="color:white; font-weight:600;">
                    Kasir
                </a>
                <span class="text-xs" style="color:#c2c6d6;">
                    Pembayaran Sambungan Baru
                </span>
            </div>
        </div>

        {{-- MENU --}}
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column"
                data-widget="treeview"
                role="menu"
                data-accordion="false"
                style="padding-bottom:50px;">

                {{-- Judul --}}
                <li class="nav-header" style="color:#8ea8db; font-size:11px;">
                    NAVIGASI KASIR
                </li>

                {{-- DASHBOARD --}}
                <li class="nav-item">
                    <a href="{{ url('kasir/tagihan') }}"
                       class="nav-link {{ request()->is('kasir/tagihan') && !request('status') ? 'active' : '' }}"
                       style="color:white;">
                        <i class="nav-icon fas fa-cash-register"></i>
                        <p>Dashboard Kasir</p>
                    </a>
                </li>

                {{-- BELUM LUNAS --}}
                <li class="nav-item">
                    <a href="{{ url('kasir/tagihan?status=SENT') }}"
                       class="nav-link {{ request('status') === 'SENT' ? 'active' : '' }}"
                       style="color:white;">
                        <i class="nav-icon fas fa-file-invoice-dollar"></i>
                        <p>Tagihan Belum Lunas</p>
                    </a>
                </li>

                {{-- LUNAS --}}
                <li class="nav-item">
                    <a href="{{ url('kasir/tagihan?status=PAID') }}"
                       class="nav-link {{ request('status') === 'PAID' ? 'active' : '' }}"
                       style="color:white;">
                        <i class="nav-icon fas fa-check-circle"></i>
                        <p>Riwayat Pembayaran</p>
                    </a>
                </li>

            </ul>
        </nav>
    </div>
</aside>

{{-- Tambahan gaya agar sidebar betul-betul sama seperti perencanaan --}}
<style>
    .main-sidebar .nav-link.active {
        background-color: #195cc4 !important;
        color: white !important;
        font-weight: 600;
    }

    .main-sidebar .nav-link:hover {
        background-color: #0f4fb3 !important;
        color: white !important;
    }

    .main-sidebar .nav-icon {
        color: #cfd8ff !important;
    }

    .main-sidebar .brand-link:hover {
        background-color: #08519c !important;
    }
</style>

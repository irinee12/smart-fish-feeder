<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Smart Fish Feeder')</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <!-- Google Fonts (Plus Jakarta Sans, sama dengan halaman lain) -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- CSS Khusus Halaman (Kondisional) -->
    @if(request()->is('stok*'))
        <link rel="stylesheet" href="{{ asset('css/stok.css') }}">
    @elseif(request()->is('jadwal*'))
        <link rel="stylesheet" href="{{ asset('css/Jadwal.css') }}">
    @elseif(request()->is('prediksi*'))
        <link rel="stylesheet" href="{{ asset('css/Prediksi.css') }}">
    @elseif(request()->is('riwayat*'))
        <link rel="stylesheet" href="{{ asset('css/Riwayat.css') }}">
    @endif
    
    <style>
        :root {
            --sea-deep: #052e4a;
            --sea-mid: #0a6a8f;
            --sea-bright: #1296c4;
            --ink: #0f2a3a;
            --muted: #5b7384;
            --nav-text: #4a6577;
            --nav-line: #e3eef4;
            --nav-soft: #e4f3f9;
        }

        body {
            font-family: 'Plus Jakarta Sans', 'Inter', system-ui, sans-serif;
            background-color: #eaf4f8;
            color: var(--ink);
        }

        /* ==========================================
           SIDEBAR STYLING (DESKTOP / LAPTOP)
        =========================================== */
        @media (min-width: 992px) {
            .app-wrapper {
                display: flex;
                min-height: 100vh;
            }

            .sidebar {
                position: sticky;
                top: 0;
                height: 100vh;
                width: 280px !important;
                min-width: 280px !important;
                background: #ffffff;
                border-right: 1px solid #dbe9f0;
                box-shadow: 4px 0 20px -14px rgba(5, 46, 74, 0.35);
                overflow-y: auto;
                flex-shrink: 0;
                padding: 1.5rem 1rem;
                scrollbar-width: thin;
                scrollbar-color: #c4dbe6 transparent;

                /* KUNCI UTAMA: Mengatur elemen agar berbaris ke bawah secara rapi */
                display: flex !important;
                flex-direction: column !important;
            }

            /* Padding dihilangkan supaya header tiap halaman bisa mentok penuh,
               padding isi sudah diatur di masing-masing halaman */
            .main-content {
                flex-grow: 1;
                min-width: 0;
                padding: 0;
            }
        }

        /* Logo / Judul Aplikasi di Bagian Paling Atas */
        .sidebar-brand {
            font-size: 1.05rem;
            font-weight: 800;
            letter-spacing: -0.01em;
            color: var(--ink);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem 0.75rem 1.25rem;
            margin-bottom: 1.25rem;
            border-bottom: 1px solid var(--nav-line);
        }

        .sidebar-brand:hover {
            color: var(--sea-mid);
        }

        /* Logo gambar (LOGO1.jpeg) */
        .sidebar-brand-logo {
            width: 44px;
            height: 44px;
            flex-shrink: 0;
            border-radius: 50%;
            background: #ffffff;
            border: 1px solid #dbe9f0;
            box-shadow: 0 2px 6px rgba(5, 46, 74, 0.12);
            object-fit: contain;
            display: block;
        }

        /* Kategori Menu */
        .sidebar-category {
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            color: #7a93a3;
            padding: 0 0.75rem;
            margin-bottom: 0.75rem;
        }

        /* Wrapper Daftar Menu */
        .sidebar .nav {
            width: 100%;
        }

        /* Navigasi Menu */
        .sidebar .nav-link {
            position: relative;
            display: flex;
            align-items: center;
            gap: 0.85rem;
            padding: 0.75rem 0.85rem;
            font-size: 0.9rem;
            font-weight: 500;
            color: var(--nav-text);
            border-radius: 10px;
            transition: background-color 0.2s ease, color 0.2s ease;
            margin-bottom: 0.3rem;
            white-space: nowrap;
        }

        .sidebar .nav-link i {
            font-size: 1.15rem;
            flex-shrink: 0;
        }

        .sidebar .nav-link:hover {
            color: var(--sea-mid);
            background-color: #f1f8fb;
        }

        .sidebar .nav-link:focus-visible {
            outline: 2px solid var(--sea-bright);
            outline-offset: 2px;
        }

        /* Menu Aktif: latar biru muda + penanda di sisi kiri */
        .sidebar .nav-link.active {
            color: var(--sea-mid) !important;
            background: var(--nav-soft) !important;
            box-shadow: none;
            font-weight: 700;
        }

        .sidebar .nav-link.active::before {
            content: '';
            position: absolute;
            left: -1rem;
            top: 20%;
            bottom: 20%;
            width: 4px;
            border-radius: 0 4px 4px 0;
            background: var(--sea-bright);
        }

        /* Tombol Logout / Login di dasar sidebar */
        .sidebar .border-top {
            border-top-color: var(--nav-line) !important;
        }

        .sidebar button.nav-link {
            color: var(--nav-text);
            font-family: inherit;
            margin-bottom: 0;
        }

        .sidebar button.nav-link:hover {
            color: #dc2626;
            background-color: #fef2f2 !important;
        }

        /* ==========================================
           BOTTOM NAVIGATION (HP DAN TABLET)
           Teks menu sama dengan sidebar laptop
        =========================================== */
        @media (max-width: 991.98px) {
            .sidebar {
                display: none !important;
            }

            main, .main-content, .content-wrapper {
                padding: 0 0 92px 0 !important;
                margin-left: 0 !important;
                width: 100% !important;
            }

            .bottom-nav-mobile {
                position: fixed;
                bottom: 0;
                left: 0;
                width: 100%;
                height: calc(68px + env(safe-area-inset-bottom, 0px));
                padding: 0 4px env(safe-area-inset-bottom, 0px);
                background-color: #ffffff;
                border-top: 1px solid #dbe9f0;
                display: flex;
                justify-content: space-around;
                align-items: stretch;
                z-index: 1040;
                box-shadow: 0 -6px 20px -12px rgba(5, 46, 74, 0.3);
            }

            .bottom-nav-item {
                position: relative;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                gap: 3px;
                text-decoration: none;
                color: var(--nav-text);
                flex: 1;
                min-width: 0;
                padding: 0 2px;
                font-size: 10px;
                font-weight: 500;
                line-height: 1.15;
                text-align: center;
                transition: color 0.2s;
            }

            .bottom-nav-item i {
                font-size: 20px;
                margin: 0;
            }

            /* Teks boleh turun ke 2 baris supaya sama dengan teks di sidebar laptop */
            .bottom-nav-item span {
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
                max-width: 100%;
            }

            /* Menu aktif: garis penanda di atas + warna sama dengan sidebar */
            .bottom-nav-item.active {
                color: var(--sea-mid);
                font-weight: 700;
            }

            .bottom-nav-item.active::before {
                content: '';
                position: absolute;
                top: 0;
                left: 26%;
                right: 26%;
                height: 3px;
                border-radius: 0 0 4px 4px;
                background: var(--sea-bright);
            }

            /* Tombol Logout di bar bawah */
            .bottom-nav-item button {
                font-family: inherit;
                font-size: inherit;
                font-weight: inherit;
                line-height: inherit;
                padding: 0;
                gap: 3px;
            }
        }

        @media (min-width: 992px) {
            .bottom-nav-mobile {
                display: none !important;
            }
        }

        /* Hormati pengguna yang mematikan animasi */
        @media (prefers-reduced-motion: reduce) {
            * {
                transition-duration: 0.01ms !important;
            }
        }
    </style>
</head>
<body>

    <!-- BUNGKUS UTAMA -->
    <div class="app-wrapper">
        
        <!-- ==========================================
             SIDEBAR (KIRI - DESKTOP)
        =========================================== -->
        <aside class="sidebar d-none d-lg-flex">
            
            <!-- 1. Logo / Judul Aplikasi di Atas -->
            <a href="{{ url('/') }}" class="sidebar-brand">
                <img
                    src="{{ asset('aset/LOGO1.jpeg') }}"
                    alt="Logo Smart Fish Feeder"
                    class="sidebar-brand-logo"
                >
                <span>Smart Fish Feeder</span>
            </a>
            
            <!-- 2. Kategori Menu -->
            <div class="sidebar-category">Menu Utama</div>
            
            <!-- 3. Daftar Menu Navigasi -->
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="{{ url('/') }}" class="nav-link {{ request()->is('/') ? 'active' : '' }}">
                        <i class="bi bi-grid-1x2-fill"></i> 
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('/jadwal') }}" class="nav-link {{ request()->is('jadwal*') ? 'active' : '' }}">
                        <i class="bi bi-clock-history"></i> 
                        <span>Jadwal Pakan</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('/stok') }}" class="nav-link {{ request()->is('stok*') ? 'active' : '' }}">
                        <i class="bi bi-box-seam"></i> 
                        <span>Stok Pakan</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('/prediksi') }}" class="nav-link {{ request()->is('prediksi*') ? 'active' : '' }}">
                        <i class="bi bi-graph-up-arrow"></i> 
                        <span>Prediksi Refill</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('/riwayat') }}" class="nav-link {{ request()->is('riwayat*') ? 'active' : '' }}">
                        <i class="bi bi-arrow-counterclockwise"></i> 
                        <span>Riwayat Pakan</span>
                    </a>
                </li>
            </ul>

            <div class="mt-auto pt-3 border-top">
                @auth
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="nav-link w-100 border-0 bg-transparent text-start">
                            <i class="bi bi-box-arrow-right"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="nav-link">
                        <i class="bi bi-box-arrow-in-right"></i>
                        <span>Login Pengelola</span>
                    </a>
                @endauth
            </div>
        </aside>

        <!-- ==========================================
             MAIN CONTENT (KANAN)
        =========================================== -->
        <main class="main-content">
            @yield('content')
        </main>
        
    </div>

    <!-- Script Javascript Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- ==========================================
         BOTTOM NAVIGATION (KHUSUS TAMPILAN HP)
    =========================================== -->
    <div class="bottom-nav-mobile">
        <a href="{{ url('/') }}" class="bottom-nav-item {{ request()->is('/') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2-fill"></i>
            <span>Dashboard</span>
        </a>
        <a href="{{ url('/jadwal') }}" class="bottom-nav-item {{ request()->is('jadwal*') ? 'active' : '' }}">
            <i class="bi bi-clock-history"></i>
            <span>Jadwal Pakan</span>
        </a>
        <a href="{{ url('/stok') }}" class="bottom-nav-item {{ request()->is('stok*') ? 'active' : '' }}">
            <i class="bi bi-box-seam"></i>
            <span>Stok Pakan</span>
        </a>
        <a href="{{ url('/prediksi') }}" class="bottom-nav-item {{ request()->is('prediksi*') ? 'active' : '' }}">
            <i class="bi bi-graph-up-arrow"></i>
            <span>Prediksi Refill</span>
        </a>
        <a href="{{ url('/riwayat') }}" class="bottom-nav-item {{ request()->is('riwayat*') ? 'active' : '' }}">
            <i class="bi bi-arrow-counterclockwise"></i>
            <span>Riwayat Pakan</span>
        </a>
        @auth
            <form action="{{ route('logout') }}" method="POST" class="bottom-nav-item">
                @csrf
                <button type="submit" class="border-0 bg-transparent text-reset d-flex flex-column align-items-center justify-content-center w-100 h-100">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Logout</span>
                </button>
            </form>
        @else
            <a href="{{ route('login') }}" class="bottom-nav-item">
                <i class="bi bi-box-arrow-in-right"></i>
                <span>Login Pengelola</span>
            </a>
        @endauth
    </div>
</body>
</html>
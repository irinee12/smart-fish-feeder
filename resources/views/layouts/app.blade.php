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
    
    <!-- Google Fonts (Inter) -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
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
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
        }

        /* ==========================================
           SIDEBAR STYLING (STRUKTUR RAPI & KOLOM)
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
                border-right: 1px solid #e2e8f0;
                overflow-y: auto;
                flex-shrink: 0;
                padding: 1.5rem 1.25rem;
                
                /* KUNCI UTAMA: Mengatur elemen agar berbaris ke bawah secara rapi */
                display: flex !important;
                flex-direction: column !important;
            }

            .main-content {
                flex-grow: 1;
                min-width: 0;
                padding: 2rem;
            }
        }

        /* Logo / Judul Aplikasi di Bagian Paling Atas */
        .sidebar-brand {
            font-size: 1.05rem;
            font-weight: 700;
            color: #0f172a;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem 0.75rem;
            margin-bottom: 1.5rem;
        }

        .sidebar-brand i {
            font-size: 1.4rem;
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Kategori Menu */
        .sidebar-category {
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            color: #94a3b8;
            padding: 0 0.75rem;
            margin-bottom: 0.75rem;
        }

        /* Wrapper Daftar Menu */
        .sidebar .nav {
            width: 100%;
        }

        /* Navigasi Menu */
        .sidebar .nav-link {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            padding: 0.75rem 0.85rem;
            font-size: 0.9rem;
            font-weight: 500;
            color: #64748b;
            border-radius: 8px;
            transition: all 0.2s ease;
            margin-bottom: 0.35rem;
            white-space: nowrap;
        }

        .sidebar .nav-link i {
            font-size: 1.15rem;
            flex-shrink: 0;
        }

        .sidebar .nav-link:hover {
            color: #2563eb;
            background-color: #f1f5f9;
        }

        /* Menu Aktif dengan Gradasi Modern */
        .sidebar .nav-link.active {
            color: #ffffff !important;
            background: linear-gradient(135deg, #3b82f6, #2563eb) !important;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
            font-weight: 600;
        }

        /* ==========================================
           BOTTOM NAVIGATION (KHUSUS HANDPHONE)
        =========================================== */
        @media (max-width: 768px) {
            .sidebar {
                display: none !important;
            }

            main, .main-content, .content-wrapper {
                padding: 1rem !important;
                padding-bottom: 90px !important;
                margin-left: 0 !important;
                width: 100% !important;
            }

            .bottom-nav-mobile {
                position: fixed;
                bottom: 0;
                left: 0;
                width: 100%;
                height: 70px;
                background-color: #ffffff;
                border-top: 1px solid #e2e8f0;
                display: flex;
                justify-content: space-around;
                align-items: center;
                z-index: 1050;
                box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.06);
                padding: 0 8px;
            }

            .bottom-nav-item {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                text-decoration: none;
                color: #64748b;
                flex: 1;
                height: 100%;
                font-size: 11px;
                font-weight: 500;
                transition: color 0.2s;
            }

            .bottom-nav-item i {
                font-size: 20px;
                margin-bottom: 4px;
            }

            .bottom-nav-item.active {
                color: #2563eb;
                font-weight: 600;
            }
        }

        @media (min-width: 769px) {
            .bottom-nav-mobile {
                display: none !important;
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
                <i class="bi bi-droplet-fill"></i>
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
            <span>Home</span>
        </a>
        <a href="{{ url('/jadwal') }}" class="bottom-nav-item {{ request()->is('jadwal*') ? 'active' : '' }}">
            <i class="bi bi-clock-history"></i>
            <span>Jadwal</span>
        </a>
        <a href="{{ url('/stok') }}" class="bottom-nav-item {{ request()->is('stok*') ? 'active' : '' }}">
            <i class="bi bi-box-seam"></i>
            <span>Stok</span>
        </a>
        <a href="{{ url('/prediksi') }}" class="bottom-nav-item {{ request()->is('prediksi*') ? 'active' : '' }}">
            <i class="bi bi-graph-up-arrow"></i>
            <span>Prediksi</span>
        </a>
        <a href="{{ url('/riwayat') }}" class="bottom-nav-item {{ request()->is('riwayat*') ? 'active' : '' }}">
            <i class="bi bi-arrow-counterclockwise"></i>
            <span>Riwayat</span>
        </a>
    </div>
</body>
</html>
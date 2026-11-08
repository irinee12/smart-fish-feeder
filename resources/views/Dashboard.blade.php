@extends('layouts.app')

@section('title', 'Dashboard Smart Fish Feeder')

@section('content')
{{-- Menghilangkan padding container agar header bisa mentok full dari kiri ke kanan --}}
<div class="dash container-fluid px-0">

    {{-- =====================================================
        HEADER FULL WARNA (MENTOK TANPA LENGKUNGAN)
    ===================================================== --}}
    <div class="dash-header py-3 px-4 mb-4 text-white d-flex align-items-center justify-content-between">

        {{-- GELEMBUNG DAN GELOMBANG (VISUAL SAJA) --}}
        <div class="dash-bubbles" aria-hidden="true">
            <span style="--x:6%;  --s:12px; --d:8s;  --delay:0s;"></span>
            <span style="--x:24%; --s:8px;  --d:6s;  --delay:2s;"></span>
            <span style="--x:47%; --s:16px; --d:9s;  --delay:1s;"></span>
            <span style="--x:68%; --s:9px;  --d:7s;  --delay:3s;"></span>
            <span style="--x:86%; --s:13px; --d:8.5s;--delay:0.6s;"></span>
        </div>
        <div class="dash-waves" aria-hidden="true">
            <svg class="dash-wave dash-wave-back" viewBox="0 0 1200 60" preserveAspectRatio="none">
                <path d="M0,30 C150,60 300,0 450,30 C600,60 750,0 900,30 C1050,60 1200,0 1350,30 L1350,60 L0,60 Z"/>
            </svg>
            <svg class="dash-wave dash-wave-front" viewBox="0 0 1200 60" preserveAspectRatio="none">
                <path d="M0,30 C150,0 300,60 450,30 C600,0 750,60 900,30 C1050,0 1200,60 1350,30 L1350,60 L0,60 Z"/>
            </svg>
        </div>

        <div class="dash-header-inner d-flex align-items-center gap-3">
            <div class="dash-header-icon p-2 rounded-3">
                <i class="bi bi-grid-1x2-fill fs-5 text-white"></i>
            </div>
            <div>
                <h4 class="fw-bold mb-0">Dashboard</h4>
                <p class="text-white text-opacity-75 mb-0 small">
                    Ringkasan kondisi alat dan penggunaan pakan ikan secara real-time.
                </p>
            </div>
        </div>
    </div>

    {{-- BUNGKUS KONTEN DI BAWAHNYA DIBERI PADDING SUPAYA TIDAK IKUT MENEMPEL KE PINGGIR --}}
    <div class="px-4">

        {{-- =====================================================
            BANNER STATUS SISTEM (KONDISI KOSONG / MENUNGGU KONEKSI)
        ===================================================== --}}
        <div class="dash-card dash-status card border-0 rounded-4 mb-4">
            <div class="card-body d-flex flex-wrap justify-content-between align-items-center p-3 p-md-4">
                <div class="d-flex align-items-center gap-3 mb-2 mb-md-0">
                    <div class="d-flex align-items-center gap-2">
                        <span class="dash-pulse-dot">●</span>
                        <span class="fw-bold text-secondary">Status Alat IoT</span>
                    </div>
                    <span class="dash-badge-wait badge rounded-pill px-3 py-2 fw-normal">Menunggu Koneksi</span>
                    <span class="text-muted small d-none d-md-inline">ESP32 Belum terhubung - IP: Memuat...</span>
                </div>
                <div class="dash-nosignal text-muted small d-flex align-items-center gap-2">
                    <i class="bi bi-wifi-off fs-5"></i> Tidak ada sinyal
                </div>
            </div>
        </div>

        {{-- =====================================================
            4 KARTU METRIK UTAMA (NILAI NOL)
        ===================================================== --}}
        <div class="row g-3 mb-4">
            {{-- KARTU 1: STOK PAKAN --}}
            <div class="col-12 col-md-6 col-lg-3">
                <div class="dash-card dash-metric dash-m1 card h-100 border-0 p-4 rounded-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="dash-metric-label text-uppercase fw-bold">Stok Pakan Saat Ini</span>
                        <div class="dash-metric-icon rounded-3 p-2 d-flex align-items-center justify-content-center">
                            <i class="bi bi-box-seam fs-5"></i>
                        </div>
                    </div>
                    <div>
                        <h2 class="fw-bold mb-0 text-dark">0 <span class="fs-5 fw-normal text-muted">kg</span></h2>
                        <small class="text-muted">Menunggu data sensor</small>
                    </div>
                </div>
            </div>

            {{-- KARTU 2: PAKAN HARI INI --}}
            <div class="col-12 col-md-6 col-lg-3">
                <div class="dash-card dash-metric dash-m2 card h-100 border-0 p-4 rounded-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="dash-metric-label text-uppercase fw-bold">Pakan Hari Ini</span>
                        <div class="dash-metric-icon rounded-3 p-2 d-flex align-items-center justify-content-center">
                            <i class="bi bi-arrow-repeat fs-5"></i>
                        </div>
                    </div>
                    <div>
                        <h2 class="fw-bold mb-0 text-dark">0 <span class="fs-5 fw-normal text-muted">kg</span></h2>
                        <small class="text-muted">Belum ada aktivitas</small>
                    </div>
                </div>
            </div>

            {{-- KARTU 3: JADWAL BERIKUTNYA --}}
            <div class="col-12 col-md-6 col-lg-3">
                <div class="dash-card dash-metric dash-m3 card h-100 border-0 p-4 rounded-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="dash-metric-label text-uppercase fw-bold">Jadwal Berikutnya</span>
                        <div class="dash-metric-icon rounded-3 p-2 d-flex align-items-center justify-content-center">
                            <i class="bi bi-clock fs-5"></i>
                        </div>
                    </div>
                    <div>
                        <h2 class="fw-bold mb-0 text-dark">--:--</h2>
                        <small class="text-muted">Belum ada jadwal aktif</small>
                    </div>
                </div>
            </div>

            {{-- KARTU 4: PREDIKSI REFILL --}}
            <div class="col-12 col-md-6 col-lg-3">
                <div class="dash-card dash-metric dash-m4 card h-100 border-0 p-4 rounded-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="dash-metric-label text-uppercase fw-bold">Prediksi Refill</span>
                        <div class="dash-metric-icon rounded-3 p-2 d-flex align-items-center justify-content-center">
                            <i class="bi bi-graph-up fs-5"></i>
                        </div>
                    </div>
                    <div>
                        <h2 class="fw-bold mb-0 text-dark">- <span class="fs-5 fw-normal text-muted">hari</span></h2>
                        <small class="text-muted">Data penggunaan tidak cukup</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- =====================================================
            BAGIAN TENGAH: GRAFIK & INDIKATOR STOK
        ===================================================== --}}
        <div class="row g-4 mb-4">
            {{-- GRAFIK KONSUMSI --}}
            <div class="col-12 col-lg-8">
                <div class="dash-card dash-panel card h-100 border-0 p-4 rounded-4">
                    <h5 class="fw-bold mb-1 text-dark">Grafik Penggunaan Pakan Harian</h5>
                    <p class="text-muted small mb-4">7 hari terakhir (dalam kg)</p>

                    {{-- Tampilan Kosong Grafik --}}
                    <div class="dash-empty w-100 d-flex flex-column align-items-center justify-content-center rounded-3" style="min-height: 250px;">
                        <i class="dash-empty-icon bi bi-bar-chart text-muted mb-2" style="font-size: 2rem;"></i>
                        <span class="text-muted">Belum ada data statistik untuk ditampilkan</span>
                    </div>
                </div>
            </div>

            {{-- INDIKATOR STOK KOSONG --}}
            <div class="col-12 col-lg-4">
                <div class="dash-card dash-panel card h-100 border-0 p-4 rounded-4">
                    <h5 class="fw-bold mb-4 text-dark">Indikator Stok</h5>

                    <div class="text-center mb-4">
                        <h1 class="dash-big-number fw-bold mb-0" style="font-size: 3rem;">0</h1>
                        <p class="text-muted mb-0">kg tersisa</p>
                    </div>

                    <div class="d-flex justify-content-between small mb-2">
                        <span class="text-muted">Tingkat Stok</span>
                        <span class="fw-bold text-secondary">Tidak diketahui</span>
                    </div>

                    {{-- Progress bar di-set 0% --}}
                    <div class="dash-progress progress mb-4" style="height: 8px;">
                        <div class="progress-bar bg-secondary" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>

                    <div class="dash-row d-flex justify-content-between border-bottom pb-2 mb-2 small">
                        <span class="text-muted">Kapasitas penuh</span>
                        <span class="fw-bold text-dark">20 kg</span>
                    </div>
                    <div class="dash-row d-flex justify-content-between border-bottom pb-2 mb-2 small">
                        <span class="text-muted">Terpakai</span>
                        <span class="fw-bold text-dark">0 kg</span>
                    </div>
                    <div class="dash-row d-flex justify-content-between small">
                        <span class="text-muted">Rata-rata/hari</span>
                        <span class="fw-bold text-dark">0 kg</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- =====================================================
            JADWAL PEMBERIAN PAKAN HARI INI (TABEL KOSONG)
        ===================================================== --}}
        <div class="dash-card dash-panel card border-0 p-4 rounded-4 mb-4">
            <h5 class="fw-bold mb-4 text-dark">Jadwal Pemberian Pakan Hari Ini</h5>

            <div class="table-responsive">
                <table class="dash-table table table-hover align-middle mb-0">
                    <thead>
                        <tr class="text-muted small text-uppercase">
                            <th class="fw-semibold pb-3">Waktu</th>
                            <th class="fw-semibold pb-3">Sesi</th>
                            <th class="fw-semibold pb-3">Porsi</th>
                            <th class="fw-semibold pb-3">Status</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        {{-- Baris ketika data kosong --}}
                        <tr>
                            <td colspan="4" class="py-5 text-center text-muted">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="dash-empty-icon bi bi-calendar-x mb-2" style="font-size: 2rem;"></i>
                                    <p class="mb-0">Belum ada jadwal pakan yang diatur hari ini.</p>
                                    <small>Silakan tambahkan jadwal melalui menu Jadwal Pakan.</small>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

{{-- CSS HALAMAN DASHBOARD (semua aturan diawali .dash agar tidak mengganggu halaman lain) --}}
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

    .dash {
        --sea-deep: #052e4a;
        --sea-mid: #0a6a8f;
        --sea-bright: #1296c4;
        --aqua: #5eead4;
        --ink: #0f2a3a;
        --muted: #5b7384;
        --line: #d3e4ec;

        min-height: 100vh;
        font-family: 'Plus Jakarta Sans', system-ui, -apple-system, 'Segoe UI', sans-serif;
        background: linear-gradient(160deg, #e8f6fa 0%, #f4fafc 50%, #e3f1f7 100%);
        color: var(--ink);
        padding-bottom: 1rem;
    }

    .dash h1, .dash h2, .dash h4, .dash h5 {
        letter-spacing: -0.02em;
    }

    /* ---------- HEADER ---------- */
    .dash-header {
        position: relative;
        width: 100%;
        overflow: hidden;
        padding-bottom: 2.25rem !important;
        background:
            radial-gradient(circle at 15% 10%, rgba(94, 234, 212, 0.3), transparent 45%),
            linear-gradient(135deg, var(--sea-bright) 0%, var(--sea-mid) 55%, var(--sea-deep) 100%);
        box-shadow: 0 10px 24px -12px rgba(5, 46, 74, 0.5);
        animation: dashHeaderIn 0.7s ease-out both;
    }

    @keyframes dashHeaderIn {
        from { opacity: 0; transform: translateY(-16px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .dash-header-inner {
        position: relative;
        z-index: 3;
    }

    .dash-header-icon {
        background: rgba(255, 255, 255, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.3);
        backdrop-filter: blur(4px);
        animation: dashFloat 5s ease-in-out infinite;
    }

    @keyframes dashFloat {
        0%, 100% { transform: translateY(0); }
        50%      { transform: translateY(-5px); }
    }

    .dash-bubbles {
        position: absolute;
        inset: 0;
        z-index: 1;
        pointer-events: none;
    }

    .dash-bubbles span {
        position: absolute;
        bottom: -20px;
        left: var(--x);
        width: var(--s);
        height: var(--s);
        border-radius: 50%;
        background: radial-gradient(circle at 30% 30%, rgba(255, 255, 255, 0.75), rgba(255, 255, 255, 0.1));
        border: 1px solid rgba(255, 255, 255, 0.4);
        animation: dashBubble var(--d) var(--delay) linear infinite;
    }

    @keyframes dashBubble {
        0%   { transform: translateY(0) translateX(0); opacity: 0; }
        15%  { opacity: 1; }
        100% { transform: translateY(-120px) translateX(12px); opacity: 0; }
    }

    .dash-waves {
        position: absolute;
        left: 0;
        right: 0;
        bottom: 0;
        height: 30px;
        z-index: 2;
        overflow: hidden;
        pointer-events: none;
    }

    .dash-wave {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 200%;
        height: 100%;
    }

    .dash-wave-back {
        fill: rgba(255, 255, 255, 0.18);
        animation: dashWave 10s linear infinite;
    }

    .dash-wave-front {
        fill: rgba(255, 255, 255, 0.3);
        height: 70%;
        animation: dashWave 6s linear infinite reverse;
    }

    @keyframes dashWave {
        from { transform: translateX(0); }
        to   { transform: translateX(-50%); }
    }

    /* ---------- KARTU UMUM ---------- */
    .dash-card {
        background: #ffffff;
        box-shadow: 0 10px 25px -14px rgba(5, 46, 74, 0.3);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        animation: dashCardIn 0.7s cubic-bezier(0.22, 1, 0.36, 1) backwards;
    }

    @keyframes dashCardIn {
        from { opacity: 0; transform: translateY(26px) scale(0.98); }
        to   { opacity: 1; transform: translateY(0) scale(1); }
    }

    .dash-status { animation-delay: 0.15s; }

    .dash-metric:hover,
    .dash-panel:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 34px -16px rgba(5, 46, 74, 0.38);
    }

    /* ---------- STATUS ALAT ---------- */
    .dash-pulse-dot {
        font-size: 0.8rem;
        color: #f59e0b;
        animation: dashPulse 1.6s ease-in-out infinite;
    }

    @keyframes dashPulse {
        0%, 100% { opacity: 1; transform: scale(1); }
        50%      { opacity: 0.35; transform: scale(1.35); }
    }

    .dash-badge-wait {
        background: rgba(245, 158, 11, 0.12);
        color: #b45309;
    }

    .dash-nosignal i {
        animation: dashBlink 2.4s ease-in-out infinite;
    }

    @keyframes dashBlink {
        0%, 100% { opacity: 1; }
        50%      { opacity: 0.35; }
    }

    /* ---------- 4 KARTU METRIK ---------- */
    .dash-metric {
        --accent: var(--sea-bright);
        --accent-soft: rgba(18, 150, 196, 0.13);
        position: relative;
        overflow: hidden;
        border-top: 4px solid var(--accent) !important;
    }

    .dash-m1 { animation-delay: 0.25s; }
    .dash-m2 { animation-delay: 0.37s; --accent: #14b8a6; --accent-soft: rgba(20, 184, 166, 0.14); }
    .dash-m3 { animation-delay: 0.49s; --accent: #0a6a8f; --accent-soft: rgba(10, 106, 143, 0.13); }
    .dash-m4 { animation-delay: 0.61s; --accent: #f59e0b; --accent-soft: rgba(245, 158, 11, 0.15); }

    .dash-metric::after {
        content: '';
        position: absolute;
        right: -45px;
        bottom: -55px;
        width: 140px;
        height: 140px;
        border-radius: 50%;
        background: var(--accent-soft);
        transition: transform 0.5s ease;
        pointer-events: none;
    }

    .dash-metric:hover::after {
        transform: scale(1.5);
    }

    .dash-metric > * {
        position: relative;
        z-index: 1;
    }

    .dash-metric-label {
        font-size: 0.72rem;
        letter-spacing: 0.5px;
        color: var(--muted);
    }

    .dash-metric-icon {
        width: 42px;
        height: 42px;
        background: var(--accent-soft);
        color: var(--accent);
        animation: dashFloat 4.5s ease-in-out infinite;
    }

    .dash-m2 .dash-metric-icon { animation-delay: 0.5s; }
    .dash-m3 .dash-metric-icon { animation-delay: 1s; }
    .dash-m4 .dash-metric-icon { animation-delay: 1.5s; }

    .dash-metric h2 {
        font-weight: 800 !important;
        font-variant-numeric: tabular-nums;
    }

    /* ---------- PANEL GRAFIK DAN INDIKATOR ---------- */
    .dash-panel:nth-of-type(1) { animation-delay: 0.7s; }

    .dash-empty {
        background-color: #f5fbfd;
        border: 1.5px dashed #b8d6e3;
        background-image: radial-gradient(rgba(18, 150, 196, 0.08) 1px, transparent 1px);
        background-size: 18px 18px;
    }

    .dash-empty-icon {
        animation: dashFloat 4s ease-in-out infinite;
    }

    .dash-big-number {
        color: var(--sea-mid);
        font-weight: 800 !important;
    }

    .dash-progress {
        position: relative;
        overflow: hidden;
        border-radius: 999px;
        background-color: #e1eef4;
    }

    /* Kilau air yang bergerak di trek progress */
    .dash-progress::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(100deg, transparent 30%, rgba(18, 150, 196, 0.25) 50%, transparent 70%);
        background-size: 200% 100%;
        animation: dashShimmer 2.8s linear infinite;
    }

    .dash-progress .progress-bar {
        background: linear-gradient(90deg, var(--sea-bright), var(--aqua)) !important;
        transition: width 1s cubic-bezier(0.22, 1, 0.36, 1);
    }

    @keyframes dashShimmer {
        from { background-position: 200% 0; }
        to   { background-position: -200% 0; }
    }

    .dash-row {
        border-color: var(--line) !important;
    }

    /* ---------- TABEL ---------- */
    .dash-table thead tr {
        border-bottom: 2px solid var(--line);
    }

    .dash-table thead th {
        background: transparent;
        color: var(--muted);
        letter-spacing: 0.5px;
        border-bottom: none;
    }

    .dash-table.table-hover > tbody > tr:hover > * {
        background-color: rgba(18, 150, 196, 0.06);
        --bs-table-accent-bg: rgba(18, 150, 196, 0.06);
    }

    /* ---------- HP ---------- */
    @media (max-width: 768px) {
        .dash-header {
            padding-bottom: 2rem !important;
        }

        .dash-header h4 {
            font-size: 1.15rem;
        }

        .dash-metric:hover,
        .dash-panel:hover {
            transform: none;
        }
    }

    /* Hormati pengguna yang mematikan animasi */
    @media (prefers-reduced-motion: reduce) {
        .dash *,
        .dash *::before,
        .dash *::after {
            animation-duration: 0.01ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: 0.01ms !important;
        }
    }
</style>
@endsection
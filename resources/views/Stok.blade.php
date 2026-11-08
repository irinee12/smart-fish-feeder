@extends('layouts.app')

@section('title', 'Stok Pakan - Smart Fish Feeder')

@section('content')
<div class="stk container-fluid px-0">

    {{-- =====================================================
        HEADER FULL WARNA
    ===================================================== --}}
    <div class="stk-header py-3 px-4 mb-4 text-white d-flex align-items-center">

        {{-- GELEMBUNG DAN GELOMBANG (VISUAL SAJA) --}}
        <div class="stk-bubbles" aria-hidden="true">
            <span style="--x:6%;  --s:12px; --d:8s;  --delay:0s;"></span>
            <span style="--x:24%; --s:8px;  --d:6s;  --delay:2s;"></span>
            <span style="--x:47%; --s:16px; --d:9s;  --delay:1s;"></span>
            <span style="--x:68%; --s:9px;  --d:7s;  --delay:3s;"></span>
            <span style="--x:86%; --s:13px; --d:8.5s;--delay:0.6s;"></span>
        </div>
        <div class="stk-waves" aria-hidden="true">
            <svg class="stk-wave stk-wave-back" viewBox="0 0 1200 60" preserveAspectRatio="none">
                <path d="M0,30 C150,60 300,0 450,30 C600,60 750,0 900,30 C1050,60 1200,0 1350,30 L1350,60 L0,60 Z"/>
            </svg>
            <svg class="stk-wave stk-wave-front" viewBox="0 0 1200 60" preserveAspectRatio="none">
                <path d="M0,30 C150,0 300,60 450,30 C600,0 750,60 900,30 C1050,0 1200,60 1350,30 L1350,60 L0,60 Z"/>
            </svg>
        </div>

        <div class="stk-header-inner d-flex align-items-center gap-3">
            <div class="stk-header-icon p-2 rounded-3">
                <i class="bi bi-box-seam fs-5 text-white"></i>
            </div>
            <div>
                <h4 class="fw-bold mb-0">Stok Pakan</h4>
                <p class="text-white text-opacity-75 mb-0 small">
                    Pantau ketersediaan stok pakan ikan secara real-time dari sensor ultrasonik.
                </p>
            </div>
        </div>
    </div>

    {{-- KONTEN UTAMA --}}
    <div class="px-4">

        {{-- ============================================= --}}
        {{-- KARTU RINGKASAN STOK (3 KOLOM) --}}
        {{-- ============================================= --}}
        <div class="row g-4 mb-4">

            {{-- Kartu Stok Awal (Kapasitas Wadah) --}}
            <div class="col-md-4">
                <div class="stk-card stk-metric stk-m1 card border-0 rounded-4 h-100 p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <span class="stk-metric-label fw-semibold small">Kapasitas Wadah</span>
                        <div class="stk-metric-icon rounded-2 p-2 d-flex align-items-center justify-content-center">
                            <i class="bi bi-box"></i>
                        </div>
                    </div>
                    <h3 class="stk-metric-value fw-bold mb-1">--- <span class="fs-6 text-muted fw-normal">kg</span></h3>
                    <p class="text-muted small mb-0">Total kapasitas (Kalibrasi Sensor)</p>
                </div>
            </div>

            {{-- Kartu Stok Saat Ini (Pembacaan Sensor Real-time) --}}
            <div class="col-md-4">
                <div class="stk-card stk-metric stk-m2 card border-0 rounded-4 h-100 p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <span class="stk-metric-label fw-semibold small">Stok Saat Ini</span>
                        <div class="stk-metric-icon rounded-2 p-2 d-flex align-items-center justify-content-center">
                            <i class="bi bi-droplet-half"></i>
                        </div>
                    </div>
                    <h3 class="stk-metric-value fw-bold mb-1">--- <span class="fs-6 text-muted fw-normal">kg</span></h3>
                    <p class="text-muted small mb-0">---% dari kapasitas</p>
                </div>
            </div>

            {{-- Kartu Total Terpakai --}}
            <div class="col-md-4">
                <div class="stk-card stk-metric stk-m3 card border-0 rounded-4 h-100 p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <span class="stk-metric-label fw-semibold small">Total Terpakai Hari Ini</span>
                        <div class="stk-metric-icon rounded-2 p-2 d-flex align-items-center justify-content-center">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </div>
                    </div>
                    <h3 class="stk-metric-value fw-bold mb-1">--- <span class="fs-6 text-muted fw-normal">kg</span></h3>
                    <p class="text-muted small mb-0">Data pengeluaran pakan</p>
                </div>
            </div>

        </div>

        {{-- ============================================= --}}
        {{-- INDIKATOR STOK PAKAN (WADAH SENSOR) --}}
        {{-- ============================================= --}}
        <div class="stk-card stk-indicator card border-0 rounded-4 mb-4 p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold mb-0 text-dark">Indikator Stok Pakan</h5>
                <span class="stk-badge-wait badge px-3 py-2 rounded-pill">Status: Menunggu Sensor</span>
            </div>

            <div class="mb-4">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted small fw-semibold">Tingkat Stok</span>
                    <span class="fw-bold text-dark small">Belum ada data</span>
                </div>
                {{-- Progress Bar (Nanti width-nya diatur dinamis dari database/sensor) --}}
                <div class="stk-progress progress rounded-pill" style="height: 12px;">
                    <div class="progress-bar bg-secondary rounded-pill" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>

            {{-- Keterangan / Legend --}}
            <div class="stk-legend row mt-4 pt-3 border-top g-3">
                <div class="stk-legend-item col-md-4 d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <div class="stk-dot rounded-circle bg-success" style="width: 10px; height: 10px;"></div>
                        <span class="text-muted small">Batas Aman</span>
                    </div>
                    <span class="badge bg-success bg-opacity-10 text-success">> 40%</span>
                </div>
                <div class="stk-legend-item stk-legend-mid col-md-4 d-flex align-items-center justify-content-between border-start border-end px-md-4">
                    <div class="d-flex align-items-center gap-2">
                        <div class="stk-dot stk-dot-warn rounded-circle bg-warning" style="width: 10px; height: 10px;"></div>
                        <span class="text-muted small">Menipis</span>
                    </div>
                    <span class="badge bg-warning bg-opacity-10 text-warning">15% - 40%</span>
                </div>
                <div class="stk-legend-item col-md-4 d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <div class="stk-dot stk-dot-danger rounded-circle bg-danger" style="width: 10px; height: 10px;"></div>
                        <span class="text-muted small">Kritis / Habis</span>
                    </div>
                    <span class="badge bg-danger bg-opacity-10 text-danger">< 15%</span>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- CSS HALAMAN STOK (diawali .stk agar tidak mengganggu halaman lain) --}}
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

    .stk {
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

    .stk h3, .stk h4, .stk h5 {
        letter-spacing: -0.02em;
    }

    @keyframes stkFloat {
        0%, 100% { transform: translateY(0); }
        50%      { transform: translateY(-5px); }
    }

    /* ---------- HEADER ---------- */
    .stk-header {
        position: relative;
        width: 100%;
        overflow: hidden;
        padding-bottom: 2.25rem !important;
        background:
            radial-gradient(circle at 15% 10%, rgba(94, 234, 212, 0.3), transparent 45%),
            linear-gradient(135deg, var(--sea-bright) 0%, var(--sea-mid) 55%, var(--sea-deep) 100%);
        box-shadow: 0 10px 24px -12px rgba(5, 46, 74, 0.5);
        animation: stkHeaderIn 0.7s ease-out both;
    }

    @keyframes stkHeaderIn {
        from { opacity: 0; transform: translateY(-16px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .stk-header-inner {
        position: relative;
        z-index: 3;
    }

    .stk-header-icon {
        background: rgba(255, 255, 255, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.3);
        backdrop-filter: blur(4px);
        animation: stkFloat 5s ease-in-out infinite;
    }

    .stk-bubbles {
        position: absolute;
        inset: 0;
        z-index: 1;
        pointer-events: none;
    }

    .stk-bubbles span {
        position: absolute;
        bottom: -20px;
        left: var(--x);
        width: var(--s);
        height: var(--s);
        border-radius: 50%;
        background: radial-gradient(circle at 30% 30%, rgba(255, 255, 255, 0.75), rgba(255, 255, 255, 0.1));
        border: 1px solid rgba(255, 255, 255, 0.4);
        animation: stkBubble var(--d) var(--delay) linear infinite;
    }

    @keyframes stkBubble {
        0%   { transform: translateY(0) translateX(0); opacity: 0; }
        15%  { opacity: 1; }
        100% { transform: translateY(-120px) translateX(12px); opacity: 0; }
    }

    .stk-waves {
        position: absolute;
        left: 0;
        right: 0;
        bottom: 0;
        height: 30px;
        z-index: 2;
        overflow: hidden;
        pointer-events: none;
    }

    .stk-wave {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 200%;
        height: 100%;
    }

    .stk-wave-back {
        fill: rgba(255, 255, 255, 0.18);
        animation: stkWave 10s linear infinite;
    }

    .stk-wave-front {
        fill: rgba(255, 255, 255, 0.3);
        height: 70%;
        animation: stkWave 6s linear infinite reverse;
    }

    @keyframes stkWave {
        from { transform: translateX(0); }
        to   { transform: translateX(-50%); }
    }

    /* ---------- KARTU UMUM ---------- */
    .stk-card {
        position: relative;
        overflow: hidden;
        background: #ffffff;
        box-shadow: 0 10px 25px -14px rgba(5, 46, 74, 0.3);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        animation: stkCardIn 0.7s cubic-bezier(0.22, 1, 0.36, 1) backwards;
    }

    @keyframes stkCardIn {
        from { opacity: 0; transform: translateY(26px) scale(0.98); }
        to   { opacity: 1; transform: translateY(0) scale(1); }
    }

    .stk-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 34px -16px rgba(5, 46, 74, 0.38);
    }

    /* ---------- 3 KARTU RINGKASAN ---------- */
    .stk-metric {
        --accent: var(--sea-bright);
        --accent-soft: rgba(18, 150, 196, 0.13);
        border-top: 4px solid var(--accent) !important;
    }

    .stk-m1 { animation-delay: 0.15s; --accent: #0a6a8f; --accent-soft: rgba(10, 106, 143, 0.13); }
    .stk-m2 { animation-delay: 0.27s; --accent: #14b8a6; --accent-soft: rgba(20, 184, 166, 0.14); }
    .stk-m3 { animation-delay: 0.39s; --accent: #f59e0b; --accent-soft: rgba(245, 158, 11, 0.15); }

    /* Lingkaran lembut di pojok kartu */
    .stk-metric::after {
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

    .stk-metric:hover::after {
        transform: scale(1.5);
    }

    .stk-metric > * {
        position: relative;
        z-index: 1;
    }

    .stk-metric-label {
        color: var(--muted);
    }

    .stk-metric-icon {
        width: 36px;
        height: 36px;
        background: var(--accent-soft);
        color: var(--accent);
        animation: stkFloat 4.5s ease-in-out infinite;
    }

    .stk-m2 .stk-metric-icon { animation-delay: 0.6s; }
    .stk-m3 .stk-metric-icon { animation-delay: 1.2s; }

    .stk-metric-value {
        color: var(--ink);
        font-weight: 800 !important;
        font-variant-numeric: tabular-nums;
    }

    .stk-m2 .stk-metric-value {
        color: var(--sea-mid);
    }

    /* ---------- INDIKATOR STOK ---------- */
    .stk-indicator {
        animation-delay: 0.5s;
    }

    /* Garis air tipis yang mengalir di atas kartu */
    .stk-indicator::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, var(--sea-bright), var(--aqua), var(--sea-bright));
        background-size: 200% 100%;
        animation: stkFlow 6s linear infinite;
    }

    @keyframes stkFlow {
        from { background-position: 0 0; }
        to   { background-position: -200% 0; }
    }

    .stk-badge-wait {
        background: rgba(245, 158, 11, 0.12);
        color: #b45309;
        font-weight: 700;
    }

    .stk-badge-wait::before {
        content: '';
        display: inline-block;
        width: 7px;
        height: 7px;
        margin-right: 7px;
        border-radius: 50%;
        background: #f59e0b;
        animation: stkPulse 1.6s ease-in-out infinite;
    }

    @keyframes stkPulse {
        0%, 100% { opacity: 1; transform: scale(1); }
        50%      { opacity: 0.35; transform: scale(1.4); }
    }

    .stk-progress {
        position: relative;
        overflow: hidden;
        background-color: #e1eef4;
    }

    .stk-progress .progress-bar {
        position: relative;
        background: linear-gradient(90deg, var(--sea-bright), var(--aqua)) !important;
        transition: width 1s cubic-bezier(0.22, 1, 0.36, 1);
    }

    /* Kilau air yang bergerak di trek progress */
    .stk-progress::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(100deg, transparent 30%, rgba(18, 150, 196, 0.25) 50%, transparent 70%);
        background-size: 200% 100%;
        animation: stkShimmer 2.8s linear infinite;
        pointer-events: none;
    }

    @keyframes stkShimmer {
        from { background-position: 200% 0; }
        to   { background-position: -200% 0; }
    }

    /* ---------- LEGEND ---------- */
    .stk-legend {
        border-color: #e3eef4 !important;
    }

    .stk-legend-mid {
        border-color: #e3eef4 !important;
    }

    .stk-legend-item {
        transition: transform 0.2s ease;
    }

    .stk-legend-item:hover {
        transform: translateY(-2px);
    }

    .stk-dot {
        flex-shrink: 0;
    }

    .stk-dot-warn   { animation: stkPulse 1.8s ease-in-out infinite; }
    .stk-dot-danger { animation: stkPulse 1.2s ease-in-out infinite; }

    .stk .badge {
        font-weight: 700;
        border-radius: 999px;
        padding: 0.4em 0.8em;
    }

    /* ---------- HP ---------- */
    @media (max-width: 767px) {
        .stk-legend-mid {
            border: none !important;
            padding-left: 0.75rem !important;
            padding-right: 0.75rem !important;
        }

        .stk-header h4 {
            font-size: 1.15rem;
        }

        .stk-card:hover {
            transform: none;
        }
    }

    /* Hormati pengguna yang mematikan animasi */
    @media (prefers-reduced-motion: reduce) {
        .stk *,
        .stk *::before,
        .stk *::after {
            animation-duration: 0.01ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: 0.01ms !important;
        }
    }
</style>
@endsection
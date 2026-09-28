@extends('layouts.app')

@section('title', 'Prediksi Refill - Smart Fish Feeder')

@section('content')
<div class="container-fluid px-0" style="background-color: #f8f9fa; min-height: 100vh;">

    {{-- =====================================================
        HEADER FULL WARNA (RAMPING & MENTOK TANPA LENGKUNGAN)
    ===================================================== --}}
    <div class="py-3 px-4 mb-4 text-white shadow-sm d-flex align-items-center" style="background: linear-gradient(135deg, #2563eb, #1d4ed8); width: 100%;">
        <div class="d-flex align-items-center gap-3">
            <div class="p-2 bg-white bg-opacity-25 rounded-3">
                <i class="bi bi-graph-up-arrow fs-5 text-white"></i>
            </div>
            <div>
                <h4 class="fw-bold mb-0">Prediksi Refill</h4>
                <p class="text-white text-opacity-75 mb-0 small">
                    Analisis dan estimasi waktu pengisian ulang pakan ikan berdasarkan konsumsi harian.
                </p>
            </div>
        </div>
    </div>

    {{-- BUNGKUS KONTEN DI BAWAHNYA DIBERI PADDING SUPAYA TIDAK IKUT MENEMPEL KE PINGGIR --}}
    <div class="px-4 pb-4">

        {{-- =========================================================
            CSS KHUSUS HALAMAN PREDIKSI REFILL (LEBIH RINGKAS & BERSIH)
        ========================================================= --}}
        <style>
            .prediction-page {
                display: flex;
                flex-direction: column;
                gap: 1.25rem;
            }

            /* --- Status Alert Minimalis (Pengganti Banner Besar) --- */
            .prediction-status-card {
                background: #ffffff;
                border: 1px solid #e2e8f0;
                border-left: 4px solid #3b82f6;
                padding: 1rem 1.25rem;
                border-radius: 10px;
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 1rem;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
            }

            .prediction-status-content {
                display: flex;
                align-items: center;
                gap: 0.85rem;
            }

            .prediction-status-icon {
                font-size: 1.25rem;
                color: #3b82f6;
                background: #eff6ff;
                padding: 0.5rem;
                border-radius: 8px;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .prediction-status-text h6 {
                font-size: 0.95rem;
                font-weight: 600;
                color: #1e293b;
                margin-bottom: 0.1rem;
            }

            .prediction-status-text p {
                font-size: 0.8rem;
                color: #64748b;
                margin-bottom: 0;
            }

            /* --- Summary Metrics Grid --- */
            .prediction-metrics-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
                gap: 1.25rem;
            }

            .prediction-metric-card {
                padding: 1.25rem 1.5rem;
                display: flex;
                flex-direction: column;
                gap: 0.5rem;
                border-radius: 12px;
                background: #ffffff;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02), 0 1px 6px rgba(0, 0, 0, 0.04);
                border: 1px solid #f1f5f9;
                transition: transform 0.2s ease;
            }

            .prediction-metric-card:hover {
                transform: translateY(-2px);
            }

            .prediction-metric-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                font-size: 0.875rem;
                font-weight: 500;
                color: #64748b;
            }

            .prediction-icon {
                width: 36px;
                height: 36px;
                border-radius: 8px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1rem;
            }

            .prediction-icon-blue { background-color: #eff6ff; color: #3b82f6; }
            .prediction-icon-yellow { background-color: #fefce8; color: #eab308; }
            .prediction-icon-green { background-color: #f0fdf4; color: #22c55e; }

            .prediction-metric-value {
                font-size: 1.75rem;
                font-weight: 700;
                color: #0f172a;
                display: flex;
                align-items: baseline;
                gap: 0.35rem;
            }

            .prediction-metric-value span {
                font-size: 0.875rem;
                font-weight: 500;
                color: #64748b;
            }

            .prediction-metric-sub {
                font-size: 0.75rem;
                color: #94a3b8;
            }

            /* --- Chart & Content Cards --- */
            .prediction-chart-card {
                padding: 1.5rem;
                background: #ffffff;
                border-radius: 12px;
                border: 1px solid #f1f5f9;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
            }

            .prediction-chart-title {
                font-size: 1rem;
                font-weight: 600;
                color: #1e293b;
                margin-bottom: 0.25rem;
            }

            .prediction-chart-description {
                font-size: 0.8125rem;
                color: #64748b;
                margin-bottom: 1.25rem;
            }

            .prediction-empty-chart {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                text-align: center;
                padding: 2.5rem 1rem;
                background-color: #f8fafc;
                border: 2px dashed #e2e8f0;
                border-radius: 10px;
                color: #64748b;
                gap: 0.4rem;
            }

            .prediction-empty-chart-small {
                padding: 1.75rem 1rem;
            }

            .prediction-empty-icon {
                font-size: 2.2rem;
                margin-bottom: 0.2rem;
            }

            .prediction-empty-chart strong {
                font-size: 0.9rem;
                color: #1e293b;
            }

            .prediction-empty-chart span {
                font-size: 0.78rem;
                color: #94a3b8;
                max-width: 350px;
            }

            .prediction-warning {
                display: flex;
                align-items: center;
                gap: 0.5rem;
                margin-top: 1rem;
                padding: 0.65rem 0.85rem;
                background-color: #fffbeb;
                border: 1px solid #fef3c7;
                border-radius: 8px;
                font-size: 0.78rem;
                color: #92400e;
            }
        </style>

        <div class="prediction-page">

            {{-- =====================================================
                STATUS ALERT MINIMALIS (PENGGANTI BANNER BESAR)
            ===================================================== --}}
            <div class="prediction-status-card">
                <div class="prediction-status-content">
                    <div class="prediction-status-icon">
                        <i class="bi bi-info-circle"></i>
                    </div>
                    <div class="prediction-status-text">
                        <h6>Status Prediksi: Belum Cukup Data</h6>
                        <p>Sistem akan menghitung waktu refill otomatis setelah data stok dan riwayat penggunaan harian tersedia.</p>
                    </div>
                </div>
            </div>

            {{-- =====================================================
                SUMMARY METRICS
            ===================================================== --}}
            <div class="prediction-metrics-grid">
                <div class="card prediction-metric-card">
                    <div class="prediction-metric-header">
                        <span>Rata-rata Pakan / Hari</span>
                        <div class="prediction-icon prediction-icon-blue">📊</div>
                    </div>
                    <div class="prediction-metric-value">— <span>kg</span></div>
                    <div class="prediction-metric-sub">Belum ada data penggunaan</div>
                </div>

                <div class="card prediction-metric-card">
                    <div class="prediction-metric-header">
                        <span>Hari Tersisa</span>
                        <div class="prediction-icon prediction-icon-yellow">🕒</div>
                    </div>
                    <div class="prediction-metric-value">— <span>hari</span></div>
                    <div class="prediction-metric-sub">Belum dapat dihitung</div>
                </div>

                <div class="card prediction-metric-card">
                    <div class="prediction-metric-header">
                        <span>Rekomendasi Refill</span>
                        <div class="prediction-icon prediction-icon-green">📈</div>
                    </div>
                    <div class="prediction-metric-value">—</div>
                    <div class="prediction-metric-sub">Menunggu data penggunaan</div>
                </div>
            </div>

            {{-- =====================================================
                GRAFIK PROYEKSI STOK
            ===================================================== --}}
            <div class="card prediction-chart-card">
                <div class="prediction-chart-title">Grafik Proyeksi Stok Pakan</div>
                <div class="prediction-chart-description">Histori dan prediksi stok pakan akan ditampilkan setelah data penggunaan tersedia.</div>

                <div class="prediction-empty-chart">
                    <div class="prediction-empty-icon">📈</div>
                    <strong>Belum ada data proyeksi</strong>
                    <span>Grafik akan muncul setelah sistem memiliki data stok dan riwayat penggunaan pakan.</span>
                </div>

                <div class="prediction-warning">
                    <span>⚠️</span>
                    <span>Prediksi merupakan estimasi berdasarkan pola penggunaan pakan yang tercatat pada sistem. Hasil aktual dapat berbeda.</span>
                </div>
            </div>

            {{-- =====================================================
                RIWAYAT PENGGUNAAN PAKAN
            ===================================================== --}}
            <div class="card prediction-chart-card">
                <div class="chart-title-wrapper mb-2">
                    <div class="prediction-chart-title">Riwayat Penggunaan Pakan</div>
                    <div class="prediction-chart-description">Konsumsi pakan harian akan digunakan sebagai dasar perhitungan prediksi refill.</div>
                </div>

                <div class="prediction-empty-chart prediction-empty-chart-small">
                    <div class="prediction-empty-icon">📊</div>
                    <strong>Belum ada data penggunaan</strong>
                    <span>Data konsumsi pakan akan tampil setelah pemberian pakan tercatat di sistem.</span>
                </div>
            </div>

        </div>

    </div>
</div>
@endsection
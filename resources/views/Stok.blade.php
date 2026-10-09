@extends('layouts.app')

@section('title', 'Stok Pakan - Smart Fish Feeder')

@section('content')

<div class="container-fluid px-0" style="background-color: #f8f9fa; min-height: 100vh;">

    {{-- HEADER --}}
    <div class="py-3 px-4 mb-4 text-white shadow-sm d-flex align-items-center"
         style="background: linear-gradient(135deg, #2563eb, #1d4ed8); width: 100%;">

        <div class="d-flex align-items-center gap-3">
            <div class="p-2 bg-white bg-opacity-25 rounded-3">
                <i class="bi bi-box-seam fs-5 text-white"></i>
            </div>

            <div>
                <h4 class="fw-bold mb-0">Stok Pakan</h4>
                <p class="text-white text-opacity-75 mb-0 small">
                    Pantau ketersediaan stok pakan ikan.
                </p>
            </div>
        </div>
    </div>

    <div class="px-4 pb-4">

        <style>
            .stock-page {
                display: flex;
                flex-direction: column;
                gap: 1.5rem;
                padding-bottom: 2rem;
            }

            /* SUMMARY STOK */
            .stock-summary-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
                gap: 1.25rem;
            }

            .stock-metric-card {
                padding: 1.25rem 1.5rem;
                display: flex;
                flex-direction: column;
                gap: 0.5rem;
                border-radius: 12px;
                background: #ffffff;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02),
                            0 1px 6px rgba(0, 0, 0, 0.04);
                border: 1px solid #f1f5f9;
                transition: transform 0.2s ease, box-shadow 0.2s ease;
            }

            .stock-metric-card:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
            }

            .stock-metric-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                font-size: 0.875rem;
                font-weight: 500;
                color: #64748b;
            }

            .stock-icon {
                width: 36px;
                height: 36px;
                border-radius: 8px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1rem;
                flex-shrink: 0;
            }

            .stock-icon-blue {
                background-color: #eff6ff;
                color: #3b82f6;
            }

            .stock-icon-green {
                background-color: #f0fdf4;
                color: #22c55e;
            }

            .stock-icon-yellow {
                background-color: #fefce8;
                color: #eab308;
            }

            .stock-metric-value {
                font-size: 1.75rem;
                font-weight: 700;
                color: #0f172a;
                display: flex;
                align-items: baseline;
                gap: 0.35rem;
            }

            .stock-metric-value span {
                font-size: 0.875rem;
                font-weight: 500;
                color: #64748b;
            }

            .stock-metric-sub {
                font-size: 0.75rem;
                color: #94a3b8;
            }

            /* GRID INDIKATOR STOK: SATU KOLOM */
            .stock-main-grid {
                display: grid;
                grid-template-columns: minmax(0, 1fr);
                gap: 1.5rem;
                width: 100%;
            }

            .stock-card-title {
                font-size: 1rem;
                font-weight: 600;
                color: #1e293b;
                margin-bottom: 1.25rem;
            }

            /* INDIKATOR STOK */
            .stock-indicator-card {
                width: 100%;
                min-width: 0;
                padding: 1.5rem;
                background: #ffffff;
                border-radius: 12px;
                border: 1px solid #f1f5f9;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
            }

            .stock-level {
                display: flex;
                flex-direction: column;
                gap: 0.75rem;
                margin-bottom: 1.5rem;
            }

            .stock-level-info {
                display: flex;
                justify-content: space-between;
                align-items: center;
                gap: 0.75rem;
                font-size: 0.875rem;
                color: #475569;
            }

            .stock-level-info strong {
                text-align: right;
            }

            .stock-progress {
                width: 100%;
                height: 10px;
                background-color: #f1f5f9;
                border-radius: 6px;
                overflow: hidden;
            }

            .stock-progress-fill {
                height: 100%;
                width: 0%;
                background-color: #3b82f6;
                border-radius: 6px;
                transition: width 0.4s ease;
            }

            /* STATUS STOK */
            .stock-status-list {
                display: flex;
                flex-direction: column;
                gap: 0.75rem;
                border-top: 1px solid #f1f5f9;
                padding-top: 1rem;
            }

            .stock-status-item {
                display: flex;
                align-items: center;
                gap: 0.75rem;
                font-size: 0.875rem;
                color: #334155;
            }

            .stock-status-item span:nth-child(2) {
                flex: 1;
            }

            .stock-status-dot {
                width: 10px;
                height: 10px;
                border-radius: 50%;
                flex-shrink: 0;
            }

            .status-safe {
                background-color: #22c55e;
            }

            .status-warning {
                background-color: #f59e0b;
            }

            .status-danger {
                background-color: #ef4444;
            }

            /* BADGE STATUS */
            .badge {
                display: inline-block;
                padding: 0.25rem 0.6rem;
                font-size: 0.75rem;
                font-weight: 600;
                border-radius: 6px;
                white-space: nowrap;
            }

            .badge-green {
                background-color: #f0fdf4;
                color: #16a34a;
            }

            .badge-amber {
                background-color: #fffbeb;
                color: #d97706;
            }

            .badge-red {
                background-color: #fef2f2;
                color: #dc2626;
            }

            /* RIWAYAT PENGISIAN STOK */
            .stock-history-card {
                width: 100%;
                min-width: 0;
                padding: 1.5rem;
                background: #ffffff;
                border-radius: 12px;
                border: 1px solid #f1f5f9;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
            }

            .stock-history-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 1rem;
            }

            .stock-history-description {
                font-size: 0.8125rem;
                color: #64748b;
            }

            .table-wrapper {
                width: 100%;
                overflow-x: auto;
            }

            .stock-table {
                width: 100%;
                border-collapse: collapse;
                text-align: left;
                font-size: 0.875rem;
            }

            .stock-table th {
                padding: 0.75rem 1rem;
                background-color: #f8fafc;
                color: #475569;
                font-weight: 600;
                border-bottom: 1px solid #e2e8f0;
            }

            .stock-table td {
                padding: 0.875rem 1rem;
                border-bottom: 1px solid #f1f5f9;
                color: #334155;
            }

            .empty-table {
                text-align: center;
                padding: 3rem 1rem !important;
                color: #64748b;
            }

            .empty-table-icon {
                font-size: 2.5rem;
                margin-bottom: 0.5rem;
            }

            .empty-table strong {
                display: block;
                font-size: 0.95rem;
                color: #1e293b;
                margin-bottom: 0.25rem;
            }

            .empty-table span {
                font-size: 0.8125rem;
                color: #94a3b8;
            }

            /* RESPONSIVE TABLET */
            @media (max-width: 992px) {
                .stock-main-grid {
                    grid-template-columns: minmax(0, 1fr);
                }
            }

            /* RESPONSIVE MOBILE */
            @media (max-width: 576px) {
                .stock-page {
                    gap: 1rem;
                    padding-bottom: 1.5rem;
                }

                .stock-summary-grid {
                    grid-template-columns: minmax(0, 1fr);
                    gap: 1rem;
                }

                .stock-metric-card {
                    padding: 1rem 1.25rem;
                }

                .stock-metric-value {
                    font-size: 1.5rem;
                }

                .stock-indicator-card,
                .stock-history-card {
                    padding: 1rem;
                }

                .stock-card-title {
                    margin-bottom: 1rem;
                }

                .stock-level-info {
                    flex-wrap: wrap;
                }

                .stock-status-item {
                    gap: 0.5rem;
                }

                .stock-table {
                    min-width: 400px;
                }

                .stock-history-description {
                    line-height: 1.5;
                }
            }
        </style>

        <div class="stock-page">

            {{-- SUMMARY STOK --}}
            <div class="stock-summary-grid">

                <div class="card stock-metric-card">
                    <div class="stock-metric-header">
                        <span>Stok Awal</span>
                        <div class="stock-icon stock-icon-blue">📦</div>
                    </div>

                    <div class="stock-metric-value">
                        — <span>kg</span>
                    </div>

                    <div class="stock-metric-sub">Belum ada data</div>
                </div>

                <div class="card stock-metric-card">
                    <div class="stock-metric-header">
                        <span>Stok Saat Ini</span>
                        <div class="stock-icon stock-icon-green">🐟</div>
                    </div>

                    <div class="stock-metric-value">
                        — <span>kg</span>
                    </div>

                    <div class="stock-metric-sub">—% dari kapasitas</div>
                </div>

                <div class="card stock-metric-card">
                    <div class="stock-metric-header">
                        <span>Total Terpakai</span>
                        <div class="stock-icon stock-icon-yellow">↻</div>
                    </div>

                    <div class="stock-metric-value">
                        — <span>kg</span>
                    </div>

                    <div class="stock-metric-sub">Belum ada data</div>
                </div>

            </div>

            {{-- INDIKATOR STOK --}}
            <div class="stock-main-grid">

                <div class="card stock-indicator-card">

                    <div class="stock-card-title">
                        Indikator Stok Pakan
                    </div>

                    <div class="stock-level">

                        <div class="stock-level-info">
                            <span>Tingkat Stok</span>
                            <strong>Belum ada data</strong>
                        </div>

                        <div class="stock-progress">
                            <div class="stock-progress-fill"></div>
                        </div>

                    </div>

                    <div class="stock-status-list">

                        <div class="stock-status-item">
                            <div class="stock-status-dot status-safe"></div>
                            <span>Batas Aman</span>
                            <span class="badge badge-green">&gt; 40%</span>
                        </div>

                        <div class="stock-status-item">
                            <div class="stock-status-dot status-warning"></div>
                            <span>Menipis</span>
                            <span class="badge badge-amber">15% – 40%</span>
                        </div>

                        <div class="stock-status-item">
                            <div class="stock-status-dot status-danger"></div>
                            <span>Kritis / Habis</span>
                            <span class="badge badge-red">&lt; 15%</span>
                        </div>

                    </div>
                </div>

            </div>

            {{-- RIWAYAT PENGISIAN STOK --}}
            <div class="card stock-history-card">

                <div class="stock-history-header">
                    <div>
                        <div class="stock-card-title">
                            Riwayat Pengisian Stok
                        </div>

                        <div class="stock-history-description">
                            Riwayat penambahan stok pakan
                        </div>
                    </div>
                </div>

                <div class="table-wrapper">
                    <table class="table stock-table">

                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Jumlah</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td colspan="3" class="empty-table">
                                    <div class="empty-table-icon">📦</div>
                                    <strong>Belum ada riwayat stok</strong>
                                    <span>
                                        Data akan tampil setelah stok ditambahkan dan database terhubung.
                                    </span>
                                </td>
                            </tr>
                        </tbody>

                    </table>
                </div>

            </div>

        </div>
    </div>
</div>

@endsection

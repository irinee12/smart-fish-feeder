@extends('layouts.app')

@section('title', 'Riwayat Pakan - Smart Fish Feeder')

@section('content')
<div class="container-fluid px-0" style="background-color: #f8f9fa; min-height: 100vh;">

    {{-- =====================================================
        HEADER FULL WARNA (RAMPING & MENTOK TANPA LENGKUNGAN)
    ===================================================== --}}
    <div class="py-3 px-4 mb-4 text-white shadow-sm d-flex align-items-center" style="background: linear-gradient(135deg, #2563eb, #1d4ed8); width: 100%;">
        <div class="d-flex align-items-center gap-3">
            <div class="p-2 bg-white bg-opacity-25 rounded-3">
                <i class="bi bi-clock-history fs-5 text-white"></i>
            </div>
            <div>
                <h4 class="fw-bold mb-0">Riwayat Pakan</h4>
                <p class="text-white text-opacity-75 mb-0 small">
                    Log dan riwayat pemberian pakan yang telah tercatat pada sistem.
                </p>
            </div>
        </div>
    </div>

    {{-- BUNGKUS KONTEN DI BAWAHNYA DIBERI PADDING SUPAYA TIDAK IKUT MENEMPEL KE PINGGIR --}}
    <div class="px-4 pb-4">

        {{-- =========================================================
            CUSTOM STYLING (STRUKTUR REFERENSI + KONDISI KOSONG)
        ========================================================= --}}
        <style>
            .history-page {
                display: flex;
                flex-direction: column;
                gap: 1.5rem;
                text-align: left;
            }

            /* --- 1. FILTER & EXPORT CARD --- */
            .history-filter-card {
                padding: 1.25rem 1.5rem;
                background: #ffffff;
                border-radius: 12px;
                border: 1px solid #e2e8f0;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
                display: flex;
                justify-content: space-between;
                align-items: flex-end;
                flex-wrap: wrap;
                gap: 1rem;
            }

            .history-filter-left {
                display: flex;
                flex-direction: column;
                gap: 0.5rem;
            }

            .form-label-custom {
                font-size: 0.75rem;
                font-weight: 700;
                color: #64748b;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                margin: 0;
            }

            .form-input-custom {
                padding: 0.5rem 0.75rem;
                font-size: 0.875rem;
                border: 1px solid #cbd5e1;
                border-radius: 8px;
                outline: none;
                background-color: #ffffff;
                color: #1e293b;
                transition: border-color 0.2s, box-shadow 0.2s;
                width: 260px;
            }

            .form-input-custom:focus {
                border-color: #3b82f6;
                box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
            }

            .history-export-icon {
                font-size: 1.1rem;
                line-height: 1;
                font-weight: bold;
            }

            /* --- 2. GRAFIK & TABEL KARTU --- */
            .history-chart-card,
            .history-table-card {
                padding: 1.5rem;
                background: #ffffff;
                border-radius: 12px;
                border: 1px solid #e2e8f0;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
            }

            .history-card-title {
                font-size: 1rem;
                font-weight: 600;
                color: #1e293b;
                margin-bottom: 0.2rem;
            }

            .history-card-description {
                font-size: 0.8125rem;
                color: #64748b;
                margin-bottom: 1.25rem;
            }

            /* Empty Chart State */
            .history-empty-chart {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                text-align: center;
                padding: 3rem 1rem;
                background-color: #f8fafc;
                border: 2px dashed #e2e8f0;
                border-radius: 10px;
                color: #64748b;
                gap: 0.4rem;
            }

            .history-empty-icon {
                font-size: 2.5rem;
                margin-bottom: 0.25rem;
            }

            .history-empty-chart strong {
                font-size: 0.95rem;
                color: #1e293b;
            }

            .history-empty-chart span {
                font-size: 0.8125rem;
                color: #94a3b8;
                max-width: 350px;
            }

            /* --- 3. TABEL RIWAYAT --- */
            .history-table-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 1rem;
                flex-wrap: wrap;
                gap: 0.5rem;
            }

            .history-data-count {
                font-size: 0.8125rem;
                color: #64748b;
                font-weight: 500;
            }

            .table-wrapper {
                overflow-x: auto;
            }

            .history-table {
                width: 100%;
                border-collapse: collapse;
                text-align: left;
                font-size: 0.875rem;
            }

            .history-table th {
                padding: 0.75rem 1rem;
                background-color: transparent;
                color: #64748b;
                font-weight: 700;
                font-size: 0.75rem;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                border-bottom: 1px solid #e2e8f0;
            }

            .history-table td {
                padding: 0.875rem 1rem;
                border-bottom: 1px solid #f1f5f9;
                color: #334155;
            }

            /* Empty Table State */
            .history-empty-table {
                text-align: center !important;
                padding: 3rem 1rem !important;
                color: #64748b;
            }

            .history-empty-table-icon {
                font-size: 2.5rem;
                margin-bottom: 0.5rem;
            }

            .history-empty-table strong {
                display: block;
                font-size: 0.95rem;
                color: #1e293b;
                margin-bottom: 0.25rem;
            }

            .history-empty-table span {
                font-size: 0.8125rem;
                color: #94a3b8;
            }
        </style>

        <div class="history-page">

            {{-- 1. FILTER TANGGAL + TOMBOL EXPORT PDF --}}
            <div class="history-filter-card">
                <div class="history-filter-left">
                    <label class="form-label-custom">Filter Tanggal</label>
                    <input type="date" class="form-input-custom" id="filterTanggal">
                </div>

                <a href="#" class="btn btn-primary d-flex align-items-center gap-2">
                    <span class="history-export-icon">↓</span>
                    <span>Export PDF</span>
                </a>
            </div>

            {{-- 2. GRAFIK KARTU (KONDISI KOSONG) --}}
            <div class="history-chart-card">
                <div class="history-card-title">Grafik Penggunaan Pakan</div>
                <div class="history-card-description">Total pakan per hari (kg)</div>

                <div class="history-empty-chart">
                    <div class="history-empty-icon">📊</div>
                    <strong>Belum ada data penggunaan pakan</strong>
                    <span>Grafik akan tampil setelah data pemberian pakan tersedia.</span>
                </div>
            </div>

            {{-- 3. TABEL RIWAYAT (KONDISI KOSONG) --}}
            <div class="history-table-card">
                <div class="history-table-header">
                    <div class="history-card-title" style="margin-bottom: 0;">Riwayat Pemberian Pakan</div>
                    <span class="history-data-count">0 data ditemukan</span>
                </div>

                <div class="table-wrapper">
                    <table class="history-table">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Waktu</th>
                                <th>Porsi</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="4" class="history-empty-table">
                                    <div class="history-empty-table-icon">📋</div>
                                    <strong>Belum ada riwayat pakan</strong>
                                    <span>Data akan muncul setelah sistem melakukan pemberian pakan.</span>
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
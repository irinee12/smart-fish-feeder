@extends('layouts.app')

@section('title', 'Stok Pakan - Smart Fish Feeder')

@section('content')
<div class="container-fluid px-0" style="background-color: #f8f9fa; min-height: 100vh;">

    {{-- =====================================================
        HEADER FULL WARNA
    ===================================================== --}}
    <div class="py-3 px-4 mb-4 text-white shadow-sm d-flex align-items-center" style="background: linear-gradient(135deg, #2563eb, #1d4ed8); width: 100%;">
        <div class="d-flex align-items-center gap-3">
            <div class="p-2 bg-white bg-opacity-25 rounded-3">
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
                <div class="card border-0 shadow-sm rounded-4 h-100 p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <span class="text-muted fw-semibold small">Kapasitas Wadah</span>
                        <div class="bg-secondary bg-opacity-10 text-secondary rounded-2 p-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                            <i class="bi bi-box"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-dark mb-1">--- <span class="fs-6 text-muted fw-normal">kg</span></h3>
                    <p class="text-muted small mb-0">Total kapasitas (Kalibrasi Sensor)</p>
                </div>
            </div>

            {{-- Kartu Stok Saat Ini (Pembacaan Sensor Real-time) --}}
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 h-100 p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <span class="text-muted fw-semibold small">Stok Saat Ini</span>
                        <div class="bg-primary bg-opacity-10 text-primary rounded-2 p-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                            <i class="bi bi-droplet-half"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-primary mb-1">--- <span class="fs-6 fw-normal">kg</span></h3>
                    <p class="text-muted small mb-0">---% dari kapasitas</p>
                </div>
            </div>

            {{-- Kartu Total Terpakai --}}
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 h-100 p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <span class="text-muted fw-semibold small">Total Terpakai Hari Ini</span>
                        <div class="bg-warning bg-opacity-10 text-warning rounded-2 p-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-dark mb-1">--- <span class="fs-6 text-muted fw-normal">kg</span></h3>
                    <p class="text-muted small mb-0">Data pengeluaran pakan</p>
                </div>
            </div>

        </div>

        {{-- ============================================= --}}
        {{-- INDIKATOR STOK PAKAN (WADAH SENSOR) --}}
        {{-- ============================================= --}}
        <div class="card border-0 shadow-sm rounded-4 mb-4 p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold mb-0 text-dark">Indikator Stok Pakan</h5>
                <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2 rounded-pill">Status: Menunggu Sensor</span>
            </div>

            <div class="mb-4">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted small fw-semibold">Tingkat Stok</span>
                    <span class="fw-bold text-dark small">Belum ada data</span>
                </div>
                {{-- Progress Bar (Nanti width-nya diatur dinamis dari database/sensor) --}}
                <div class="progress rounded-pill" style="height: 12px; background-color: #e2e8f0;">
                    <div class="progress-bar bg-secondary rounded-pill" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>

            {{-- Keterangan / Legend --}}
            <div class="row mt-4 pt-3 border-top g-3">
                <div class="col-md-4 d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-circle bg-success" style="width: 10px; height: 10px;"></div>
                        <span class="text-muted small">Batas Aman</span>
                    </div>
                    <span class="badge bg-success bg-opacity-10 text-success">> 40%</span>
                </div>
                <div class="col-md-4 d-flex align-items-center justify-content-between border-start border-end px-md-4">
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-circle bg-warning" style="width: 10px; height: 10px;"></div>
                        <span class="text-muted small">Menipis</span>
                    </div>
                    <span class="badge bg-warning bg-opacity-10 text-warning">15% - 40%</span>
                </div>
                <div class="col-md-4 d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-circle bg-danger" style="width: 10px; height: 10px;"></div>
                        <span class="text-muted small">Kritis / Habis</span>
                    </div>
                    <span class="badge bg-danger bg-opacity-10 text-danger">< 15%</span>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
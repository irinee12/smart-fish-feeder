@extends('layouts.app')

@section('title', 'Dashboard Smart Fish Feeder')

@section('content')
{{-- Menghilangkan padding container agar header bisa mentok full dari kiri ke kanan --}}
<div class="container-fluid px-0" style="background-color: #f8f9fa; min-height: 100vh;">

    {{-- =====================================================
        HEADER FULL WARNA (MENTOK TANPA LENGKUNGAN)
    ===================================================== --}}
    <div class="py-3 px-4 mb-4 text-white shadow-sm d-flex align-items-center justify-content-between" style="background: linear-gradient(135deg, #2563eb, #1d4ed8); width: 100%;">
        <div class="d-flex align-items-center gap-3">
            <div class="p-2 bg-white bg-opacity-25 rounded-3">
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
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body d-flex flex-wrap justify-content-between align-items-center p-3 p-md-4">
                <div class="d-flex align-items-center gap-3 mb-2 mb-md-0">
                    <div class="d-flex align-items-center gap-2">
                        <span class="text-warning" style="font-size: 0.8rem;">●</span>
                        <span class="fw-bold text-secondary">Status Alat IoT</span>
                    </div>
                    <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3 py-2 fw-normal">Menunggu Koneksi</span>
                    <span class="text-muted small d-none d-md-inline">ESP32 Belum terhubung - IP: Memuat...</span>
                </div>
                <div class="text-muted small d-flex align-items-center gap-2">
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
                <div class="card h-100 border-0 shadow-sm p-4 rounded-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-uppercase fw-bold text-secondary" style="font-size: 0.75rem; letter-spacing: 0.5px;">Stok Pakan Saat Ini</span>
                        <div class="bg-secondary bg-opacity-10 text-secondary rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
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
                <div class="card h-100 border-0 shadow-sm p-4 rounded-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-uppercase fw-bold text-secondary" style="font-size: 0.75rem; letter-spacing: 0.5px;">Pakan Hari Ini</span>
                        <div class="bg-secondary bg-opacity-10 text-secondary rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
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
                <div class="card h-100 border-0 shadow-sm p-4 rounded-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-uppercase fw-bold text-secondary" style="font-size: 0.75rem; letter-spacing: 0.5px;">Jadwal Berikutnya</span>
                        <div class="bg-secondary bg-opacity-10 text-secondary rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
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
                <div class="card h-100 border-0 shadow-sm p-4 rounded-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-uppercase fw-bold text-secondary" style="font-size: 0.75rem; letter-spacing: 0.5px;">Prediksi Refill</span>
                        <div class="bg-secondary bg-opacity-10 text-secondary rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
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
                <div class="card h-100 border-0 shadow-sm p-4 rounded-4">
                    <h5 class="fw-bold mb-1 text-dark">Grafik Penggunaan Pakan Harian</h5>
                    <p class="text-muted small mb-4">7 hari terakhir (dalam kg)</p>
                    
                    {{-- Tampilan Kosong Grafik --}}
                    <div class="w-100 d-flex flex-column align-items-center justify-content-center rounded-3" style="min-height: 250px; background-color: #fbfbfc; border: 1px dashed #dee2e6;">
                        <i class="bi bi-bar-chart text-muted mb-2" style="font-size: 2rem;"></i>
                        <span class="text-muted">Belum ada data statistik untuk ditampilkan</span>
                    </div>
                </div>
            </div>

            {{-- INDIKATOR STOK KOSONG --}}
            <div class="col-12 col-lg-4">
                <div class="card h-100 border-0 shadow-sm p-4 rounded-4">
                    <h5 class="fw-bold mb-4 text-dark">Indikator Stok</h5>
                    
                    <div class="text-center mb-4">
                        <h1 class="fw-bold text-secondary mb-0" style="font-size: 3rem;">0</h1>
                        <p class="text-muted mb-0">kg tersisa</p>
                    </div>

                    <div class="d-flex justify-content-between small mb-2">
                        <span class="text-muted">Tingkat Stok</span>
                        <span class="fw-bold text-secondary">Tidak diketahui</span>
                    </div>
                    
                    {{-- Progress bar di-set 0% --}}
                    <div class="progress mb-4" style="height: 8px;">
                        <div class="progress-bar bg-secondary" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>

                    <div class="d-flex justify-content-between border-bottom pb-2 mb-2 small">
                        <span class="text-muted">Kapasitas penuh</span>
                        <span class="fw-bold text-dark">20 kg</span>
                    </div>
                    <div class="d-flex justify-content-between border-bottom pb-2 mb-2 small">
                        <span class="text-muted">Terpakai</span>
                        <span class="fw-bold text-dark">0 kg</span>
                    </div>
                    <div class="d-flex justify-content-between small">
                        <span class="text-muted">Rata-rata/hari</span>
                        <span class="fw-bold text-dark">0 kg</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- =====================================================
            JADWAL PEMBERIAN PAKAN HARI INI (TABEL KOSONG)
        ===================================================== --}}
        <div class="card border-0 shadow-sm p-4 rounded-4 mb-4">
            <h5 class="fw-bold mb-4 text-dark">Jadwal Pemberian Pakan Hari Ini</h5>
            
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr class="text-muted small text-uppercase" style="border-bottom: 2px solid #f8f9fa;">
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
                                    <i class="bi bi-calendar-x mb-2" style="font-size: 2rem;"></i>
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
@endsection
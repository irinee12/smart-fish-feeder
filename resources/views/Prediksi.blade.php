@extends('layouts.app')

@section('title', 'Prediksi Refill - Smart Fish Feeder')

@section('content')

{{-- CSS TAMPILAN (dibawa langsung di dalam file ini) --}}
<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

/* ==========================================
   PREDIKSI REFILL - TAMPILAN (TEMA LAUT, VERSI TENANG)
   Simpan di: public/css/prediksi.css
   ========================================== */

.prediction-page {
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

@keyframes prdFloat {
    0%, 100% { transform: translateY(0); }
    50%      { transform: translateY(-5px); }
}

/* ---------- HEADER FULL LEBAR ---------- */
.prediction-hero {
    position: relative;
    width: 100%;
    overflow: hidden;
    display: flex;
    align-items: center;
    padding: 1rem 1.5rem 2.25rem;
    margin-bottom: 1.5rem;
    color: #ffffff;
    background:
        radial-gradient(circle at 15% 10%, rgba(94, 234, 212, 0.3), transparent 45%),
        linear-gradient(135deg, var(--sea-bright) 0%, var(--sea-mid) 55%, var(--sea-deep) 100%);
    box-shadow: 0 10px 24px -12px rgba(5, 46, 74, 0.5);
    animation: prdHeroIn 0.7s ease-out both;
}

@keyframes prdHeroIn {
    from { opacity: 0; transform: translateY(-16px); }
    to   { opacity: 1; transform: translateY(0); }
}

.prediction-hero-inner {
    position: relative;
    z-index: 3;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.prediction-hero-icon {
    padding: 0.5rem 0.65rem;
    border-radius: 12px;
    background: rgba(255, 255, 255, 0.2);
    border: 1px solid rgba(255, 255, 255, 0.3);
    backdrop-filter: blur(4px);
    font-size: 1.25rem;
    line-height: 1;
    animation: prdFloat 5s ease-in-out infinite;
}

.prediction-hero h4 {
    margin: 0;
    font-weight: 800;
    letter-spacing: -0.02em;
}

.prediction-hero p {
    margin: 0;
    font-size: 0.875rem;
    color: rgba(255, 255, 255, 0.8);
}

.prediction-bubbles {
    position: absolute;
    inset: 0;
    z-index: 1;
    pointer-events: none;
}

.prediction-bubbles span {
    position: absolute;
    bottom: -20px;
    left: var(--x);
    width: var(--s);
    height: var(--s);
    border-radius: 50%;
    background: radial-gradient(circle at 30% 30%, rgba(255, 255, 255, 0.75), rgba(255, 255, 255, 0.1));
    border: 1px solid rgba(255, 255, 255, 0.4);
    animation: prdBubble var(--d) var(--delay) linear infinite;
}

@keyframes prdBubble {
    0%   { transform: translateY(0) translateX(0); opacity: 0; }
    15%  { opacity: 1; }
    100% { transform: translateY(-120px) translateX(12px); opacity: 0; }
}

.prediction-waves {
    position: absolute;
    left: 0;
    right: 0;
    bottom: 0;
    height: 30px;
    z-index: 2;
    overflow: hidden;
    pointer-events: none;
}

.prediction-wave {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 200%;
    height: 100%;
}

.prediction-wave-back {
    fill: rgba(255, 255, 255, 0.18);
    animation: prdWave 10s linear infinite;
}

.prediction-wave-front {
    fill: rgba(255, 255, 255, 0.3);
    height: 70%;
    animation: prdWave 6s linear infinite reverse;
}

@keyframes prdWave {
    from { transform: translateX(0); }
    to   { transform: translateX(-50%); }
}

/* ---------- WADAH KONTEN ---------- */
.prediction-container {
    padding: 0 1.5rem 1rem;
}

/* ---------- STATUS PREDIKSI ---------- */
.prediction-status-card {
    background: #eef7fb;
    border: 1px solid #cfe5ee;
    border-radius: 12px;
    padding: 18px 22px;
    margin-bottom: 20px;
}

.prediction-status-card h5 {
    font-weight: 700;
    color: var(--sea-mid);
    margin-bottom: 6px;
}

.prediction-status-card p {
    color: #36566b;
    margin-bottom: 0;
}

/* ---------- 3 KARTU RINGKASAN ---------- */
.prediction-metrics-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
    margin-bottom: 20px;
}

.prediction-metric-card {
    background: #ffffff;
    border: 1px solid #e1ecf2;
    border-radius: 12px;
    padding: 20px 22px;
    box-shadow: 0 1px 3px rgba(5, 46, 74, 0.06);
}

.prediction-metric-card h6 {
    color: var(--muted);
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 10px;
}

.prediction-metric-card h3 {
    font-size: 26px;
    font-weight: 700;
    color: var(--ink);
    margin-bottom: 6px;
    font-variant-numeric: tabular-nums;
}

.prediction-metric-card p {
    font-size: 12px;
    color: #7a93a3;
    margin-bottom: 0;
}

/* ---------- KARTU BAGIAN (INFORMASI, GRAFIK, TABEL) ---------- */
.prediction-section-card {
    background: #ffffff;
    border: 1px solid #e1ecf2;
    border-radius: 12px;
    padding: 22px;
    margin-bottom: 20px;
    box-shadow: 0 1px 3px rgba(5, 46, 74, 0.06);
}

.prediction-section-card h5 {
    font-size: 16px;
    font-weight: 700;
    color: var(--ink);
    margin-bottom: 18px;
}

.prediction-section-card .row h4 {
    color: var(--ink);
    font-weight: 700 !important;
    font-variant-numeric: tabular-nums;
}

.prediction-section-card .row small,
.prediction-section-card > small {
    color: var(--muted) !important;
}

/* ---------- GRAFIK ---------- */
.prediction-chart-container {
    position: relative;
    width: 100%;
    height: 320px;
}

.prediction-info {
    color: var(--muted);
    font-size: 13px;
    margin-top: 15px;
}

/* ---------- TABEL RIWAYAT ---------- */
.prediction-section-card .table {
    --bs-table-hover-bg: #f3f9fc;
    --bs-table-border-color: #e8f0f5;
    margin-bottom: 0;
}

.prediction-section-card .table-light {
    --bs-table-bg: #f3f8fb;
    --bs-table-color: #36566b;
    --bs-table-border-color: #e1ecf2;
}

.prediction-section-card .table th {
    font-weight: 600;
    white-space: nowrap;
}

/* Badge status data */
.prediction-section-card .badge {
    font-weight: 600;
    border-radius: 6px;
    padding: 0.4em 0.7em;
}

.prediction-section-card .badge.bg-success {
    background-color: #e8f6ee !important;
    color: #15803d !important;
}

.prediction-section-card .badge.bg-warning {
    background-color: #fdf3df !important;
    color: #a16207 !important;
}

/* Kondisi kosong (teks "Belum ada data stok...") */
.prediction-section-card .text-center.text-muted.py-5 {
    border: 1px dashed #c9dde7;
    border-radius: 10px;
    background-color: #f8fbfd;
}

/* ---------- HP ---------- */
@media (max-width: 768px) {
    .prediction-metrics-grid {
        grid-template-columns: 1fr;
    }

    .prediction-container {
        padding: 0 12px 1rem;
    }

    .prediction-hero {
        padding: 1rem 1rem 2rem;
    }

    .prediction-hero h4 {
        font-size: 1.15rem;
    }

    .prediction-chart-container {
        height: 260px;
    }
}

/* Hormati pengguna yang mematikan animasi di perangkatnya */
@media (prefers-reduced-motion: reduce) {
    .prediction-page *,
    .prediction-page *::before,
    .prediction-page *::after {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
    }
}

</style>

<div class="prediction-page container-fluid px-0">

    {{-- HEADER FULL LEBAR --}}
    <div class="prediction-hero">

        {{-- GELEMBUNG DAN GELOMBANG (VISUAL SAJA) --}}
        <div class="prediction-bubbles" aria-hidden="true">
            <span style="--x:6%;  --s:12px; --d:8s;  --delay:0s;"></span>
            <span style="--x:24%; --s:8px;  --d:6s;  --delay:2s;"></span>
            <span style="--x:47%; --s:16px; --d:9s;  --delay:1s;"></span>
            <span style="--x:68%; --s:9px;  --d:7s;  --delay:3s;"></span>
            <span style="--x:86%; --s:13px; --d:8.5s;--delay:0.6s;"></span>
        </div>
        <div class="prediction-waves" aria-hidden="true">
            <svg class="prediction-wave prediction-wave-back" viewBox="0 0 1200 60" preserveAspectRatio="none">
                <path d="M0,30 C150,60 300,0 450,30 C600,60 750,0 900,30 C1050,60 1200,0 1350,30 L1350,60 L0,60 Z"/>
            </svg>
            <svg class="prediction-wave prediction-wave-front" viewBox="0 0 1200 60" preserveAspectRatio="none">
                <path d="M0,30 C150,0 300,60 450,30 C600,0 750,60 900,30 C1050,0 1200,60 1350,30 L1350,60 L0,60 Z"/>
            </svg>
        </div>

        <div class="prediction-hero-inner">
            <div class="prediction-hero-icon">
                <i class="bi bi-graph-up"></i>
            </div>
            <div>
                <h4>Prediksi Refill</h4>
                <p>
                    Monitoring konsumsi pakan dan estimasi waktu pengisian ulang stok.
                </p>
            </div>
        </div>
    </div>

    <div class="prediction-container">

        {{-- STATUS PREDIKSI --}}
        <div class="prediction-status-card">
            <h5>
                Status Prediksi: {{ $statusPrediksi }}
            </h5>

            <p>{{ $pesanPrediksi }}</p>
        </div>

        {{-- RINGKASAN PREDIKSI --}}
        <div class="prediction-metrics-grid">

            <div class="prediction-metric-card">
                <h6>Rata-rata Pakan / Hari</h6>

                <h3>
                    @if ($sma !== null)
                        {{ number_format($sma / 1000, 2, ',', '.') }} kg
                    @else
                        —
                    @endif
                </h3>

                <p>Metode Simple Moving Average 7 hari</p>
            </div>

            <div class="prediction-metric-card">
                <h6>Hari Tersisa</h6>

                <h3>
                    @if ($prediksiHari !== null)
                        {{ number_format($prediksiHari, 1, ',', '.') }} hari
                    @else
                        —
                    @endif
                </h3>

                <p>Estimasi menuju batas minimum stok</p>
            </div>

            <div class="prediction-metric-card">
                <h6>Rekomendasi Refill</h6>

                <h3>{{ $rekomendasiRefill }}</h3>

                <p>
                    Estimasi tanggal:
                    {{ $estimasiTanggal ?? 'Belum tersedia' }}
                </p>
            </div>

        </div>

        {{-- INFORMASI STOK --}}
        <div class="prediction-section-card">

            <h5>Informasi Stok Pakan</h5>

            <div class="row">

                <div class="col-md-4 mb-3">
                    <small class="text-muted">Stok Saat Ini</small>

                    <h4 class="fw-bold">
                        {{ $stokSaatIni !== null
                            ? number_format($stokSaatIni, 0, ',', '.') . ' gram'
                            : 'Belum tersedia' }}
                    </h4>
                </div>

                <div class="col-md-4 mb-3">
                    <small class="text-muted">Batas Minimum</small>

                    <h4 class="fw-bold">
                        {{ $batasMinimum !== null
                            ? number_format($batasMinimum, 0, ',', '.') . ' gram'
                            : 'Belum tersedia' }}
                    </h4>
                </div>

                <div class="col-md-4 mb-3">
                    <small class="text-muted">Total Konsumsi 7 Hari</small>

                    <h4 class="fw-bold">
                        {{ number_format($totalKonsumsi, 0, ',', '.') }} gram
                    </h4>
                </div>

            </div>

            @if ($monitoring)
                <small class="text-muted">
                    Pembacaan stok terakhir:
                    {{ $monitoring->waktu_pembacaan
                        ? \Carbon\Carbon::parse($monitoring->waktu_pembacaan)->format('d-m-Y H:i:s')
                        : 'Tidak tersedia' }}
                </small>
            @endif

        </div>

        {{-- GRAFIK PROYEKSI STOK --}}
        <div class="prediction-section-card">

            <h5>Grafik Prediksi Stok Pakan</h5>

            @if ($stokSaatIni !== null)

                <div class="prediction-chart-container">
                    <canvas id="grafikPrediksiStok"></canvas>
                </div>

                <p class="prediction-info">
                    Grafik menampilkan proyeksi stok selama 7 hari ke depan
                    berdasarkan rata-rata konsumsi pakan.
                </p>

            @else

                <div class="text-center text-muted py-5">
                    <p>Belum ada data stok untuk ditampilkan.</p>
                </div>

            @endif

        </div>

        {{-- RIWAYAT KONSUMSI PAKAN --}}
        <div class="prediction-section-card">

            <h5>Riwayat Penggunaan Pakan (7 Hari Terakhir)</h5>

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Konsumsi Pakan</th>
                            <th>Status Data</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse ($dataKonsumsi as $index => $data)

                            <tr>
                                <td>{{ $index + 1 }}</td>

                                <td>{{ $data['tanggal'] }}</td>

                                <td>
                                    {{ number_format($data['konsumsi'], 0, ',', '.') }}
                                    gram
                                </td>

                                <td>
                                    @if ($data['ada_data'])
                                        <span class="badge bg-success">
                                            Tercatat
                                        </span>
                                    @else
                                        <span class="badge bg-warning text-dark">
                                            Belum ada data
                                        </span>
                                    @endif
                                </td>
                            </tr>

                        @empty

                            <tr>
                                <td colspan="4" class="text-center">
                                    Belum ada data penggunaan pakan.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                    <tfoot class="table-light">

                        <tr>
                            <th colspan="2">Total Konsumsi</th>

                            <th colspan="2">
                                {{ number_format($totalKonsumsi, 0, ',', '.') }}
                                gram
                            </th>
                        </tr>

                    </tfoot>

                </table>

            </div>

            <div class="prediction-info">
                Data konsumsi yang digunakan merupakan data pakan aktual
                dengan status berhasil.
                <br>
                Hari tercatat: {{ $jumlahHariTercatat }} dari 7 hari.
            </div>

        </div>

    </div>
</div>

{{-- CHART JS --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const canvas = document.getElementById('grafikPrediksiStok');

    if (!canvas) {
        return;
    }

    const labels = @json($grafikLabels);
    const dataStok = @json($grafikStok);
    const batasMinimum = Number(@json($batasMinimum ?? 0));

    new Chart(canvas, {

        type: 'line',

        data: {
            labels: labels,

            datasets: [

                {
                    label: 'Proyeksi Stok (gram)',

                    data: dataStok,

                    borderColor: '#1296c4',

                    backgroundColor: 'rgba(18, 150, 196, 0.15)',

                    fill: true,

                    tension: 0.3,

                    pointRadius: 4,

                    pointHoverRadius: 6
                },

                {
                    label: 'Batas Minimum (gram)',

                    data: labels.map(() => batasMinimum),

                    borderColor: '#ef4444',

                    borderDash: [6, 5],

                    pointRadius: 0,

                    fill: false
                }

            ]
        },

        options: {

            responsive: true,

            maintainAspectRatio: false,

            interaction: {
                intersect: false,
                mode: 'index'
            },

            plugins: {

                legend: {
                    position: 'bottom'
                },

                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': ' +
                                Number(context.parsed.y).toLocaleString('id-ID') +
                                ' gram';
                        }
                    }
                }

            },

            scales: {

                y: {
                    beginAtZero: true,

                    title: {
                        display: true,
                        text: 'Stok Pakan (gram)'
                    }
                },

                x: {
                    title: {
                        display: true,
                        text: 'Periode'
                    }
                }

            }

        }

    });

});
</script>

@endsection
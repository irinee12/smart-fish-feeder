
@extends('layouts.app')

@section('title', 'Prediksi Refill Pakan Gudang - Smart Fish Feeder')

@section('content')

@php
    $nSma = (int) ($nSma ?? 7);
    $stokAwalKg = $stokAwalKg ?? null;
    $konsumsiSejakInput = (float) ($konsumsiSejakInput ?? 0);
    $dataKonsumsi = collect($dataKonsumsi ?? []);

    $fmt = fn($v, $d = 2) => number_format($v, $d, ',', '.');

    // Persediaan tercatat berkurang saat pakan benar-benar digunakan
    $stokKg = $stokAwalKg !== null
        ? max((float) $stokAwalKg - ($konsumsiSejakInput / 1000), 0)
        : null;

    // Data konsumsi dalam gram
    $vals = $dataKonsumsi->pluck('konsumsi')
        ->map(fn($v) => (float) $v)
        ->values()
        ->all();

    $rows = [];

    foreach ($dataKonsumsi->values() as $i => $d) {
        $awal = max(0, $i + 1 - $nSma);
        $jumlahData = $i + 1 - $awal;

        $sma = $jumlahData > 0
            ? array_sum(array_slice($vals, $awal, $jumlahData)) / $jumlahData
            : null;

        $rows[] = [
            'tanggal' => $d['tanggal'],
            'konsumsi' => $vals[$i],
            'ada_data' => $d['ada_data'] ?? true,
            'sma' => $sma
        ];
    }

    $rows = collect($rows);

    // SMA terkini menggunakan data periode yang dipilih
    $dataSma = $rows
        ->where('ada_data', true)
        ->take(-$nSma);

    $smaTerkini = $dataSma->count() > 0
        ? $dataSma->avg('konsumsi')
        : null;

    $smaKg = $smaTerkini !== null
        ? $smaTerkini / 1000
        : null;

    // Total konsumsi hari ini
    $konsumsiHariIni = $rows->last()['konsumsi'] ?? 0;

    $konsumsiHariIniKg = $konsumsiHariIni / 1000;

    // Estimasi ketahanan persediaan
    $estimasiHari = ($stokKg !== null && $smaKg > 0)
        ? $stokKg / $smaKg
        : null;

    // Status persediaan untuk informasi, bukan perintah pembelian
    if ($stokKg === null) {
        $status = 'Belum ada data stok';
        $statusClass = 'secondary';
        $statusIcon = 'bi-info-circle';
    } elseif ($smaKg === null || $smaKg <= 0) {
        $status = 'Menunggu data konsumsi';
        $statusClass = 'secondary';
        $statusIcon = 'bi-clock-history';
    } elseif ($estimasiHari <= 3) {
        $status = 'Persediaan terbatas';
        $statusClass = 'danger';
        $statusIcon = 'bi-exclamation-circle';
    } elseif ($estimasiHari <= 7) {
        $status = 'Persediaan mulai menipis';
        $statusClass = 'warning';
        $statusIcon = 'bi-exclamation-triangle';
    } else {
        $status = 'Persediaan mencukupi';
        $statusClass = 'success';
        $statusIcon = 'bi-check-circle';
    }

    $labels = $rows->pluck('tanggal')->values();
    $aktual = $rows->pluck('konsumsi')->map(fn($v) => round($v / 1000, 3))->values();

    $smaChart = $rows->pluck('sma')
        ->map(fn($v) => $v !== null ? round($v / 1000, 3) : null)
        ->values();
@endphp

<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

.prediction-page {
    --sea-deep: #052e4a;
    --sea-mid: #0a6a8f;
    --sea-bright: #1296c4;
    --ink: #0f2a3a;
    --muted: #668091;
    font-family: 'Plus Jakarta Sans', sans-serif;
    color: var(--ink);
    background: linear-gradient(160deg,#e8f6fa,#f7fbfd,#e3f1f7);
    min-height: 100vh;
    padding-bottom: 30px;
}

.prediction-hero {
    position: relative;
    overflow: hidden;
    padding: 30px 32px;
    margin-bottom: 25px;
    color: white;
    background:
        radial-gradient(circle at 15% 10%,rgba(94,234,212,.3),transparent 45%),
        linear-gradient(135deg,#1296c4,#0a6a8f 55%,#052e4a);
    box-shadow: 0 10px 24px -12px rgba(5,46,74,.5);
}

.hero-content {
    position: relative;
    z-index: 2;
    display: flex;
    align-items: center;
    gap: 16px;
}

.hero-icon {
    width: 54px;
    height: 54px;
    display: grid;
    place-items: center;
    font-size: 25px;
    border-radius: 14px;
    background: rgba(255,255,255,.2);
    border: 1px solid rgba(255,255,255,.3);
}

.hero-content h4 {
    font-size: 21px;
    font-weight: 800;
    margin: 0;
}

.hero-content p {
    color: rgba(255,255,255,.8);
    font-size: 13px;
    margin: 6px 0 0;
}

.prediction-container {
    max-width: 1500px;
    padding: 0 24px;
    margin: auto;
}

.section-card {
    background: #fff;
    border: 1px solid #e1ecf2;
    border-radius: 14px;
    padding: 24px;
    margin-bottom: 20px;
    box-shadow: 0 2px 8px rgba(5,46,74,.04);
}

.section-title {
    font-size: 16px;
    font-weight: 800;
    margin-bottom: 20px;
    color: var(--ink);
}

.section-title i {
    color: var(--sea-bright);
    margin-right: 8px;
}

.metric-grid {
    display: grid;
    grid-template-columns: repeat(4,minmax(0,1fr));
    gap: 16px;
    margin-bottom: 22px;
}

.metric-card {
    background: #fff;
    border: 1px solid #e1ecf2;
    border-radius: 13px;
    padding: 21px;
    min-width: 0;
}

.metric-icon {
    width: 40px;
    height: 40px;
    display: grid;
    place-items: center;
    border-radius: 11px;
    background: #e8f6fa;
    color: #0a6a8f;
    font-size: 19px;
    margin-bottom: 14px;
}

.metric-label {
    font-size: 12px;
    font-weight: 600;
    color: #668091;
    margin-bottom: 9px;
}

.metric-value {
    font-size: 25px;
    font-weight: 800;
    color: #0f2a3a;
    overflow-wrap: anywhere;
    font-variant-numeric: tabular-nums;
}

.metric-description {
    font-size: 11px;
    color: #8aa0ae;
    margin-top: 7px;
}

.stock-value {
    color: #0a8a58;
}

.stock-progress {
    height: 9px;
    background: #e9f0f4;
    border-radius: 20px;
    overflow: hidden;
    margin-top: 15px;
}

.stock-progress-bar {
    height: 100%;
    background: linear-gradient(90deg,#1296c4,#22b99b);
    border-radius: 20px;
}

.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr auto;
    align-items: end;
    gap: 15px;
}

.form-grid label {
    display: block;
    font-size: 12px;
    font-weight: 700;
    color: #5b7384;
    margin-bottom: 8px;
}

.form-grid .form-control {
    min-height: 44px;
    border: 1px solid #cfe1e9;
    border-radius: 9px;
}

.form-grid .form-control:focus {
    border-color: #1296c4;
    box-shadow: 0 0 0 .2rem rgba(18,150,196,.12);
}

.btn-add-stock {
    min-height: 44px;
    border: 0;
    border-radius: 9px;
    padding: 0 23px;
    font-size: 13px;
    font-weight: 700;
    color: white;
    background: linear-gradient(135deg,#1296c4,#0a6a8f);
}

.btn-add-stock:hover {
    color: white;
    filter: brightness(1.08);
}

.info-text {
    color: #718797;
    font-size: 12px;
    line-height: 1.8;
    margin-top: 14px;
}

.sma-box {
    background: #f2f9fc;
    border: 1px solid #dfedf3;
    border-radius: 12px;
    padding: 20px;
    height: 100%;
}

.sma-box small {
    display: block;
    font-size: 12px;
    color: #668091;
    margin-bottom: 10px;
}

.sma-box strong {
    font-size: 25px;
    color: #0a6a8f;
}

.sma-selector {
    width: 180px;
    border: 1px solid #cfe1e9;
    border-radius: 8px;
    padding: 8px 12px;
    font-size: 13px;
}

.status-box {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 19px 22px;
    border-radius: 12px;
    margin-bottom: 20px;
}

.status-box.success {
    background: #eaf7f0;
    border: 1px solid #bfe5d0;
    color: #15803d;
}

.status-box.warning {
    background: #fff7e6;
    border: 1px solid #f1dca8;
    color: #a16207;
}

.status-box.danger {
    background: #fdeeee;
    border: 1px solid #f3c4c4;
    color: #b02a37;
}

.status-box.secondary {
    background: #eef5f8;
    border: 1px solid #d4e4eb;
    color: #496779;
}

.status-icon {
    font-size: 25px;
}

.status-box strong {
    display: block;
    font-size: 14px;
    margin-bottom: 4px;
}

.status-box p {
    font-size: 12px;
    margin: 0;
    opacity: .85;
}

.chart-container {
    height: 330px;
    position: relative;
}

.table thead th {
    background: #f1f7fa;
    color: #36566b;
    font-size: 12px;
    white-space: nowrap;
}

.table tbody td {
    font-size: 12px;
    vertical-align: middle;
}

@media(max-width:1100px) {
    .metric-grid {
        grid-template-columns: repeat(2,1fr);
    }
}

@media(max-width:700px) {
    .prediction-container {
        padding: 0 12px;
    }

    .prediction-hero {
        padding: 22px 16px;
    }

    .hero-content h4 {
        font-size: 17px;
    }

    .metric-grid {
        grid-template-columns: 1fr 1fr;
        gap: 10px;
    }

    .metric-card {
        padding: 15px;
    }

    .metric-value {
        font-size: 20px;
    }

    .section-card {
        padding: 17px;
    }

    .form-grid {
        grid-template-columns: 1fr;
    }

    .btn-add-stock {
        width: 100%;
    }

    .chart-container {
        height: 260px;
    }
}

@media(max-width:420px) {
    .metric-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<div class="prediction-page">

    {{-- HEADER --}}
    <div class="prediction-hero">
        <div class="hero-content">
            <div class="hero-icon">
                <i class="bi bi-boxes"></i>
            </div>
            <div>
                <h4>Prediksi Refill Pakan Gudang</h4>
                <p>Monitoring persediaan dan analisis konsumsi pakan menggunakan metode SMA.</p>
            </div>
        </div>
    </div>

    <div class="prediction-container">

        {{-- NOTIFIKASI --}}
        @if(session('success'))
            <div class="alert alert-success">
                <i class="bi bi-check-circle me-2"></i>
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif

        {{-- STATUS PERSEDIAAN --}}
        <div class="status-box {{ $statusClass }}">
            <div class="status-icon">
                <i class="bi {{ $statusIcon }}"></i>
            </div>
            <div>
                <strong>{{ $status }}</strong>
                <p>
                    @if($estimasiHari !== null)
                        Estimasi total persediaan dapat bertahan sekitar
                        {{ $fmt($estimasiHari, 1) }} hari berdasarkan rata-rata konsumsi.
                    @else
                        Data persediaan atau konsumsi belum mencukupi untuk dianalisis.
                    @endif
                </p>
            </div>
        </div>

        {{-- FORM TAMBAH STOK --}}
        <div class="section-card">
            <h5 class="section-title">
                <i class="bi bi-plus-circle"></i>
                Tambah Stok Gudang
            </h5>

            <form method="POST" action="{{ route('prediksi.update') }}">
                @csrf

                <div class="form-grid">

                    <div>
                        <label for="tanggal_stok">Tanggal Penambahan</label>
                        <input type="date"
                               class="form-control"
                               id="tanggal_stok"
                               name="tanggal_stok"
                               value="{{ old('tanggal_stok', now()->format('Y-m-d')) }}"
                               required>
                    </div>

                    <div>
                        <label for="jumlah_stok_kg">Jumlah Pakan Masuk (KG)</label>
                        <input type="number"
                               class="form-control"
                               id="jumlah_stok_kg"
                               name="jumlah_stok_kg"
                               step="0.01"
                               min="0.01"
                               placeholder="Contoh: 50"
                               value="{{ old('jumlah_stok_kg') }}"
                               required>
                    </div>

                    <button type="submit" class="btn-add-stock">
                        <i class="bi bi-plus-lg me-2"></i>
                        Tambah Stok
                    </button>

                </div>
            </form>

            <div class="info-text">
                <i class="bi bi-info-circle me-1"></i>
                Penambahan stok dicatat sebagai transaksi baru. Pemindahan pakan
                dari gudang ke wadah IoT tidak dihitung sebagai konsumsi.
                Pengurangan persediaan dilakukan berdasarkan pakan yang benar-benar
                dikeluarkan saat jadwal pemberian pakan.
            </div>
        </div>

        {{-- RINGKASAN PERSEDIAAN --}}
        <h5 class="section-title">
            <i class="bi bi-speedometer2"></i>
            Ringkasan Persediaan
        </h5>

        <div class="metric-grid">

            <div class="metric-card">
                <div class="metric-icon">
                    <i class="bi bi-box-seam"></i>
                </div>
                <div class="metric-label">Total Persediaan Tercatat</div>
                <div class="metric-value stock-value">
                    {{ $stokKg !== null ? $fmt($stokKg).' kg' : '—' }}
                </div>
                <div class="metric-description">
                    Gudang dan wadah IoT
                </div>
            </div>

            <div class="metric-card">
                <div class="metric-icon">
                    <i class="bi bi-activity"></i>
                </div>
                <div class="metric-label">Konsumsi Hari Ini</div>
                <div class="metric-value">
                    {{ $fmt($konsumsiHariIniKg).' kg' }}
                </div>
                <div class="metric-description">
                    Berdasarkan data load cell
                </div>
            </div>

            <div class="metric-card">
                <div class="metric-icon">
                    <i class="bi bi-graph-up"></i>
                </div>
                <div class="metric-label">Rata-rata Konsumsi Harian</div>
                <div class="metric-value">
                    {{ $smaKg !== null ? $fmt($smaKg).' kg' : '—' }}
                </div>
                <div class="metric-description">
                    Metode SMA {{ $nSma }} hari
                </div>
            </div>

            <div class="metric-card">
                <div class="metric-icon">
                    <i class="bi bi-hourglass-split"></i>
                </div>
                <div class="metric-label">Estimasi Ketahanan Persediaan</div>
                <div class="metric-value">
                    {{ $estimasiHari !== null ? $fmt($estimasiHari,1).' hari' : '—' }}
                </div>
                <div class="metric-description">
                    Berdasarkan rata-rata konsumsi
                </div>
            </div>

        </div>

        {{-- ANALISIS SMA --}}
        <div class="section-card">

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
                <h5 class="section-title mb-0">
                    <i class="bi bi-calculator"></i>
                    Analisis Konsumsi Metode SMA
                </h5>

                <form method="GET" action="{{ url()->current() }}">
                    <select name="n_sma"
                            class="sma-selector"
                            onchange="this.form.submit()">
                        <option value="3" {{ $nSma == 3 ? 'selected' : '' }}>
                            SMA 3 Hari
                        </option>
                        <option value="7" {{ $nSma == 7 ? 'selected' : '' }}>
                            SMA 7 Hari
                        </option>
                    </select>
                </form>
            </div>

            <div class="row g-3">

                <div class="col-md-6">
                    <div class="sma-box">
                        <small>Rata-rata Konsumsi Harian SMA {{ $nSma }}</small>
                        <strong>
                            {{ $smaKg !== null ? $fmt($smaKg).' kg/hari' : 'Menunggu data' }}
                        </strong>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="sma-box">
                        <small>Jumlah Data Konsumsi yang Dianalisis</small>
                        <strong>{{ $dataSma->count() }} hari</strong>
                    </div>
                </div>

            </div>

            <div class="info-text">
                SMA menghitung rata-rata konsumsi berdasarkan data harian
                selama 3 atau 7 hari terakhir. Hasilnya digunakan untuk
                menganalisis pola penggunaan dan ketahanan persediaan pakan.
            </div>
        </div>

        {{-- GRAFIK --}}
        <div class="section-card">
            <h5 class="section-title">
                <i class="bi bi-bar-chart-line"></i>
                Grafik Konsumsi Aktual dan SMA
            </h5>

            @if($rows->count() > 0)
                <div class="chart-container">
                    <canvas id="grafikKonsumsi"></canvas>
                </div>
                <div class="info-text">
                    Garis pertama menunjukkan konsumsi aktual harian.
                    Garis kedua menunjukkan hasil perhitungan rata-rata SMA.
                </div>
            @else
                <div class="text-center text-muted py-5">
                    <i class="bi bi-bar-chart fs-1 d-block mb-3"></i>
                    Belum ada data konsumsi untuk ditampilkan.
                </div>
            @endif
        </div>

        {{-- RIWAYAT KONSUMSI --}}
        <div class="section-card">
            <h5 class="section-title">
                <i class="bi bi-clock-history"></i>
                Riwayat Konsumsi Harian
            </h5>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Tanggal</th>
                            <th>Konsumsi Aktual</th>
                            <th>SMA {{ $nSma }} Hari</th>
                            <th>Status Data</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($rows->reverse()->values() as $index => $r)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $r['tanggal'] }}</td>
                                <td>
                                    <strong>{{ $fmt($r['konsumsi'],0) }} gram</strong>
                                </td>
                                <td>
                                    {{ $r['sma'] !== null
                                        ? $fmt($r['sma'],0).' gram'
                                        : '—' }}
                                </td>
                                <td>
                                    @if($r['ada_data'])
                                        <span class="badge bg-success">Tercatat</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Belum ada data</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-5">
                                    Belum ada riwayat konsumsi.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

{{-- CHART JS --}}
@if($rows->count() > 0)
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const canvas = document.getElementById('grafikKonsumsi');

    if (!canvas) return;

    new Chart(canvas, {
        type: 'line',

        data: {
            labels: @json($labels),

            datasets: [
                {
                    label: 'Konsumsi Aktual (kg)',
                    data: @json($aktual),
                    borderColor: '#1296c4',
                    backgroundColor: 'rgba(18,150,196,.12)',
                    fill: true,
                    tension: .3,
                    borderWidth: 2,
                    pointRadius: 4
                },
                {
                    label: 'SMA {{ $nSma }} Hari (kg)',
                    data: @json($smaChart),
                    borderColor: '#f59e0b',
                    backgroundColor: '#f59e0b',
                    fill: false,
                    tension: .3,
                    borderWidth: 2,
                    pointRadius: 3,
                    borderDash: [5,4]
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
                }
            },

            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Konsumsi Pakan (kg)'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Tanggal'
                    }
                }
            }
        }
    });

});
</script>
@endif

@endsection

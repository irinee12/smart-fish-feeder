
@extends('layouts.app')

@section('title', 'Prediksi Refill - Smart Fish Feeder')

@section('content')

<style>
    .prediction-container {
        padding: 20px;
    }

    .prediction-header {
        margin-bottom: 25px;
    }

    .prediction-header h2 {
        font-size: 25px;
        font-weight: 700;
        color: #1e293b;
    }

    .prediction-header p {
        color: #64748b;
        margin-bottom: 0;
    }

    .prediction-status-card {
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 25px;
    }

    .prediction-status-card h5 {
        font-weight: 700;
        color: #1e40af;
        margin-bottom: 8px;
    }

    .prediction-status-card p {
        color: #475569;
        margin-bottom: 0;
    }

    .prediction-metrics-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        margin-bottom: 25px;
    }

    .prediction-metric-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 22px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.03);
    }

    .prediction-metric-card h6 {
        color: #64748b;
        font-size: 14px;
        margin-bottom: 12px;
    }

    .prediction-metric-card h3 {
        font-size: 25px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 8px;
    }

    .prediction-metric-card p {
        font-size: 12px;
        color: #94a3b8;
        margin-bottom: 0;
    }

    .prediction-section-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 22px;
        margin-bottom: 25px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.03);
    }

    .prediction-section-card h5 {
        font-size: 17px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 20px;
    }

    .prediction-chart-container {
        position: relative;
        width: 100%;
        height: 320px;
    }

    .prediction-info {
        color: #64748b;
        font-size: 13px;
        margin-top: 15px;
    }

    @media (max-width: 768px) {
        .prediction-metrics-grid {
            grid-template-columns: 1fr;
        }

        .prediction-container {
            padding: 12px;
        }
    }
</style>

<div class="prediction-container">

    {{-- HEADER --}}
    <div class="prediction-header">
        <h2>Prediksi Refill</h2>
        <p>
            Monitoring konsumsi pakan dan estimasi waktu pengisian ulang stok.
        </p>
    </div>

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

                    borderColor: '#2563eb',

                    backgroundColor: 'rgba(37, 99, 235, 0.10)',

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

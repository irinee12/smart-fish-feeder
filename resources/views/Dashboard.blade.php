
@extends('layouts.app')

@section('title', 'Dashboard Smart Fish Feeder')

@section('content')

@php
    /*
    |--------------------------------------------------------------------------
    | DEFAULT DATA
    |--------------------------------------------------------------------------
    | Supaya halaman tidak error jika controller belum mengirim variabel
    | jadwal, grafik, load cell, atau prediksi gudang.
    */

    $jadwal = $jadwal ?? collect();
    $next = $next ?? null;

    $aktualHariIni = $aktualHariIni ?? 0;
    $targetHariIni = $targetHariIni ?? 0;
    $selisihHariIni = $selisihHariIni ?? 0;
    $selisihPct = $selisihPct ?? 0;

    $stHariIni = $stHariIni ?? 'Belum ada data';
    $stHariIniColor = $stHariIniColor ?? 'secondary';

    $sisaHari = $sisaHari ?? ($sisa_hari_gudang ?? null);
    $gudangOk = $gudangOk ?? false;

    $gLabel = $gLabel ?? [];
    $gTarget = $gTarget ?? [];
    $gAktual = $gAktual ?? [];

    $hasChart = $hasChart ?? false;

    $statusPakan = $statusPakan ?? function ($target, $aktual) {
        if ($aktual === null || $target <= 0) {
            return ['Belum diberikan', 'secondary', 0, 0];
        }

        $selisih = max(0, $target - $aktual);
        $persen = round(($selisih / $target) * 100, 1);

        if ($persen <= 5) {
            return ['Sesuai', 'success', $selisih, $persen];
        }

        if ($persen <= 15) {
            return ['Hampir sesuai', 'warning', $selisih, $persen];
        }

        return ['Tidak sesuai', 'danger', $selisih, $persen];
    };

    /*
    |--------------------------------------------------------------------------
    | DATA SENSOR DAN STATUS KONEKSI
    |--------------------------------------------------------------------------
    */

    $hasData = isset($data_sensor) && $data_sensor->count() > 0;
    $latest = $hasData ? $data_sensor->first() : null;

    $sensorTerhubung = false;
    $umurData = null;

    if ($latest && !empty($latest->waktu)) {
        try {
            $waktuTerakhir = \Carbon\Carbon::parse($latest->waktu);
            $umurData = now()->timestamp - $waktuTerakhir->timestamp;

            // Data dianggap aktif jika diterima maksimal 30 detik lalu.
            $sensorTerhubung = $umurData >= 0 && $umurData <= 30;
        } catch (\Throwable $e) {
            $sensorTerhubung = false;
        }
    }

    $dataSensorAktif = $sensorTerhubung && $latest !== null;

    /*
    |--------------------------------------------------------------------------
    | PERHITUNGAN STOK DARI ULTRASONIC
    |--------------------------------------------------------------------------
    | Pertahankan rentang yang digunakan pada Dashboard sebelumnya.
    | Sesuaikan dengan hasil kalibrasi wadah sebenarnya.
    */

    $jarakMax = 30;
    $jarakMin = 5;
    $kapasitasMax = 6;

    $stokKg = 0;
    $persentase = 0;
    $jarak = null;

    if ($dataSensorAktif) {
        $jarak = (float) $latest->jarak;

        if ($jarak >= 0 && $jarak <= $jarakMax) {
            $rentang = $jarakMax - $jarakMin;

            $persentase = (($jarakMax - $jarak) / $rentang) * 100;
            $persentase = max(0, min(100, $persentase));

            $stokKg = ($persentase / 100) * $kapasitasMax;
        } else {
            $dataSensorAktif = false;
            $sensorTerhubung = false;
            $jarak = null;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | STATUS FUZZY
    |--------------------------------------------------------------------------
    | Jika controller memberikan fuzzy_status, gunakan nilai tersebut.
    | Jika tidak, gunakan klasifikasi jarak sebagai fallback.
    */

    $fuzzyKey = $dataSensorAktif ? ($fuzzy_status ?? null) : null;

    if (!$fuzzyKey && $dataSensorAktif) {
        if ($jarak <= 5) {
            $fuzzyKey = 'aman';
        } elseif ($jarak <= 11) {
            $fuzzyKey = 'cukup';
        } elseif ($jarak <= 17) {
            $fuzzyKey = 'persiapan';
        } elseif ($jarak <= 22) {
            $fuzzyKey = 'segera';
        } else {
            $fuzzyKey = 'habis';
        }
    }

    $fuzzyMap = [
        'aman' => 'Stok Aman',
        'cukup' => 'Stok Masih Cukup',
        'persiapan' => 'Persiapan Refill',
        'segera' => 'Segera Refill',
        'habis' => 'Wadah Kosong',
    ];

    $fuzzyLabel = $fuzzyMap[$fuzzyKey] ?? 'Belum ada data';

    $fuzzyColorMap = [
        'aman' => '#059669',
        'cukup' => '#0d9488',
        'persiapan' => '#ca8a04',
        'segera' => '#ea580c',
        'habis' => '#dc3545',
    ];

    $fuzzyColor = $dataSensorAktif
        ? ($fuzzyColorMap[$fuzzyKey] ?? '#64748b')
        : '#64748b';

    $fuzzyBarMap = [
        'aman' => 'bar-aman',
        'cukup' => 'bar-aman',
        'persiapan' => 'bar-persiapan',
        'segera' => 'bar-segera',
        'habis' => 'bar-habis',
    ];

    $fuzzyBar = $dataSensorAktif
        ? ($fuzzyBarMap[$fuzzyKey] ?? 'bar-none')
        : 'bar-none';

    // Buzzer hanya ditandai aktif ketika sensor aktif dan wadah kosong.
    $buzzerAktif = $dataSensorAktif && $fuzzyKey === 'habis';

    // Status tampilan awal.
    $statusAwal = $sensorTerhubung
        ? 'Aktif Terhubung'
        : 'Menunggu Koneksi';
@endphp


<div class="dash container-fluid px-0">

    {{-- HEADER --}}
    <div class="dash-header py-3 px-4 mb-4 text-white d-flex align-items-center justify-content-between">

        <div class="dash-bubbles" aria-hidden="true">
            <span style="--x:6%;--s:12px;--d:8s;--delay:0s;"></span>
            <span style="--x:24%;--s:8px;--d:6s;--delay:2s;"></span>
            <span style="--x:47%;--s:16px;--d:9s;--delay:1s;"></span>
            <span style="--x:68%;--s:9px;--d:7s;--delay:3s;"></span>
            <span style="--x:86%;--s:13px;--d:8.5s;--delay:.6s;"></span>
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


    <div class="px-4">

        {{-- STATUS ALAT IOT --}}
        <div class="dash-card dash-status card border-0 rounded-4 mb-4">
            <div class="card-body d-flex flex-wrap justify-content-between align-items-center p-3 p-md-4">

                <div class="d-flex align-items-center gap-3 mb-2 mb-md-0">

                    <div class="d-flex align-items-center gap-2">
                        <span
                            id="status-dot"
                            class="dash-pulse-dot"
                            style="color: {{ $sensorTerhubung ? '#10b981' : '#f59e0b' }}">
                            ●
                        </span>

                        <span class="fw-bold text-secondary">Status Alat IoT</span>
                    </div>

                    <span
                        id="status-koneksi"
                        class="badge rounded-pill px-3 py-2 fw-normal"
                        style="{{ $sensorTerhubung
                            ? 'background:rgba(16,185,129,.12);color:#047857;'
                            : 'background:rgba(245,158,11,.12);color:#b45309;' }}">
                        {{ $statusAwal }}
                    </span>

                    <span id="waktu-update" class="text-muted small d-none d-md-inline">
                        @if($sensorTerhubung)
                            Update terakhir: {{ \Carbon\Carbon::parse($latest->waktu)->diffForHumans() }}
                        @else
                            ESP32 belum mengirim data terbaru
                        @endif
                    </span>
                </div>

                <div id="sinyal-koneksi"
                     class="{{ $sensorTerhubung ? 'text-success' : 'text-muted' }} small d-flex align-items-center gap-2">

                    @if($sensorTerhubung)
                        <i class="bi bi-wifi fs-5"></i> Sinyal bagus
                    @else
                        <i class="bi bi-wifi-off fs-5"></i> Tidak ada sinyal
                    @endif

                </div>
            </div>
        </div>


        {{-- 4 KARTU METRIK --}}
        <div class="row g-3 mb-4">

            {{-- KARTU 1: STOK PAKAN --}}
            <div class="col-12 col-md-6 col-lg-3">
                <div class="dash-card dash-metric dash-m1 card h-100 border-0 p-4 rounded-4">

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="dash-metric-label text-uppercase fw-bold">
                            Stok Pakan Saat Ini
                        </span>

                        <div class="dash-metric-icon rounded-3 p-2 d-flex align-items-center justify-content-center">
                            <i class="bi bi-box-seam fs-5"></i>
                        </div>
                    </div>

                    <div>
                        <h3 id="fuzzy-label"
                            class="dash-state fw-bold mb-1"
                            style="color: {{ $fuzzyColor }}">
                            {{ $fuzzyLabel }}
                        </h3>

                        <div class="text-dark fw-semibold">
                            <span id="stok-kg">
                                {{ $dataSensorAktif ? number_format($stokKg, 2, '.', '') : '--' }}
                            </span>
                            kg

                            <span class="text-muted fw-normal small">
                                ·
                                <span id="stok-persen">
                                    {{ $dataSensorAktif ? number_format($persentase, 1, '.', '') : '--' }}
                                </span>
                                % dari {{ $kapasitasMax }} kg
                            </span>
                        </div>

                        <span id="buzzer-label"
                              class="dash-buzzer badge rounded-pill mt-2"
                              style="{{ $buzzerAktif ? '' : 'display:none;' }}">
                            <i class="bi bi-bell-fill me-1"></i>Buzzer aktif
                        </span>
                    </div>
                </div>
            </div>


            {{-- KARTU 2: PAKAN HARI INI --}}
            <div class="col-12 col-md-6 col-lg-3">
                <div class="dash-card dash-metric dash-m2 card h-100 border-0 p-4 rounded-4">

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="dash-metric-label text-uppercase fw-bold">
                            Pakan Hari Ini
                        </span>

                        <div class="dash-metric-icon rounded-3 p-2 d-flex align-items-center justify-content-center">
                            <i class="bi bi-speedometer2 fs-5"></i>
                        </div>
                    </div>

                    <div>
                        <h2 class="fw-bold mb-1 text-dark">
                            {{ $aktualHariIni }}
                            <span class="fs-6 fw-normal text-muted">g keluar</span>
                        </h2>

                        <div class="small text-muted">
                            Target: <b class="text-dark">{{ $targetHariIni }} g</b>
                        </div>

                        <div class="small text-muted">
                            Tertahan:
                            <b class="text-dark">{{ $selisihHariIni }} g ({{ $selisihPct }}%)</b>
                        </div>

                        <span class="badge text-bg-{{ $stHariIniColor }} mt-2 {{ $stHariIniColor === 'warning' ? 'text-dark' : '' }}">
                            {{ $stHariIni }}
                        </span>
                    </div>
                </div>
            </div>


            {{-- KARTU 3: JADWAL BERIKUTNYA --}}
            <div class="col-12 col-md-6 col-lg-3">
                <div class="dash-card dash-metric dash-m3 card h-100 border-0 p-4 rounded-4">

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="dash-metric-label text-uppercase fw-bold">
                            Jadwal Berikutnya
                        </span>

                        <div class="dash-metric-icon rounded-3 p-2 d-flex align-items-center justify-content-center">
                            <i class="bi bi-clock fs-5"></i>
                        </div>
                    </div>

                    <div>
                        <h2 class="fw-bold mb-0 text-dark">
                            <small class="text-muted">
    {{ $next ? ($next->sesi.' · '.$next->target.' g') : 'Belum ada jadwal aktif' }}
</small>{{ $next->waktu ?? '--:--' }}
                        </h2>

                        <small class="text-muted">
                            {{ $next ? ($next->sesi.' · '.$next->target.' g') : 'Belum ada jadwal aktif' }}
                        </small>
                    </div>
                </div>
            </div>


            {{-- KARTU 4: PREDIKSI STOK GUDANG --}}
            <div class="col-12 col-md-6 col-lg-3">
                <div class="dash-card dash-metric dash-m4 card h-100 border-0 p-4 rounded-4">

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="dash-metric-label text-uppercase fw-bold">
                            Prediksi Stok Gudang
                        </span>

                        <div class="dash-metric-icon rounded-3 p-2 d-flex align-items-center justify-content-center">
                            <i class="bi bi-graph-up-arrow fs-5"></i>
                        </div>
                    </div>

                    <div>
                        <h2 class="fw-bold mb-0 text-dark">
                            {{ $sisaHari ?? '--' }}
                            <span class="fs-5 fw-normal text-muted">hari</span>
                        </h2>

                        @if($sisaHari === null)
                            <small class="text-muted">Belum cukup data (SMA)</small>
                        @elseif($gudangOk)
                            <small class="text-success fw-bold">Stok cukup</small>
                        @else
                            <small class="text-danger fw-bold">Perlu beli pakan</small>
                        @endif
                    </div>
                </div>
            </div>

        </div>


        {{-- GRAFIK DAN INDIKATOR --}}
        <div class="row g-4 mb-4">

            <div class="col-12 col-lg-8">
                <div class="dash-card dash-panel card h-100 border-0 p-4 rounded-4">

                    <h5 class="fw-bold mb-1 text-dark">
                        Grafik Pakan 7 Hari Terakhir
                    </h5>

                    <p class="text-muted small mb-3">
                        Target vs aktual keluar (dalam gram)
                    </p>

                    @if($hasChart)
                        <div style="position:relative;min-height:260px;">
                            <canvas id="chartPakan"></canvas>
                        </div>
                    @else
                        <div class="dash-empty w-100 d-flex flex-column align-items-center justify-content-center rounded-3"
                             style="min-height:250px;">

                            <i class="dash-empty-icon bi bi-bar-chart text-muted mb-2"
                               style="font-size:2rem;"></i>

                            <span class="text-muted">
                                Belum ada data penggunaan pakan.
                            </span>
                        </div>
                    @endif

                </div>
            </div>


            <div class="col-12 col-lg-4">
                <div class="dash-card dash-panel card h-100 border-0 p-4 rounded-4">

                    <h5 class="fw-bold mb-4 text-dark">
                        Indikator Stok Wadah
                    </h5>

                    <div class="text-center mb-4">
                        <h1 id="indikator-stok"
                            class="dash-big-number fw-bold mb-0"
                            style="font-size:3rem;color:{{ $fuzzyColor }}">

                            {{ $dataSensorAktif ? number_format($stokKg, 2, '.', '') : '--' }}
                        </h1>

                        <p class="text-muted mb-0">
                            kg tersisa (
                            <span id="indikator-persen">
                                {{ $dataSensorAktif ? number_format($persentase, 1, '.', '') : '--' }}
                            </span>%)
                        </p>
                    </div>


                    <div class="d-flex justify-content-between small mb-2">
                        <span class="text-muted">Status fuzzy</span>

                        <span id="indikator-fuzzy"
                              class="fw-bold"
                              style="color:{{ $fuzzyColor }}">
                            {{ $fuzzyLabel }}
                        </span>
                    </div>


                    <div class="dash-progress progress mb-4" style="height:8px;">
                        <div id="stok-progress"
                             class="progress-bar {{ $fuzzyBar }}"
                             role="progressbar"
                             style="width:{{ $dataSensorAktif ? $persentase : 0 }}%;"
                             aria-valuenow="{{ $dataSensorAktif ? $persentase : 0 }}"
                             aria-valuemin="0"
                             aria-valuemax="100">
                        </div>
                    </div>


                    <div class="dash-row d-flex justify-content-between border-bottom pb-2 mb-2 small">
                        <span class="text-muted">Kapasitas penuh</span>
                        <span class="fw-bold text-dark">{{ $kapasitasMax }} kg</span>
                    </div>

                    <div class="dash-row d-flex justify-content-between border-bottom pb-2 mb-2 small">
                        <span class="text-muted">Sisa pakan</span>

                        <span id="sisa-pakan" class="fw-bold text-dark">
                            {{ $dataSensorAktif ? number_format($stokKg, 2, '.', '') : '--' }} kg
                        </span>
                    </div>

                    <div class="dash-row d-flex justify-content-between border-bottom pb-2 mb-2 small">
                        <span class="text-muted">Ruang kosong</span>

                        <span id="ruang-kosong" class="fw-bold text-dark">
                            {{ $dataSensorAktif ? number_format($kapasitasMax - $stokKg, 2, '.', '') : '--' }} kg
                        </span>
                    </div>

                </div>
            </div>

        </div>


        {{-- TABEL JADWAL HARI INI --}}
        <div class="dash-card dash-panel card border-0 p-4 rounded-4 mb-4">

            <div class="d-flex justify-content-between align-items-center mb-4">

                <h5 class="fw-bold mb-0 text-dark">
                    Jadwal Pakan Hari Ini
                </h5>

                <button onclick="window.location.reload();"
                        class="btn btn-sm btn-outline-secondary">

                    <i class="bi bi-arrow-clockwise"></i>
                    Refresh
                </button>
            </div>


            <div class="table-responsive">

                <table class="dash-table table table-hover align-middle mb-0">

                    <thead>
                        <tr class="text-muted small text-uppercase">
                            <th class="fw-semibold pb-3">Waktu</th>
                            <th class="fw-semibold pb-3">Sesi</th>
                            <th class="fw-semibold pb-3">Target</th>
                            <th class="fw-semibold pb-3">Aktual</th>
                            <th class="fw-semibold pb-3">Selisih</th>
                            <th class="fw-semibold pb-3">Status</th>
                        </tr>
                    </thead>


                    <tbody class="border-top-0">

                        @forelse($jadwal as $row)

                            @php
                                [$st, $stColor, $sel, $selPct] =
                                    $statusPakan($row->target, $row->aktual);
                            @endphp

                            <tr>
                                <td class="fw-bold">{{ $row->waktu }}</td>
                                <td>{{ $row->sesi }}</td>
                                <td>{{ $row->target }} g</td>

                                <td>
                                    {{ $row->aktual !== null ? $row->aktual.' g' : '-' }}
                                </td>

                                <td>
                                    {{ $row->aktual !== null ? $sel.' g ('.$selPct.'%)' : '-' }}
                                </td>

                                <td>
                                    <span class="badge text-bg-{{ $stColor }} {{ $stColor === 'warning' ? 'text-dark' : '' }}">
                                        {{ $st }}
                                    </span>
                                </td>
                            </tr>

                        @empty

                            <tr>
                                <td colspan="6" class="py-5 text-center text-muted">

                                    <div class="d-flex flex-column align-items-center">

                                        <i class="dash-empty-icon bi bi-calendar-x text-muted mb-2"
                                           style="font-size:2rem;"></i>

                                        <p class="mb-0">
                                            Belum ada jadwal pakan untuk hari ini.
                                        </p>

                                        <small>
                                            Tambahkan jadwal di menu Jadwal Pakan.
                                        </small>

                                    </div>
                                </td>
                            </tr>

                        @endforelse

                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>


{{-- CHART.JS --}}
@if($hasChart)

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

    <script>
        const chartElement = document.getElementById('chartPakan');

        if (chartElement) {
            new Chart(chartElement, {
                type: 'line',

                data: {
                    labels: @json($gLabel),

                    datasets: [
                        {
                            label: 'Target (g)',
                            data: @json($gTarget),
                            borderColor: '#0a6a8f',
                            backgroundColor: 'rgba(10,106,143,.1)',
                            borderDash: [6, 4],
                            tension: .35,
                            pointRadius: 3
                        },
                        {
                            label: 'Aktual keluar (g)',
                            data: @json($gAktual),
                            borderColor: '#14b8a6',
                            backgroundColor: 'rgba(20,184,166,.15)',
                            fill: true,
                            tension: .35,
                            pointRadius: 3
                        }
                    ]
                },

                options: {
                    responsive: true,
                    maintainAspectRatio: false,

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
                                text: 'gram'
                            }
                        }
                    }
                }
            });
        }
    </script>

@endif


{{-- UPDATE STATUS SENSOR OTOMATIS --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    const KAPASITAS_MAX = 6;
    const JARAK_MAX = 30;
    const JARAK_MIN = 5;

    const statusElement = document.getElementById('status-koneksi');
    const dotElement = document.getElementById('status-dot');
    const waktuElement = document.getElementById('waktu-update');
    const sinyalElement = document.getElementById('sinyal-koneksi');

    function tampilkanOffline() {

        statusElement.textContent = 'Menunggu Koneksi';
        statusElement.style.background = 'rgba(245,158,11,.12)';
        statusElement.style.color = '#b45309';

        dotElement.style.color = '#f59e0b';

        waktuElement.textContent = 'ESP32 belum mengirim data terbaru';

        sinyalElement.className =
            'text-muted small d-flex align-items-center gap-2';

        sinyalElement.innerHTML =
            '<i class="bi bi-wifi-off fs-5"></i> Tidak ada sinyal';

        document.getElementById('fuzzy-label').textContent = 'Belum ada data';
        document.getElementById('fuzzy-label').style.color = '#64748b';

        document.getElementById('stok-kg').textContent = '--';
        document.getElementById('stok-persen').textContent = '--';

        document.getElementById('indikator-stok').textContent = '--';
        document.getElementById('indikator-stok').style.color = '#64748b';

        document.getElementById('indikator-persen').textContent = '--';
        document.getElementById('indikator-fuzzy').textContent = 'Belum ada data';
        document.getElementById('indikator-fuzzy').style.color = '#64748b';

        document.getElementById('sisa-pakan').textContent = '-- kg';
        document.getElementById('ruang-kosong').textContent = '-- kg';

        const progress = document.getElementById('stok-progress');

        progress.style.width = '0%';
        progress.setAttribute('aria-valuenow', 0);

        progress.className = 'progress-bar bar-none';

        document.getElementById('buzzer-label').style.display = 'none';
    }


    function tampilkanOnline(jarak, waktu) {

        const rentang = JARAK_MAX - JARAK_MIN;

        const persentase = Math.max(
            0,
            Math.min(100, ((JARAK_MAX - jarak) / rentang) * 100)
        );

        const stokKg = (persentase / 100) * KAPASITAS_MAX;
        const ruangKosong = KAPASITAS_MAX - stokKg;

        let fuzzyKey = '';
        let fuzzyLabel = '';
        let fuzzyColor = '';
        let fuzzyBar = '';

        if (jarak <= 5) {
            fuzzyKey = 'aman';
            fuzzyLabel = 'Stok Aman';
            fuzzyColor = '#059669';
            fuzzyBar = 'bar-aman';

        } else if (jarak <= 11) {
            fuzzyKey = 'cukup';
            fuzzyLabel = 'Stok Masih Cukup';
            fuzzyColor = '#0d9488';
            fuzzyBar = 'bar-aman';

        } else if (jarak <= 17) {
            fuzzyKey = 'persiapan';
            fuzzyLabel = 'Persiapan Refill';
            fuzzyColor = '#ca8a04';
            fuzzyBar = 'bar-persiapan';

        } else if (jarak <= 22) {
            fuzzyKey = 'segera';
            fuzzyLabel = 'Segera Refill';
            fuzzyColor = '#ea580c';
            fuzzyBar = 'bar-segera';

        } else {
            fuzzyKey = 'habis';
            fuzzyLabel = 'Wadah Kosong';
            fuzzyColor = '#dc3545';
            fuzzyBar = 'bar-habis';
        }


        statusElement.textContent = 'Aktif Terhubung';
        statusElement.style.background = 'rgba(16,185,129,.12)';
        statusElement.style.color = '#047857';

        dotElement.style.color = '#10b981';

        waktuElement.textContent = 'Data sensor berhasil diterima';

        sinyalElement.className =
            'text-success small d-flex align-items-center gap-2';

        sinyalElement.innerHTML =
            '<i class="bi bi-wifi fs-5"></i> Sinyal bagus';


        document.getElementById('fuzzy-label').textContent = fuzzyLabel;
        document.getElementById('fuzzy-label').style.color = fuzzyColor;

        document.getElementById('stok-kg').textContent = stokKg.toFixed(2);
        document.getElementById('stok-persen').textContent = persentase.toFixed(1);

        document.getElementById('indikator-stok').textContent = stokKg.toFixed(2);
        document.getElementById('indikator-stok').style.color = fuzzyColor;

        document.getElementById('indikator-persen').textContent = persentase.toFixed(1);

        document.getElementById('indikator-fuzzy').textContent = fuzzyLabel;
        document.getElementById('indikator-fuzzy').style.color = fuzzyColor;

        document.getElementById('sisa-pakan').textContent =
            stokKg.toFixed(2) + ' kg';

        document.getElementById('ruang-kosong').textContent =
            ruangKosong.toFixed(2) + ' kg';


        const progress = document.getElementById('stok-progress');

        progress.style.width = persentase + '%';
        progress.setAttribute('aria-valuenow', persentase);
        progress.className = 'progress-bar ' + fuzzyBar;


        const buzzer = document.getElementById('buzzer-label');

        buzzer.style.display = fuzzyKey === 'habis' ? 'inline-block' : 'none';
    }


    async function updateSensorData() {

        try {

            const response = await fetch('/api/sensor-data/latest', {
                method: 'GET',
                cache: 'no-store',
                headers: {
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) {
                throw new Error('Gagal mengambil data sensor');
            }

            const result = await response.json();

            const data = result.data;

            // API menyatakan apakah data terakhir masih segar.
            if (!result.sensor_terhubung || !data) {
                tampilkanOffline();
                return;
            }

            const jarak = Number(data.jarak);

            // Periksa nilai sensor.
            if (!Number.isFinite(jarak) || jarak < 0 || jarak > JARAK_MAX) {
                tampilkanOffline();
                return;
            }

            tampilkanOnline(jarak, data.waktu);

        } catch (error) {

            console.error('Monitoring sensor:', error);

            tampilkanOffline();
        }
    }


    // Periksa langsung saat halaman dibuka.
    updateSensorData();

    // Periksa ulang setiap 5 detik.
    setInterval(updateSensorData, 5000);

});
</script>


{{-- CSS --}}
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

.dash h1,
.dash h2,
.dash h3,
.dash h4,
.dash h5 {
    letter-spacing: -0.02em;
}

/* HEADER */
.dash-header {
    position: relative;
    width: 100%;
    overflow: hidden;
    padding-bottom: 2.25rem !important;

    background:
        radial-gradient(circle at 15% 10%, rgba(94,234,212,.3), transparent 45%),
        linear-gradient(135deg, var(--sea-bright) 0%, var(--sea-mid) 55%, var(--sea-deep) 100%);

    box-shadow: 0 10px 24px -12px rgba(5,46,74,.5);
    animation: dashHeaderIn .7s ease-out both;
}

@keyframes dashHeaderIn {
    from { opacity:0; transform:translateY(-16px); }
    to { opacity:1; transform:translateY(0); }
}

.dash-header-inner {
    position: relative;
    z-index: 3;
}

.dash-header-icon {
    background: rgba(255,255,255,.2);
    border: 1px solid rgba(255,255,255,.3);
    backdrop-filter: blur(4px);
    animation: dashFloat 5s ease-in-out infinite;
}

@keyframes dashFloat {
    0%,100% { transform:translateY(0); }
    50% { transform:translateY(-5px); }
}

.dash-bubbles {
    position:absolute;
    inset:0;
    z-index:1;
    pointer-events:none;
}

.dash-bubbles span {
    position:absolute;
    bottom:-20px;
    left:var(--x);
    width:var(--s);
    height:var(--s);
    border-radius:50%;

    background:radial-gradient(circle at 30% 30%, rgba(255,255,255,.75), rgba(255,255,255,.1));
    border:1px solid rgba(255,255,255,.4);

    animation:dashBubble var(--d) var(--delay) linear infinite;
}

@keyframes dashBubble {
    0% { transform:translateY(0) translateX(0); opacity:0; }
    15% { opacity:1; }
    100% { transform:translateY(-120px) translateX(12px); opacity:0; }
}

.dash-waves {
    position:absolute;
    left:0;
    right:0;
    bottom:0;
    height:30px;
    z-index:2;
    overflow:hidden;
    pointer-events:none;
}

.dash-wave {
    position:absolute;
    bottom:0;
    left:0;
    width:200%;
    height:100%;
}

.dash-wave-back {
    fill:rgba(255,255,255,.18);
    animation:dashWave 10s linear infinite;
}

.dash-wave-front {
    fill:rgba(255,255,255,.3);
    height:70%;
    animation:dashWave 6s linear infinite reverse;
}

@keyframes dashWave {
    from { transform:translateX(0); }
    to { transform:translateX(-50%); }
}

/* KARTU */
.dash-card {
    background:#fff;
    box-shadow:0 10px 25px -14px rgba(5,46,74,.3);
    transition:transform .3s ease, box-shadow .3s ease;
    animation:dashCardIn .7s cubic-bezier(.22,1,.36,1) backwards;
}

@keyframes dashCardIn {
    from { opacity:0; transform:translateY(26px) scale(.98); }
    to { opacity:1; transform:translateY(0) scale(1); }
}

.dash-status { animation-delay:.15s; }

.dash-metric:hover,
.dash-panel:hover {
    transform:translateY(-5px);
    box-shadow:0 20px 34px -16px rgba(5,46,74,.38);
}

/* STATUS ALAT */
.dash-pulse-dot {
    font-size:.8rem;
    color:#f59e0b;
    animation:dashPulse 1.6s ease-in-out infinite;
}

@keyframes dashPulse {
    0%,100% { opacity:1; transform:scale(1); }
    50% { opacity:.35; transform:scale(1.35); }
}

.dash-badge-wait {
    background:rgba(245,158,11,.12);
    color:#b45309;
}

.dash-nosignal i {
    animation:dashBlink 2.4s ease-in-out infinite;
}

@keyframes dashBlink {
    0%,100% { opacity:1; }
    50% { opacity:.35; }
}

/* KARTU METRIK */
.dash-metric {
    --accent:var(--sea-bright);
    --accent-soft:rgba(18,150,196,.13);

    position:relative;
    overflow:hidden;
    border-top:4px solid var(--accent) !important;
}

.dash-m1 { animation-delay:.25s; }

.dash-m2 {
    animation-delay:.37s;
    --accent:#14b8a6;
    --accent-soft:rgba(20,184,166,.14);
}

.dash-m3 {
    animation-delay:.49s;
    --accent:#0a6a8f;
    --accent-soft:rgba(10,106,143,.13);
}

.dash-m4 {
    animation-delay:.61s;
    --accent:#f59e0b;
    --accent-soft:rgba(245,158,11,.15);
}

.dash-metric::after {
    content:'';
    position:absolute;
    right:-45px;
    bottom:-55px;
    width:140px;
    height:140px;
    border-radius:50%;
    background:var(--accent-soft);
    transition:transform .5s ease;
    pointer-events:none;
}

.dash-metric:hover::after {
    transform:scale(1.5);
}

.dash-metric > * {
    position:relative;
    z-index:1;
}

.dash-metric-label {
    font-size:.72rem;
    letter-spacing:.5px;
    color:var(--muted);
}

.dash-metric-icon {
    width:42px;
    height:42px;
    background:var(--accent-soft);
    color:var(--accent);
    animation:dashFloat 4.5s ease-in-out infinite;
}

.dash-m2 .dash-metric-icon { animation-delay:.5s; }
.dash-m3 .dash-metric-icon { animation-delay:1s; }
.dash-m4 .dash-metric-icon { animation-delay:1.5s; }

.dash-metric h2 {
    font-weight:800 !important;
    font-variant-numeric:tabular-nums;
}

.dash-state {
    font-weight:800 !important;
    font-size:1.6rem;
    transition:color .5s;
}

/* BUZZER */
.dash-buzzer {
    background:rgba(220,53,69,.12);
    color:#b02a37;
    font-weight:600;
    animation:dashBlink 1.2s ease-in-out infinite;
}

/* PANEL */
.dash-empty {
    background-color:#f5fbfd;
    border:1.5px dashed #b8d6e3;

    background-image:radial-gradient(rgba(18,150,196,.08) 1px, transparent 1px);
    background-size:18px 18px;
}

.dash-empty-icon {
    animation:dashFloat 4s ease-in-out infinite;
}

.dash-big-number {
    font-weight:800 !important;
    transition:color .5s;
}

.dash-progress {
    position:relative;
    overflow:hidden;
    border-radius:999px;
    background-color:#e1eef4;
}

.dash-progress::after {
    content:'';
    position:absolute;
    inset:0;

    background:linear-gradient(100deg, transparent 30%, rgba(255,255,255,.45) 50%, transparent 70%);
    background-size:200% 100%;
    animation:dashShimmer 2.8s linear infinite;
}

.dash-progress .progress-bar {
    transition:width 1s cubic-bezier(.22,1,.36,1);
}

.dash-progress .bar-aman {
    background:linear-gradient(90deg, #059669, #34d399) !important;
}

.dash-progress .bar-persiapan {
    background:linear-gradient(90deg, #ca8a04, #facc15) !important;
}

.dash-progress .bar-segera {
    background:linear-gradient(90deg, #ea580c, #fb923c) !important;
}

.dash-progress .bar-habis {
    background:linear-gradient(90deg, #dc3545, #ff6b6b) !important;
}

.dash-progress .bar-none {
    background:#b8d6e3 !important;
}

@keyframes dashShimmer {
    from { background-position:200% 0; }
    to { background-position:-200% 0; }
}

.dash-row {
    border-color:var(--line) !important;
}

/* TABEL */
.dash-table thead tr {
    border-bottom:2px solid var(--line);
}

.dash-table thead th {
    background:transparent;
    color:var(--muted);
    letter-spacing:.5px;
    border-bottom:none;
}

.dash-table.table-hover > tbody > tr:hover > * {
    background-color:rgba(18,150,196,.06);
    --bs-table-accent-bg:rgba(18,150,196,.06);
}

@media (max-width:768px) {
    .dash-header {
        padding-bottom:2rem !important;
    }

    .dash-header h4 {
        font-size:1.15rem;
    }

    .dash-metric:hover,
    .dash-panel:hover {
        transform:none;
    }
}

@media (prefers-reduced-motion:reduce) {
    .dash *,
    .dash *::before,
    .dash *::after {
        animation-duration:.01ms !important;
        animation-iteration-count:1 !important;
        transition-duration:.01ms !important;
    }
}
</style>

@endsection

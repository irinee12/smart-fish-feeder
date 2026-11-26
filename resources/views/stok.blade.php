@extends('layouts.app')

@section('title', 'Stok Pakan - Smart Fish Feeder')

@section('content')

@php
    /*
    |--------------------------------------------------------------------------
    | DATA SENSOR ULTRASONIK
    |--------------------------------------------------------------------------
    | Data dikirim dari route /stok (routes/web.php) berdasarkan tabel sensor_data.
    */

    $kapasitasWadah = 6; // kg

    // Jarak sensor ke permukaan pakan (cm)
    $jarakSensor = $jarakSensor ?? null;

    // Status koneksi ESP32
    $sensorTerhubung = $sensorTerhubung ?? false;

    /*
    |--------------------------------------------------------------------------
    | KALIBRASI SENSOR
    |--------------------------------------------------------------------------
    | Jarak 0 cm  = permukaan pakan paling tinggi (batas penuh).
    | Jarak 23 cm = wadah kosong.
    */

    $jarakPenuh = 0;
    $jarakKosong = 23;

    /*
    |--------------------------------------------------------------------------
    | PERHITUNGAN PERSENTASE
    |--------------------------------------------------------------------------
    */

    $adaDataSensor = $jarakSensor !== null && $sensorTerhubung;

    // Jarak > 23 cm tetap dianggap data valid (= wadah kosong / tidak ada pantulan), bukan 'tidak ada data'.
    $jarakValid = $adaDataSensor
        && $jarakSensor >= 0;

    $persentase = $jarakValid
        ? round(
            (($jarakKosong - $jarakSensor) /
            ($jarakKosong - $jarakPenuh)) * 100,
            1
        )
        : 0;

    $persentase = max(0, min(100, $persentase));

    /*
    |--------------------------------------------------------------------------
    | STATUS STOK BERDASARKAN JARAK (3 KONDISI)
    |--------------------------------------------------------------------------
    | Stok Banyak : 0  - 7 cm
    | Stok Cukup  : >7 - 16 cm
    | Stok Habis  : >16 - 23 cm (dan lebih)
    */

    if (!$adaDataSensor || !$jarakValid) {

        $statusStok = 'Menunggu Sensor';
        $warnaStatus = 'wait';
        $keteranganStok = 'Belum ada pembacaan sensor yang valid.';
        $kondisiJarak = 'Belum tersedia';

    } elseif ($jarakSensor <= 7) {

        $statusStok = 'Stok Banyak';
        $warnaStatus = 'safe';
        $keteranganStok = 'Permukaan pakan masih tinggi dan persediaan dalam kondisi banyak.';
        $kondisiJarak = 'Stok Banyak';

    } elseif ($jarakSensor <= 16) {

        $statusStok = 'Stok Cukup';
        $warnaStatus = 'warning';
        $keteranganStok = 'Persediaan pakan masih mencukupi, namun petani perlu mempersiapkan pengisian.';
        $kondisiJarak = 'Stok Cukup';

    } else {

        $statusStok = 'Stok Habis';
        $warnaStatus = 'danger';
        $keteranganStok = 'Persediaan pakan habis atau hampir habis dan perlu segera diisi kembali.';
        $kondisiJarak = 'Stok Habis';

    }

@endphp

<div class="stk container-fluid px-0">

    {{-- =====================================================
        HEADER
    ====================================================== --}}

    <div class="stk-header py-3 px-4 mb-4 text-white d-flex align-items-center">

        <div class="stk-bubbles" aria-hidden="true">
            <span style="--x:6%;--s:12px;--d:8s;--delay:0s;"></span>
            <span style="--x:24%;--s:8px;--d:6s;--delay:2s;"></span>
            <span style="--x:47%;--s:16px;--d:9s;--delay:1s;"></span>
            <span style="--x:68%;--s:9px;--d:7s;--delay:3s;"></span>
            <span style="--x:86%;--s:13px;--d:8.5s;--delay:.6s;"></span>
        </div>

        <div class="stk-waves" aria-hidden="true">

            <svg class="stk-wave stk-wave-back"
                viewBox="0 0 1200 60"
                preserveAspectRatio="none">

                <path d="M0,30 C150,60 300,0 450,30 C600,60 750,0 900,30 C1050,60 1200,0 1350,30 L1350,60 L0,60 Z"/>

            </svg>

            <svg class="stk-wave stk-wave-front"
                viewBox="0 0 1200 60"
                preserveAspectRatio="none">

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
                    Monitoring kondisi pakan dalam wadah IoT menggunakan sensor ultrasonik.
                </p>
            </div>

        </div>

    </div>


    <div class="px-4">

        {{-- =====================================================
            RINGKASAN INFORMASI
        ====================================================== --}}

        <div class="row g-4 mb-4">

            {{-- KAPASITAS WADAH --}}

            <div class="col-md-6">

                <div class="stk-card stk-metric stk-m1 card border-0 rounded-4 h-100 p-4">

                    <div class="d-flex justify-content-between align-items-start mb-3">

                        <span class="stk-metric-label fw-semibold small">
                            Kapasitas Wadah
                        </span>

                        <div class="stk-metric-icon rounded-2 p-2">
                            <i class="bi bi-box"></i>
                        </div>

                    </div>

                    <h3 class="stk-metric-value fw-bold mb-1">
                        {{ number_format($kapasitasWadah, 1, ',', '.') }}

                        <span class="fs-6 text-muted fw-normal">kg</span>
                    </h3>

                    <p class="text-muted small mb-0">
                        Kapasitas maksimum wadah pakan.
                    </p>

                </div>

            </div>


            {{-- JARAK SENSOR --}}

            <div class="col-md-6">

                <div class="stk-card stk-metric stk-m2 card border-0 rounded-4 h-100 p-4">

                    <div class="d-flex justify-content-between align-items-start mb-3">

                        <span class="stk-metric-label fw-semibold small">
                            Jarak Sensor
                        </span>

                        <div class="stk-metric-icon rounded-2 p-2">
                            <i class="bi bi-rulers"></i>
                        </div>

                    </div>

                    <h3 class="stk-metric-value fw-bold mb-1">

                        <span class="nilai-jarak-sensor">{{ $jarakValid
                            ? number_format($jarakSensor, 1, ',', '.')
                            : '---' }}</span>

                        <span class="fs-6 text-muted fw-normal">cm</span>

                    </h3>

                    <p class="text-muted small mb-0">
                        Jarak sensor ultrasonik ke permukaan pakan.
                    </p>

                </div>

            </div>

        </div>


        {{-- =====================================================
            INDIKATOR STOK PAKAN
        ====================================================== --}}

        <div class="stk-card stk-indicator card border-0 rounded-4 mb-4 p-4">

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">

                <h5 class="fw-bold mb-0 text-dark">
                    Indikator Stok Pakan
                </h5>

                <span id="badge-status" class="stk-badge stk-badge-{{ $warnaStatus }} badge px-3 py-2 rounded-pill">

                    {{ $statusStok }}

                </span>

            </div>


            {{-- PROGRESS BAR --}}

            <div class="mb-4">

                <div class="d-flex justify-content-between mb-2">

                    <span class="text-muted small fw-semibold">
                        Perkiraan Tingkat Isi Wadah
                    </span>

                    <span id="nilai-persen" class="fw-bold text-dark small">

                        {{ $jarakValid
                            ? number_format($persentase, 1, ',', '.') . '%'
                            : 'Belum ada data' }}

                    </span>

                </div>


                <div class="stk-progress progress rounded-pill"
                    style="height:14px;">

                    <div
                        id="bar-isi" class="progress-bar rounded-pill stk-progress-{{ $warnaStatus }}"
                        role="progressbar"
                        style="width: {{ $persentase }}%"
                        aria-valuenow="{{ $persentase }}"
                        aria-valuemin="0"
                        aria-valuemax="100">
                    </div>

                </div>

                <p id="keterangan-stok" class="text-muted small mt-2 mb-0">
                    {{ $keteranganStok }}
                </p>

            </div>


            {{-- BERAT LOAD CELL (realtime) --}}
            <div class="rounded-3 p-3 mb-3 border d-flex justify-content-between align-items-center">
                <span class="fw-bold small"><i class="bi bi-speedometer2 me-1"></i> Berat Timbangan (Load Cell)</span>
                <span class="fw-bold">
                    <span id="nilai-berat-loadcell">---</span> g
                    <small class="text-muted fw-normal ms-2">SMA-5: <span id="nilai-berat-sma">---</span> g</small>
                </span>
            </div>

            {{-- KONEKSI SENSOR --}}

            <div class="stk-sensor-box rounded-3 p-3 mb-4">

                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">

                    <div class="d-flex align-items-center gap-2">

                        <i class="bi bi-wifi fs-5"></i>

                        <div>

                            <div class="fw-bold small">
                                Koneksi Sensor ESP32
                            </div>

                    <div id="keterangan-koneksi" class="text-muted small">
    {{ $sensorTerhubung
        ? 'Perangkat berhasil terhubung.'
        : 'Menunggu koneksi perangkat.' }}
</div>

                        </div>

                    </div>


                  <span id="status-koneksi"
    class="badge {{ $sensorTerhubung ? 'bg-success' : 'bg-secondary' }}">
    {{ $sensorTerhubung ? 'Terhubung' : 'Tidak Terhubung' }}
</span>

                </div>

            </div>


            {{-- INFORMASI JARAK DAN KONDISI --}}

            <div class="row g-3">

                <div class="col-md-6">

                    <div class="stk-info-box rounded-3 p-3 h-100">

                        <span class="text-muted small d-block mb-2">
                            Hasil Pembacaan Ultrasonik
                        </span>

                        <h4 class="fw-bold mb-1">

                          <span class="nilai-jarak-sensor">
    {{ $jarakValid
        ? number_format($jarakSensor, 1, ',', '.')
        : '---' }}
</span>

                            <span class="fs-6 text-muted fw-normal">
                                cm
                            </span>

                        </h4>

                        <span class="text-muted small">
                            Jarak antara sensor dan permukaan pakan.
                        </span>

                    </div>

                </div>


                <div class="col-md-6">

                    <div class="stk-info-box rounded-3 p-3 h-100">

                        <span class="text-muted small d-block mb-2">
                            Kondisi Persediaan
                        </span>

                        <h5 id="kondisi-jarak" class="fw-bold mb-1">
                            {{ $kondisiJarak }}
                        </h5>

                        <span class="text-muted small">
                            Kondisi berdasarkan rentang jarak sensor.
                        </span>

                    </div>

                </div>

            </div>


            {{-- KETERANGAN BATAS PERSENTASE --}}
            {{-- Persentase = (23 - jarak) / 23 x 100. Batas 7 cm = 69,6% dan 16 cm = 30,4% --}}

            <div class="stk-legend row mt-4 pt-3 border-top g-3">

                <div class="col-md-4 d-flex align-items-center justify-content-between">

                    <div class="d-flex align-items-center gap-2">

                        <div class="stk-dot bg-success"></div>

                        <span class="text-muted small">
                            Stok Banyak
                        </span>

                    </div>

                    <span class="badge bg-success bg-opacity-10 text-success">
                        &ge; 69,6%
                    </span>

                </div>


                <div class="col-md-4 d-flex align-items-center justify-content-between border-start border-end px-md-4 stk-legend-mid">

                    <div class="d-flex align-items-center gap-2">

                        <div class="stk-dot bg-warning"></div>

                        <span class="text-muted small">
                            Stok Cukup
                        </span>

                    </div>

                    <span class="badge bg-warning bg-opacity-10 text-warning">
                        30,4% - 69,6%
                    </span>

                </div>


                <div class="col-md-4 d-flex align-items-center justify-content-between">

                    <div class="d-flex align-items-center gap-2">

                        <div class="stk-dot bg-danger"></div>

                        <span class="text-muted small">
                            Stok Habis
                        </span>

                    </div>

                    <span class="badge bg-danger bg-opacity-10 text-danger">
                        &lt; 30,4%
                    </span>

                </div>

            </div>

        </div>


        {{-- =====================================================
            ACUAN KONDISI REFILL
        ====================================================== --}}

        <div class="stk-card card border-0 rounded-4 p-4 mb-4">

            <h5 class="fw-bold mb-3">

                <i class="bi bi-info-circle text-primary me-2"></i>

                Acuan Kondisi Refill

            </h5>

            <p class="text-muted small mb-3">
                Kondisi refill ditentukan berdasarkan jarak permukaan pakan terhadap sensor ultrasonik.
            </p>


            <div class="row g-3">

                <div class="col-md-4">

                    <div class="stk-range">

                        <span class="fw-bold text-success">
                            0 - 7 cm
                        </span>

                        <span class="text-muted small d-block">
                            Stok banyak
                        </span>

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="stk-range">

                        <span class="fw-bold text-warning">
                            &gt; 7 - 16 cm
                        </span>

                        <span class="text-muted small d-block">
                            Stok cukup
                        </span>

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="stk-range">

                        <span class="fw-bold text-danger">
                            &gt; 16 - 23 cm
                        </span>

                        <span class="text-muted small d-block">
                            Stok habis, segera refill
                        </span>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


{{-- =====================================================
    CSS HALAMAN STOK
====================================================== --}}

<style>

@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

.stk {
    --sea-deep: #052e4a;
    --sea-mid: #0a6a8f;
    --sea-bright: #1296c4;
    --aqua: #5eead4;
    --ink: #0f2a3a;
    --muted: #5b7384;

    min-height: 100vh;
    font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
    background: linear-gradient(160deg, #e8f6fa 0%, #f4fafc 50%, #e3f1f7 100%);
    color: var(--ink);
    padding-bottom: 1rem;
}

.stk h3,
.stk h4,
.stk h5 {
    letter-spacing: -0.02em;
}


/* ANIMASI */

@keyframes stkFloat {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-5px); }
}

@keyframes stkHeaderIn {
    from {
        opacity: 0;
        transform: translateY(-16px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes stkBubble {
    0% {
        transform: translateY(0);
        opacity: 0;
    }

    15% {
        opacity: 1;
    }

    100% {
        transform: translateY(-120px) translateX(12px);
        opacity: 0;
    }
}

@keyframes stkWave {
    from { transform: translateX(0); }
    to { transform: translateX(-50%); }
}

@keyframes stkCardIn {
    from {
        opacity: 0;
        transform: translateY(26px) scale(.98);
    }

    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

@keyframes stkFlow {
    from { background-position: 0 0; }
    to { background-position: -200% 0; }
}


/* HEADER */

.stk-header {
    position: relative;
    width: 100%;
    overflow: hidden;
    padding-bottom: 2.25rem !important;

    background:
        radial-gradient(circle at 15% 10%, rgba(94,234,212,.3), transparent 45%),
        linear-gradient(135deg, var(--sea-bright), var(--sea-mid) 55%, var(--sea-deep));

    box-shadow: 0 10px 24px -12px rgba(5,46,74,.5);
    animation: stkHeaderIn .7s ease-out both;
}

.stk-header-inner {
    position: relative;
    z-index: 3;
}

.stk-header-icon {
    background: rgba(255,255,255,.2);
    border: 1px solid rgba(255,255,255,.3);
    backdrop-filter: blur(4px);
    animation: stkFloat 5s ease-in-out infinite;
}


/* GELEMBUNG */

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

    background: radial-gradient(
        circle at 30% 30%,
        rgba(255,255,255,.75),
        rgba(255,255,255,.1)
    );

    border: 1px solid rgba(255,255,255,.4);
    animation: stkBubble var(--d) var(--delay) linear infinite;
}


/* GELOMBANG */

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
    fill: rgba(255,255,255,.18);
    animation: stkWave 10s linear infinite;
}

.stk-wave-front {
    fill: rgba(255,255,255,.3);
    height: 70%;
    animation: stkWave 6s linear infinite reverse;
}


/* KARTU */

.stk-card {
    position: relative;
    overflow: hidden;
    background: #fff;

    box-shadow: 0 10px 25px -14px rgba(5,46,74,.3);

    transition:
        transform .3s ease,
        box-shadow .3s ease;

    animation: stkCardIn .7s cubic-bezier(.22,1,.36,1) backwards;
}

.stk-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 34px -16px rgba(5,46,74,.38);
}


/* KARTU RINGKASAN */

.stk-metric {
    --accent: var(--sea-bright);
    --accent-soft: rgba(18,150,196,.13);

    border-top: 4px solid var(--accent) !important;
}

.stk-m1 {
    animation-delay: .15s;
    --accent: #0a6a8f;
    --accent-soft: rgba(10,106,143,.13);
}

.stk-m2 {
    animation-delay: .27s;
    --accent: #14b8a6;
    --accent-soft: rgba(20,184,166,.14);
}

.stk-metric::after {
    content: '';
    position: absolute;
    right: -45px;
    bottom: -55px;
    width: 140px;
    height: 140px;
    border-radius: 50%;
    background: var(--accent-soft);
    transition: transform .5s ease;
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
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--accent-soft);
    color: var(--accent);
    animation: stkFloat 4.5s ease-in-out infinite;
}

.stk-metric-value {
    color: var(--ink);
    font-weight: 800 !important;
    font-variant-numeric: tabular-nums;
}

.stk-m2 .stk-metric-value {
    color: var(--sea-mid);
}


/* INDIKATOR */

.stk-indicator {
    animation-delay: .5s;
}

.stk-indicator::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;

    background: linear-gradient(
        90deg,
        var(--sea-bright),
        var(--aqua),
        var(--sea-bright)
    );

    background-size: 200% 100%;
    animation: stkFlow 6s linear infinite;
}


/* BADGE STATUS */

.stk-badge {
    font-weight: 700;
}

.stk-badge-safe {
    background: rgba(22,163,74,.12);
    color: #15803d;
}

.stk-badge-warning {
    background: rgba(245,158,11,.14);
    color: #b45309;
}

.stk-badge-danger {
    background: rgba(220,38,38,.12);
    color: #b91c1c;
}

.stk-badge-wait {
    background: rgba(100,116,139,.13);
    color: #475569;
}


/* PROGRESS BAR */

.stk-progress {
    position: relative;
    overflow: hidden;
    background: #e1eef4;
}

.stk-progress .progress-bar {
    transition: width 1s ease;
}

.stk-progress-safe {
    background: linear-gradient(90deg,#16a34a,#4ade80);
}

.stk-progress-warning {
    background: linear-gradient(90deg,#f59e0b,#facc15);
}

.stk-progress-danger {
    background: linear-gradient(90deg,#dc2626,#f87171);
}

.stk-progress-wait {
    background: #94a3b8;
}


/* KONEKSI DAN INFORMASI SENSOR */

.stk-sensor-box {
    background: #f4f9fc;
    border: 1px solid #e1edf3;
}

.stk-info-box {
    background: #f6fafc;
    border: 1px solid #e1edf3;
}


/* ACUAN REFILL */

.stk-range {
    height: 100%;
    padding: 15px;
    border-radius: 12px;
    background: #f6fafc;
    border: 1px solid #e1edf3;
}


/* LEGEND */

.stk-legend {
    border-color: #e3eef4 !important;
}

.stk-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    flex-shrink: 0;
}

.stk .badge {
    font-weight: 700;
    border-radius: 999px;
    padding: .4em .8em;
}


/* RESPONSIVE */

@media (max-width: 767px) {

    .stk-legend-mid {
        border: none !important;
        padding-left: .75rem !important;
        padding-right: .75rem !important;
    }

    .stk-header h4 {
        font-size: 1.15rem;
    }

    .stk-card:hover {
        transform: none;
    }

}


/* REDUCE MOTION */

@media (prefers-reduced-motion: reduce) {

    .stk *,
    .stk *::before,
    .stk *::after {

        animation-duration: .01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: .01ms !important;

    }

}


</style>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // Kalibrasi & batas status (harus sama dengan blok @php di atas)
    const JARAK_KOSONG = 23;
    const JARAK_PENUH  = 0;

    const fmt = (n) => n.toFixed(1).replace('.', ',');
    const el  = (id) => document.getElementById(id);
    const setText = (id, t) => { const e = el(id); if (e) e.textContent = t; };

    // 3 kondisi: Stok Banyak (0-7), Stok Cukup (>7-16), Stok Habis (>16-23)
    function statusDariJarak(j) {
        if (j <= 7)  return ['Stok Banyak', 'safe', 'Permukaan pakan masih tinggi dan persediaan dalam kondisi banyak.', 'Stok Banyak'];
        if (j <= 16) return ['Stok Cukup', 'warning', 'Persediaan pakan masih mencukupi, namun petani perlu mempersiapkan pengisian.', 'Stok Cukup'];
        return ['Stok Habis', 'danger', 'Persediaan pakan habis atau hampir habis dan perlu segera diisi kembali.', 'Stok Habis'];
    }

    async function updateSensorData() {
        try {
            const response = await fetch('/api/sensor-data/latest', { cache: 'no-store' });
            if (!response.ok) throw new Error('Gagal mengambil data sensor');

            const result = await response.json();
            const data = result.data;

            // Status terhubung ditentukan SERVER (hindari beda jam server vs browser)
            const terhubung = result.sensor_terhubung === true;

            const jarak = data && data.jarak !== null ? Number(data.jarak) : NaN;
            const valid = terhubung && !isNaN(jarak) && jarak >= 0;

            // Koneksi
            const status = el('status-koneksi');
            if (status) {
                status.textContent = terhubung ? 'Terhubung' : 'Tidak Terhubung';
                status.className = terhubung ? 'badge bg-success' : 'badge bg-secondary';
            }
            setText('keterangan-koneksi', terhubung ? 'Perangkat berhasil terhubung.' : 'Menunggu koneksi perangkat.');

            // Jarak (semua tempat yang menampilkan jarak)
            document.querySelectorAll('.nilai-jarak-sensor')
                .forEach(e => { e.textContent = valid ? fmt(jarak) : '---'; });

            // Load cell
            setText('nilai-berat-loadcell',
                terhubung && data && data.berat_g != null ? fmt(Number(data.berat_g)) : '---');
            setText('nilai-berat-sma',
                terhubung && data && data.berat_sma != null ? fmt(Number(data.berat_sma)) : '---');

            // Persentase, bar, badge, keterangan
            let persen = 0, st = ['Menunggu Sensor', 'wait', 'Belum ada pembacaan sensor yang valid.', 'Belum tersedia'];
            if (valid) {
                persen = Math.round(((JARAK_KOSONG - jarak) / (JARAK_KOSONG - JARAK_PENUH)) * 1000) / 10;
                persen = Math.max(0, Math.min(100, persen));
                st = statusDariJarak(jarak);
            }

            setText('nilai-persen', valid ? fmt(persen) + '%' : 'Belum ada data');
            setText('keterangan-stok', st[2]);
            setText('kondisi-jarak', st[3]);

            const badge = el('badge-status');
            if (badge) {
                badge.textContent = st[0];
                badge.className = 'stk-badge stk-badge-' + st[1] + ' badge px-3 py-2 rounded-pill';
            }

            const bar = el('bar-isi');
            if (bar) {
                bar.style.width = persen + '%';
                bar.setAttribute('aria-valuenow', persen);
                bar.className = 'progress-bar rounded-pill stk-progress-' + st[1];
            }

        } catch (error) {
            console.error('Monitoring sensor:', error);
        }
    }

    updateSensorData();
    setInterval(updateSensorData, 2000);
});
</script>

@endsection
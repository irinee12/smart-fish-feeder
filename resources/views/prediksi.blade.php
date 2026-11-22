@extends('layouts.app')

@section('title', 'Prediksi Refill Pakan - Smart Fish Feeder')

@section('content')

@php
    $fmt = fn($v, $d = 2) => number_format($v, $d, ',', '.');

    if ($sisaKg === null) {
        $statusClass = 'secondary'; $statusIcon = 'bi-info-circle';
        $judul = 'Belum ada stok tercatat';
        $isi   = 'Catat dulu pakan yang baru dibeli pada form di bawah.';
    } elseif ($smaKg === null) {
        $statusClass = 'secondary'; $statusIcon = 'bi-clock-history';
        $judul = 'Menunggu data pemakaian';
        $isi   = 'Prediksi muncul setelah alat mencatat pemberian pakan minimal 1 hari penuh.';
    } elseif ($perluBeliKg > 0) {
        $statusClass = $hariTahan <= 7 ? 'danger' : 'warning'; $statusIcon = 'bi-cart-plus';
        $judul = 'Disarankan membeli sekitar ' . $perluBeliKg . ' kg';
        $isi   = 'Stok sekarang ' . $fmt($sisaKg) . ' kg hanya bertahan sekitar ' . $fmt($hariTahan, 0) . ' hari.';
    } else {
        $statusClass = 'success'; $statusIcon = 'bi-check-circle';
        $judul = 'Persediaan cukup, belum perlu membeli';
        $isi   = 'Stok ' . $fmt($sisaKg) . ' kg cukup untuk sekitar ' . $fmt($hariTahan, 0) . ' hari (± ' . $fmt($mingguTahan, 1) . ' minggu).';
    }
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

.step-row{display:flex;gap:16px;padding:20px 0;border-bottom:1px solid #e9f1f5}
.step-row:last-child{border-bottom:0;padding-bottom:4px}
.step-no{flex:0 0 38px;height:38px;border-radius:50%;display:grid;place-items:center;font-weight:800;color:#fff;background:linear-gradient(135deg,#1296c4,#0a6a8f)}
.step-body{min-width:0;flex:1}
.step-title{font-weight:800;font-size:15px;margin-bottom:2px}
.step-rumus{font-size:12px;color:#668091;margin-bottom:8px}
.step-hitung{font-size:15px;background:#f2f9fc;border:1px solid #dfedf3;border-radius:10px;padding:11px 14px;overflow-wrap:anywhere}
.step-hitung b{color:#0a6a8f;font-size:17px}
</style>

<div class="prediction-page">

    <div class="prediction-hero">
        <div class="hero-content">
            <div class="hero-icon"><i class="bi bi-boxes"></i></div>
            <div>
                <h4>Prediksi Refill Pakan</h4>
                <p>Berapa lama stok gudang bertahan dan kapan harus beli lagi, dihitung dengan metode SMA.</p>
            </div>
        </div>
    </div>

    <div class="prediction-container">

        @if(session('success'))
            <div class="alert alert-success"><i class="bi bi-check-circle me-2"></i>{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif

        {{-- JAWABAN UTAMA --}}
        <div class="status-box {{ $statusClass }}">
            <div class="status-icon"><i class="bi {{ $statusIcon }}"></i></div>
            <div>
                <strong style="font-size:16px">{{ $judul }}</strong>
                <p>{{ $isi }}</p>
            </div>
        </div>

        {{-- CARA MENGHITUNG, 4 LANGKAH --}}
        <div class="section-card">
            <h5 class="section-title"><i class="bi bi-list-ol"></i>Cara Menghitungnya (4 Langkah)</h5>

            {{-- LANGKAH 1 --}}
            <div class="step-row">
                <div class="step-no">1</div>
                <div class="step-body">
                    <div class="step-title">Berapa stok gudang sekarang?</div>
                    <div class="step-rumus">Stok = total pakan dibeli − total pakan yang sudah keluar</div>
                    <div class="step-hitung">
                        @if($sisaKg !== null)
                            {{ $fmt($masukKg) }} kg − {{ $fmt($keluarKg) }} kg = <b>{{ $fmt($sisaKg) }} kg</b>
                        @else
                            Belum ada pakan yang dicatat.
                        @endif
                    </div>
                </div>
            </div>

            {{-- LANGKAH 2 --}}
            <div class="step-row">
                <div class="step-no">2</div>
                <div class="step-body">
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                        <div>
                            <div class="step-title">Rata-rata pakan keluar per hari (SMA)</div>
                            <div class="step-rumus">SMA = (hari 1 + hari 2 + … ) ÷ jumlah hari, memakai {{ $nSma }} hari terakhir. Hari ini tidak ikut karena belum selesai.</div>
                        </div>
                        <form method="GET" action="{{ url()->current() }}">
                            <select name="n_sma" class="sma-selector" onchange="this.form.submit()">
                                <option value="3" {{ $nSma == 3 ? 'selected' : '' }}>SMA 3 hari</option>
                                <option value="7" {{ $nSma == 7 ? 'selected' : '' }}>SMA 7 hari</option>
                            </select>
                        </form>
                    </div>

                    <div class="step-hitung">
                        @if($smaGram !== null)
                            ({{ $rincian->map(fn($r) => $fmt($r['gram'], 1))->implode(' + ') }}) ÷ {{ $jumlahData }}
                            = <b>{{ $fmt($smaGram, 1) }} g/hari</b>
                            ({{ $fmt($smaKg, 3) }} kg/hari)
                        @else
                            Belum ada hari lengkap yang tercatat.
                        @endif
                    </div>

                    @if($rincian->count() > 0)
                        <div class="table-responsive mt-2">
                            <table class="table table-sm mb-0" style="max-width:420px">
                                <thead><tr><th>Tanggal</th><th>Pakan keluar</th></tr></thead>
                                <tbody>
                                    @foreach($rincian as $r)
                                        <tr>
                                            <td>
                                                {{ $r['tanggal'] }}
                                                @if($r['dummy'])<span class="badge bg-secondary ms-1">contoh</span>@endif
                                            </td>
                                            <td>{{ $fmt($r['gram'], 1) }} g</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                    @if($smaGram !== null && $jumlahData < $nSma)
                        <div class="info-text">Baru ada {{ $jumlahData }} hari data, jadi rata-rata memakai {{ $jumlahData }} hari. Makin banyak hari tercatat, makin akurat.</div>
                    @endif
                    @if($adaDummy)
                        <div class="info-text">Baris bertanda "contoh" adalah data uji (<code>php artisan sma:dummy</code>), hanya dipakai di langkah ini.</div>
                    @endif
                </div>
            </div>

            {{-- LANGKAH 3 --}}
            <div class="step-row">
                <div class="step-no">3</div>
                <div class="step-body">
                    <div class="step-title">Stok akan bertahan berapa lama?</div>
                    <div class="step-rumus">Lama bertahan = stok gudang ÷ rata-rata per hari</div>
                    <div class="step-hitung">
                        @if($hariTahan !== null)
                            {{ $fmt($sisaKg) }} kg ÷ {{ $fmt($smaKg, 3) }} kg/hari =
                            <b>{{ $fmt($hariTahan, 0) }} hari</b> (± {{ $fmt($mingguTahan, 1) }} minggu)<br>
                            <small class="text-muted">Perkiraan habis sekitar {{ $tanggalHabis->translatedFormat('d F Y') }}</small>
                        @else
                            Menunggu langkah 1 dan 2.
                        @endif
                    </div>
                </div>
            </div>

            {{-- LANGKAH 4 --}}
            <div class="step-row">
                <div class="step-no">4</div>
                <div class="step-body">
                    <div class="step-title">Perlu beli berapa kg?</div>
                    <div class="step-rumus">Perlu beli = (rata-rata per hari × {{ $hariKebutuhan }} hari) − stok gudang. Jika hasilnya 0 atau minus, belum perlu beli.</div>
                    <div class="step-hitung">
                        @if($kebutuhanKg !== null && $sisaKg !== null)
                            ({{ $fmt($smaKg, 3) }} × {{ $hariKebutuhan }}) − {{ $fmt($sisaKg) }}
                            = {{ $fmt($kebutuhanKg - $sisaKg) }} kg →
                            @if($perluBeliKg > 0)
                                <b>beli ± {{ $perluBeliKg }} kg</b>
                            @else
                                <b>belum perlu beli</b>
                            @endif
                        @else
                            Menunggu langkah 1 dan 2.
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- CATAT PAKAN DIBELI --}}
        <div class="section-card">
            <h5 class="section-title"><i class="bi bi-plus-circle"></i>Catat Pakan Baru Dibeli</h5>

            <form method="POST" action="{{ route('prediksi.update') }}">
                @csrf
                <div class="form-grid">
                    <div>
                        <label for="tanggal_stok">Tanggal Beli</label>
                        <input type="date" class="form-control" id="tanggal_stok" name="tanggal_stok"
                               value="{{ old('tanggal_stok', now()->format('Y-m-d')) }}" required>
                    </div>
                    <div>
                        <label for="jumlah_stok_kg">Jumlah (kg)</label>
                        <input type="number" class="form-control" id="jumlah_stok_kg" name="jumlah_stok_kg"
                               step="0.01" min="0.01" placeholder="Contoh: 50"
                               value="{{ old('jumlah_stok_kg') }}" required>
                    </div>
                    <button type="submit" class="btn-add-stock"><i class="bi bi-plus-lg me-2"></i>Simpan</button>
                </div>
            </form>

            <div class="info-text">
                Isi setiap kali membeli pakan, misalnya satu karung 50 kg. Setelah itu stok berkurang otomatis
                sesuai pakan yang benar-benar keluar dari alat. Tidak perlu diisi ulang.
            </div>

            @if($riwayatStok->count() > 0)
                <div class="table-responsive mt-3">
                    <table class="table table-sm mb-0" style="max-width:420px">
                        <thead><tr><th>Pembelian terakhir</th><th>Jumlah</th></tr></thead>
                        <tbody>
                            @foreach($riwayatStok as $b)
                                <tr>
                                    <td>{{ $b->tanggal->format('d M Y') }}</td>
                                    <td>{{ $fmt((float) $b->jumlah_kg) }} kg</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

    </div>
</div>

@endsection

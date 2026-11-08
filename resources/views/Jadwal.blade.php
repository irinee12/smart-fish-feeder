@extends('layouts.app')

@section('title', 'Jadwal Pakan - Smart Fish Feeder')

@section('content')
<div class="jdw container-fluid px-0">

    {{-- =====================================================
        HEADER FULL WARNA (HANYA JUDUL & DESKRIPSI)
    ===================================================== --}}
    <div class="jdw-header py-3 px-4 mb-4 text-white d-flex align-items-center">

        {{-- GELEMBUNG DAN GELOMBANG (VISUAL SAJA) --}}
        <div class="jdw-bubbles" aria-hidden="true">
            <span style="--x:6%;  --s:12px; --d:8s;  --delay:0s;"></span>
            <span style="--x:24%; --s:8px;  --d:6s;  --delay:2s;"></span>
            <span style="--x:47%; --s:16px; --d:9s;  --delay:1s;"></span>
            <span style="--x:68%; --s:9px;  --d:7s;  --delay:3s;"></span>
            <span style="--x:86%; --s:13px; --d:8.5s;--delay:0.6s;"></span>
        </div>
        <div class="jdw-waves" aria-hidden="true">
            <svg class="jdw-wave jdw-wave-back" viewBox="0 0 1200 60" preserveAspectRatio="none">
                <path d="M0,30 C150,60 300,0 450,30 C600,60 750,0 900,30 C1050,60 1200,0 1350,30 L1350,60 L0,60 Z"/>
            </svg>
            <svg class="jdw-wave jdw-wave-front" viewBox="0 0 1200 60" preserveAspectRatio="none">
                <path d="M0,30 C150,0 300,60 450,30 C600,0 750,60 900,30 C1050,0 1200,60 1350,30 L1350,60 L0,60 Z"/>
            </svg>
        </div>

        <div class="jdw-header-inner d-flex align-items-center gap-3">
            <div class="jdw-header-icon p-2 rounded-3">
                <i class="bi bi-clock-history fs-5 text-white"></i>
            </div>
            <div>
                <h4 class="fw-bold mb-0">Jadwal Pakan</h4>
                <p class="text-white text-opacity-75 mb-0 small">
                    Kelola waktu dan porsi pemberian pakan otomatis.
                </p>
            </div>
        </div>
    </div>

    {{-- BUNGKUS KONTEN DI BAWAHNYA DIBERI PADDING SUPAYA TIDAK IKUT MENEMPEL KE PINGGIR --}}
    <div class="px-4">

        {{-- ============================================= --}}
        {{-- TOMBOL TAMBAH JADWAL (HANYA TAMPIL JIKA LOGIN) --}}
        {{-- ============================================= --}}
        @auth
        <div class="jdw-actions d-flex justify-content-end mb-3">
            <button type="button" class="btn btn-primary d-flex align-items-center gap-2 px-3 py-2 rounded-3" onclick="bukaPopupTambahJadwal()">
                <i class="bi bi-plus-lg fs-5"></i>
                <span class="fw-semibold">Tambah Jadwal</span>
            </button>
        </div>
        @endauth

        {{-- ============================= --}}
        {{-- NOTIFIKASI SUKSES --}}
        {{-- ============================= --}}
        @if(session('success'))
            <div class="jdw-alert alert alert-success alert-dismissible fade show rounded-3 shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- ============================= --}}
        {{-- KARTU DAFTAR JADWAL --}}
        {{-- ============================= --}}
        <div class="jdw-card card border-0 rounded-4 mb-4">
            <div class="jdw-card-head card-header border-bottom p-4">
                <h5 class="fw-bold mb-1 text-dark">Daftar Jadwal Pakan</h5>
                <p class="text-muted small mb-0">Jadwal pemberian pakan yang telah ditentukan pada sistem.</p>
            </div>

            <div class="table-responsive">
                <table class="jdw-table table table-hover align-middle mb-0">
                    <thead>
                        <tr class="text-muted small text-uppercase">
                            <th class="py-3 px-4 fw-semibold">Waktu</th>
                            <th class="py-3 px-4 fw-semibold">Sesi</th>
                            <th class="py-3 px-4 fw-semibold">Porsi</th>
                            <th class="py-3 px-4 fw-semibold">Status</th>
                            {{-- HEADER AKSI HANYA TAMPIL JIKA LOGIN --}}
                            @auth
                            <th class="py-3 px-4 fw-semibold text-end">Aksi</th>
                            @endauth
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jadwals as $jadwal)
                            <tr>
                                <td class="py-3 px-4 fw-semibold">{{ \Carbon\Carbon::parse($jadwal->waktu)->format('H:i') }}</td>
                                <td class="py-3 px-4">{{ $jadwal->sesi }}</td>
                                <td class="py-3 px-4">{{ $jadwal->porsi }} g</td>
                                <td class="py-3 px-4">
                                    <span class="jdw-badge-active badge bg-success bg-opacity-10 text-success rounded-pill px-3">Aktif</span>
                                </td>
                                {{-- TOMBOL HAPUS HANYA TAMPIL JIKA LOGIN --}}
                                @auth
                                <td class="py-3 px-4 text-end">
                                    <form action="{{ route('jadwal.destroy', $jadwal->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus jadwal pakan ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="jdw-btn-delete btn btn-sm btn-outline-danger rounded-3" title="Hapus Jadwal">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                                @endauth
                            </tr>
                        @empty
                            {{-- KONDISI JIKA DATABASE BELUM ADA / KOSONG --}}
                            <tr class="jdw-empty-row">
                                {{-- COLSPAN MENYESUAIKAN APAKAH USER LOGIN (5 KOLOM) ATAU TIDAK (4 KOLOM) --}}
                                <td colspan="{{ auth()->check() ? 5 : 4 }}" class="py-5 text-center text-muted">
                                    <div class="d-flex flex-column align-items-center justify-content-center py-4">
                                        <div class="jdw-empty-circle rounded-circle d-flex align-items-center justify-content-center mb-3">
                                            <i class="bi bi-clock-history fs-2"></i>
                                        </div>
                                        <h6 class="fw-bold text-dark mb-1">Belum ada jadwal pakan</h6>
                                        <p class="text-muted small mb-0">Data jadwal akan tampil otomatis setelah ditambahkan atau database terhubung.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- TOTAL PAKAN --}}
            <div class="jdw-total card-footer border-top p-3 px-4 d-flex justify-content-end align-items-center">
                <span class="text-muted me-2">Total pakan hari ini:</span>
                <strong class="text-dark fs-6">0 g (0,00 kg)</strong>
            </div>
        </div>

    </div>
</div>

{{-- ================================================= --}}
{{-- POPUP / MODAL TAMBAH JADWAL (HANYA RENDER JIKA LOGIN) --}}
{{-- ================================================= --}}
@auth
<div class="popup-overlay jdw-overlay" id="popupTambahJadwal">
    <div class="popup-card jdw-popup rounded-4 overflow-hidden">

        {{-- HEADER POPUP --}}
        <div class="jdw-pop-header p-4 d-flex justify-content-between align-items-center">
            <div>
                <h5 class="fw-bold mb-1 text-dark">Tambah Jadwal Pakan</h5>
                <p class="text-muted small mb-0">Atur waktu, sesi, dan jumlah pakan harian.</p>
            </div>
            <button type="button" class="btn-close shadow-none" onclick="tutupPopupTambahJadwal()" aria-label="Tutup"></button>
        </div>

        {{-- BUNGKUS DENGAN TAG FORM --}}
        <form action="{{ route('jadwal.store') }}" method="POST">
            @csrf

            {{-- FORM POPUP --}}
            <div class="p-4 d-flex flex-column gap-3">
                <div class="mb-1">
                    <label for="waktu" class="form-label fw-semibold small text-secondary">Waktu</label>
                    <input type="time" id="waktu" name="waktu" class="form-control rounded-3 py-2" required>
                </div>

                <div class="mb-1">
                    <label for="sesi" class="form-label fw-semibold small text-secondary">Sesi</label>
                    <select id="sesi" name="sesi" class="form-select rounded-3 py-2" required>
                        <option value="Pagi">Pagi</option>
                        <option value="Siang">Siang</option>
                        <option value="Sore">Sore</option>
                        <option value="Malam">Malam</option>
                    </select>
                </div>

                <div class="mb-1">
                    <label for="porsi" class="form-label fw-semibold small text-secondary">Porsi (gram)</label>
                    <input type="number" id="porsi" name="porsi" class="form-control rounded-3 py-2" placeholder="Contoh: 500" min="50" max="2000" required>
                </div>
            </div>

            {{-- TOMBOL AKSI POPUP --}}
            <div class="jdw-pop-footer p-3 px-4 border-top d-flex justify-content-end gap-2">
                <button type="button" class="btn btn-outline-secondary px-4 rounded-3" onclick="tutupPopupTambahJadwal()">Batal</button>
                <button type="submit" class="btn btn-primary px-4 rounded-3">
                    <i class="bi bi-plus-lg me-1"></i> Simpan Jadwal
                </button>
            </div>
        </form>

    </div>
</div>
@endauth

{{-- CSS HALAMAN JADWAL (diawali .jdw agar tidak mengganggu halaman lain) --}}
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

    .jdw,
    .jdw-overlay {
        --sea-deep: #052e4a;
        --sea-mid: #0a6a8f;
        --sea-bright: #1296c4;
        --aqua: #5eead4;
        --ink: #0f2a3a;
        --muted: #5b7384;
        --line: #d3e4ec;
        font-family: 'Plus Jakarta Sans', system-ui, -apple-system, 'Segoe UI', sans-serif;
    }

    .jdw {
        min-height: 100vh;
        background: linear-gradient(160deg, #e8f6fa 0%, #f4fafc 50%, #e3f1f7 100%);
        color: var(--ink);
        padding-bottom: 1rem;
    }

    .jdw h4, .jdw h5, .jdw h6,
    .jdw-overlay h5 {
        letter-spacing: -0.02em;
    }

    /* ---------- HEADER ---------- */
    .jdw-header {
        position: relative;
        width: 100%;
        overflow: hidden;
        padding-bottom: 2.25rem !important;
        background:
            radial-gradient(circle at 15% 10%, rgba(94, 234, 212, 0.3), transparent 45%),
            linear-gradient(135deg, var(--sea-bright) 0%, var(--sea-mid) 55%, var(--sea-deep) 100%);
        box-shadow: 0 10px 24px -12px rgba(5, 46, 74, 0.5);
        animation: jdwHeaderIn 0.7s ease-out both;
    }

    @keyframes jdwHeaderIn {
        from { opacity: 0; transform: translateY(-16px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .jdw-header-inner {
        position: relative;
        z-index: 3;
    }

    .jdw-header-icon {
        background: rgba(255, 255, 255, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.3);
        backdrop-filter: blur(4px);
        animation: jdwFloat 5s ease-in-out infinite;
    }

    @keyframes jdwFloat {
        0%, 100% { transform: translateY(0); }
        50%      { transform: translateY(-5px); }
    }

    .jdw-bubbles {
        position: absolute;
        inset: 0;
        z-index: 1;
        pointer-events: none;
    }

    .jdw-bubbles span {
        position: absolute;
        bottom: -20px;
        left: var(--x);
        width: var(--s);
        height: var(--s);
        border-radius: 50%;
        background: radial-gradient(circle at 30% 30%, rgba(255, 255, 255, 0.75), rgba(255, 255, 255, 0.1));
        border: 1px solid rgba(255, 255, 255, 0.4);
        animation: jdwBubble var(--d) var(--delay) linear infinite;
    }

    @keyframes jdwBubble {
        0%   { transform: translateY(0) translateX(0); opacity: 0; }
        15%  { opacity: 1; }
        100% { transform: translateY(-120px) translateX(12px); opacity: 0; }
    }

    .jdw-waves {
        position: absolute;
        left: 0;
        right: 0;
        bottom: 0;
        height: 30px;
        z-index: 2;
        overflow: hidden;
        pointer-events: none;
    }

    .jdw-wave {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 200%;
        height: 100%;
    }

    .jdw-wave-back {
        fill: rgba(255, 255, 255, 0.18);
        animation: jdwWave 10s linear infinite;
    }

    .jdw-wave-front {
        fill: rgba(255, 255, 255, 0.3);
        height: 70%;
        animation: jdwWave 6s linear infinite reverse;
    }

    @keyframes jdwWave {
        from { transform: translateX(0); }
        to   { transform: translateX(-50%); }
    }

    /* ---------- TOMBOL (dipakai di halaman dan popup) ---------- */
    .jdw .btn,
    .jdw-overlay .btn {
        position: relative;
        overflow: hidden;
        font-family: inherit;
        font-weight: 700;
        transition: transform 0.25s ease, box-shadow 0.25s ease, background-color 0.25s ease, border-color 0.25s ease, color 0.25s ease;
    }

    .jdw .btn:active,
    .jdw-overlay .btn:active {
        transform: scale(0.97);
    }

    .jdw .btn-primary,
    .jdw-overlay .btn-primary {
        background: linear-gradient(135deg, var(--sea-bright), var(--sea-mid));
        border-color: transparent;
        color: #ffffff;
        box-shadow: 0 8px 18px -6px rgba(10, 106, 143, 0.55);
    }

    /* Kilau yang lewat saat tombol di-hover */
    .jdw .btn-primary::after,
    .jdw-overlay .btn-primary::after {
        content: '';
        position: absolute;
        top: 0;
        left: -80%;
        width: 50%;
        height: 100%;
        background: linear-gradient(100deg, transparent, rgba(255, 255, 255, 0.4), transparent);
        transform: skewX(-20deg);
        pointer-events: none;
    }

    .jdw .btn-primary:hover,
    .jdw-overlay .btn-primary:hover {
        background: linear-gradient(135deg, var(--sea-bright), var(--sea-mid));
        border-color: transparent;
        color: #ffffff;
        transform: translateY(-2px);
        box-shadow: 0 14px 24px -8px rgba(10, 106, 143, 0.6);
    }

    .jdw .btn-primary:hover::after,
    .jdw-overlay .btn-primary:hover::after {
        left: 130%;
        transition: left 0.7s ease;
    }

    .jdw .btn-primary:focus-visible,
    .jdw-overlay .btn-primary:focus-visible {
        outline: 3px solid rgba(18, 150, 196, 0.35);
        outline-offset: 2px;
    }

    .jdw-actions {
        animation: jdwFadeUp 0.6s 0.15s ease-out backwards;
    }

    .jdw-actions .bi-plus-lg {
        transition: transform 0.3s ease;
    }

    .jdw-actions .btn:hover .bi-plus-lg {
        transform: rotate(90deg) scale(1.1);
    }

    .jdw-overlay .btn-outline-secondary {
        border-color: #c4dbe6;
        color: #36566b;
    }

    .jdw-overlay .btn-outline-secondary:hover {
        background-color: #eaf5f9;
        border-color: var(--sea-bright);
        color: var(--sea-mid);
        transform: translateY(-1px);
    }

    @keyframes jdwFadeUp {
        from { opacity: 0; transform: translateY(22px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* ---------- NOTIFIKASI SUKSES ---------- */
    .jdw-alert {
        background: #ecfdf5;
        border: 1px solid #a7f3d0;
        color: #047857;
        animation: jdwFadeUp 0.5s ease-out backwards;
    }

    /* ---------- KARTU DAFTAR JADWAL ---------- */
    .jdw-card {
        background: #ffffff;
        overflow: hidden;
        box-shadow: 0 12px 28px -16px rgba(5, 46, 74, 0.32);
        animation: jdwCardIn 0.7s 0.25s cubic-bezier(0.22, 1, 0.36, 1) backwards;
    }

    @keyframes jdwCardIn {
        from { opacity: 0; transform: translateY(26px) scale(0.98); }
        to   { opacity: 1; transform: translateY(0) scale(1); }
    }

    .jdw-card-head {
        position: relative;
        background: linear-gradient(180deg, #f5fbfd 0%, #ffffff 100%) !important;
        border-color: #e3eef4 !important;
    }

    /* Garis air tipis yang mengalir di atas kartu */
    .jdw-card-head::before,
    .jdw-pop-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, var(--sea-bright), var(--aqua), var(--sea-bright));
        background-size: 200% 100%;
        animation: jdwFlow 6s linear infinite;
    }

    @keyframes jdwFlow {
        from { background-position: 0 0; }
        to   { background-position: -200% 0; }
    }

    /* ---------- TABEL ---------- */
    .jdw-table thead tr {
        background: #eef7fb;
    }

    .jdw-table thead th {
        background: transparent;
        color: var(--muted);
        letter-spacing: 0.5px;
        border-bottom: 1px solid #e3eef4;
        white-space: nowrap;
    }

    .jdw-table tbody tr {
        transition: box-shadow 0.2s ease;
        animation: jdwRowIn 0.5s ease-out backwards;
    }

    .jdw-table tbody tr:nth-child(1) { animation-delay: 0.35s; }
    .jdw-table tbody tr:nth-child(2) { animation-delay: 0.42s; }
    .jdw-table tbody tr:nth-child(3) { animation-delay: 0.49s; }
    .jdw-table tbody tr:nth-child(4) { animation-delay: 0.56s; }
    .jdw-table tbody tr:nth-child(5) { animation-delay: 0.63s; }
    .jdw-table tbody tr:nth-child(6) { animation-delay: 0.70s; }
    .jdw-table tbody tr:nth-child(n+7) { animation-delay: 0.77s; }

    @keyframes jdwRowIn {
        from { opacity: 0; transform: translateX(-14px); }
        to   { opacity: 1; transform: translateX(0); }
    }

    .jdw-table.table-hover > tbody > tr:not(.jdw-empty-row):hover > * {
        background-color: rgba(18, 150, 196, 0.06);
        --bs-table-accent-bg: rgba(18, 150, 196, 0.06);
    }

    .jdw-table.table-hover > tbody > tr:not(.jdw-empty-row):hover {
        box-shadow: inset 4px 0 0 var(--sea-bright);
    }

    .jdw-table td {
        border-color: #e8f1f6;
    }

    /* Titik berdenyut pada status Aktif */
    .jdw-badge-active::before {
        content: '';
        display: inline-block;
        width: 7px;
        height: 7px;
        margin-right: 6px;
        border-radius: 50%;
        background: #16a34a;
        animation: jdwPulse 1.8s ease-in-out infinite;
    }

    @keyframes jdwPulse {
        0%, 100% { opacity: 1; transform: scale(1); }
        50%      { opacity: 0.35; transform: scale(1.4); }
    }

    .jdw-btn-delete {
        transition: transform 0.25s ease, background-color 0.25s ease, color 0.25s ease;
    }

    .jdw-btn-delete:hover {
        transform: scale(1.1) rotate(-6deg);
    }

    /* ---------- KONDISI KOSONG ---------- */
    .jdw-empty-row td {
        background-image: radial-gradient(rgba(18, 150, 196, 0.08) 1px, transparent 1px);
        background-size: 18px 18px;
    }

    .jdw-empty-circle {
        position: relative;
        width: 64px;
        height: 64px;
        background: #e1f2f8;
        color: var(--sea-mid);
        animation: jdwFloat 4s ease-in-out infinite;
    }

    /* Riak air di sekitar ikon kosong */
    .jdw-empty-circle::before,
    .jdw-empty-circle::after {
        content: '';
        position: absolute;
        inset: 0;
        border-radius: 50%;
        border: 2px solid rgba(18, 150, 196, 0.35);
        animation: jdwRipple 3s ease-out infinite;
    }

    .jdw-empty-circle::after {
        animation-delay: 1.5s;
    }

    @keyframes jdwRipple {
        from { transform: scale(1); opacity: 0.8; }
        to   { transform: scale(1.9); opacity: 0; }
    }

    /* ---------- TOTAL PAKAN ---------- */
    .jdw-total {
        background: #eef7fb !important;
        border-color: #e3eef4 !important;
    }

    .jdw-total strong {
        color: var(--sea-mid) !important;
        font-weight: 800;
        font-variant-numeric: tabular-nums;
    }

    /* ---------- POPUP ---------- */
    .jdw-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(5, 46, 74, 0.45);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.3s ease-in-out, visibility 0.3s ease-in-out;
        z-index: 1050;
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
        padding: 1rem;
    }

    .jdw-overlay.show {
        opacity: 1;
        visibility: visible;
    }

    .jdw-popup {
        position: relative;
        width: 100%;
        max-width: 480px;
        max-height: 92vh;
        overflow-y: auto;
        background: #ffffff;
        box-shadow: 0 30px 60px -20px rgba(5, 46, 74, 0.5);
        transform: translateY(-30px) scale(0.94);
        transition: transform 0.45s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    .jdw-overlay.show .jdw-popup {
        transform: translateY(0) scale(1);
    }

    .jdw-pop-header {
        position: relative;
        background: linear-gradient(180deg, #f1f9fc 0%, #ffffff 100%);
        border-bottom: 1px solid #e3eef4;
    }

    .jdw-pop-header::before {
        height: 4px;
    }

    .jdw-overlay .btn-close {
        transition: transform 0.3s ease;
    }

    .jdw-overlay .btn-close:hover {
        transform: rotate(90deg);
    }

    .jdw-overlay .form-label {
        color: #36566b !important;
    }

    .jdw-overlay .form-control,
    .jdw-overlay .form-select {
        border: 1.5px solid var(--line);
        background-color: #f7fbfd;
        color: var(--ink);
        font-family: inherit;
        transition: border-color 0.25s ease, box-shadow 0.25s ease, background-color 0.25s ease, transform 0.25s ease;
    }

    .jdw-overlay .form-control:hover,
    .jdw-overlay .form-select:hover {
        border-color: #a9cddd;
    }

    .jdw-overlay .form-control:focus,
    .jdw-overlay .form-select:focus {
        border-color: var(--sea-bright);
        background-color: #ffffff;
        box-shadow: 0 0 0 4px rgba(18, 150, 196, 0.15);
        transform: translateY(-1px);
    }

    .jdw-overlay .form-control::placeholder {
        color: #9bb2c0;
    }

    .jdw-pop-footer {
        background: #eef7fb;
        border-color: #e3eef4 !important;
    }

    /* ---------- HP ---------- */
    @media (max-width: 576px) {
        .jdw-header h4 {
            font-size: 1.15rem;
        }

        .jdw-actions .btn {
            width: 100%;
            justify-content: center;
        }

        .jdw-pop-footer {
            flex-direction: column-reverse;
        }

        .jdw-pop-footer .btn {
            width: 100%;
        }
    }

    /* Hormati pengguna yang mematikan animasi */
    @media (prefers-reduced-motion: reduce) {
        .jdw *,
        .jdw *::before,
        .jdw *::after,
        .jdw-overlay,
        .jdw-overlay *,
        .jdw-overlay *::before,
        .jdw-overlay *::after {
            animation-duration: 0.01ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: 0.01ms !important;
        }
    }
</style>

@auth
<script>
    function bukaPopupTambahJadwal() {
        document.getElementById('popupTambahJadwal').classList.add('show');
    }

    function tutupPopupTambahJadwal() {
        document.getElementById('popupTambahJadwal').classList.remove('show');
    }

    // Tutup jika klik di luar kotak popup
    document.getElementById('popupTambahJadwal').addEventListener('click', function(event) {
        if (event.target === this) {
            tutupPopupTambahJadwal();
        }
    });

    // Tutup dengan tombol ESC pada keyboard
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            tutupPopupTambahJadwal();
        }
    });
</script>
@endauth
@endsection
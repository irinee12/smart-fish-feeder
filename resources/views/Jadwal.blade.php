@extends('layouts.app')

@section('title', 'Jadwal Pakan - Smart Fish Feeder')

@section('content')
<div class="container-fluid px-0" style="background-color: #f8f9fa; min-height: 100vh;">

    {{-- =====================================================
        HEADER FULL WARNA (HANYA JUDUL & DESKRIPSI)
    ===================================================== --}}
    <div class="py-3 px-4 mb-4 text-white shadow-sm d-flex align-items-center" style="background: linear-gradient(135deg, #2563eb, #1d4ed8); width: 100%;">
        <div class="d-flex align-items-center gap-3">
            <div class="p-2 bg-white bg-opacity-25 rounded-3">
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
        {{-- TOMBOL TAMBAH JADWAL (TERPISAH DI LUAR HEADER) --}}
        {{-- ============================================= --}}
        <div class="d-flex justify-content-end mb-3">
            <button type="button" class="btn btn-primary d-flex align-items-center gap-2 shadow-sm px-3 py-2 rounded-3" onclick="bukaPopupTambahJadwal()">
                <i class="bi bi-plus-lg fs-5"></i>
                <span class="fw-semibold">Tambah Jadwal</span>
            </button>
        </div>

        {{-- ============================= --}}
        {{-- KARTU DAFTAR JADWAL --}}
        {{-- ============================= --}}
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-white border-bottom p-4">
                <h5 class="fw-bold mb-1 text-dark">Daftar Jadwal Pakan</h5>
                <p class="text-muted small mb-0">Jadwal pemberian pakan yang telah ditentukan pada sistem.</p>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr class="text-muted small text-uppercase bg-light">
                            <th class="py-3 px-4 fw-semibold">Waktu</th>
                            <th class="py-3 px-4 fw-semibold">Sesi</th>
                            <th class="py-3 px-4 fw-semibold">Porsi</th>
                            <th class="py-3 px-4 fw-semibold">Status</th>
                            <th class="py-3 px-4 fw-semibold text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- KONDISI JIKA DATABASE BELUM ADA / KOSONG --}}
                        <tr>
                            <td colspan="5" class="py-5 text-center text-muted">
                                <div class="d-flex flex-column align-items-center justify-content-center py-4">
                                    <div class="bg-light text-secondary rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 64px; height: 64px;">
                                        <i class="bi bi-clock-history fs-2"></i>
                                    </div>
                                    <h6 class="fw-bold text-dark mb-1">Belum ada jadwal pakan</h6>
                                    <p class="text-muted small mb-0">Data jadwal akan tampil otomatis setelah ditambahkan atau database terhubung.</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- TOTAL PAKAN --}}
            <div class="card-footer bg-light border-top p-3 px-4 d-flex justify-content-end align-items-center">
                <span class="text-muted me-2">Total pakan hari ini:</span>
                <strong class="text-dark fs-6">0 g (0,00 kg)</strong>
            </div>
        </div>

    </div>
</div>

{{-- ================================================= --}}
{{-- POPUP / MODAL TAMBAH JADWAL --}}
{{-- ================================================= --}}
<div class="popup-overlay" id="popupTambahJadwal">
    <div class="popup-card bg-white rounded-4 shadow-lg overflow-hidden" style="width: 100%; max-width: 480px;">
        
        {{-- HEADER POPUP --}}
        <div class="p-4 border-bottom d-flex justify-content-between align-items-center bg-white">
            <div>
                <h5 class="fw-bold mb-1 text-dark">Tambah Jadwal Pakan</h5>
                <p class="text-muted small mb-0">Atur waktu, sesi, dan jumlah pakan harian.</p>
            </div>
            <button type="button" class="btn-close shadow-none" onclick="tutupPopupTambahJadwal()" aria-label="Tutup"></button>
        </div>

        {{-- FORM POPUP --}}
        <div class="p-4 d-flex flex-column gap-3">
            <div class="mb-1">
                <label for="waktu" class="form-label fw-semibold small text-secondary">Waktu</label>
                <input type="time" id="waktu" class="form-control rounded-3 py-2">
            </div>

            <div class="mb-1">
                <label for="sesi" class="form-label fw-semibold small text-secondary">Sesi</label>
                <select id="sesi" class="form-select rounded-3 py-2">
                    <option value="Pagi">Pagi</option>
                    <option value="Siang">Siang</option>
                    <option value="Sore">Sore</option>
                </select>
            </div>

            <div class="mb-1">
                <label for="porsi" class="form-label fw-semibold small text-secondary">Porsi (gram)</label>
                <input type="number" id="porsi" class="form-control rounded-3 py-2" placeholder="Contoh: 500" min="50" max="2000">
            </div>
        </div>

        {{-- TOMBOL AKSI POPUP --}}
        <div class="p-3 px-4 bg-light border-top d-flex justify-content-end gap-2">
            <button type="button" class="btn btn-outline-secondary px-4 rounded-3" onclick="tutupPopupTambahJadwal()">Batal</button>
            <button type="button" class="btn btn-primary px-4 rounded-3" onclick="simpanJadwal()">
                <i class="bi bi-plus-lg me-1"></i> Simpan Jadwal
            </button>
        </div>

    </div>
</div>

{{-- ================================================= --}}
{{-- JAVASCRIPT POPUP & INTERAKSI --}}
{{-- ================================================= --}}
<style>
    /* Styling khusus transisi popup */
    .popup-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease-in-out;
        z-index: 1050;
        backdrop-filter: blur(4px);
    }
    .popup-overlay.show {
        opacity: 1;
        visibility: visible;
    }
    .popup-card {
        transform: translateY(-20px);
        transition: transform 0.3s ease-out;
    }
    .popup-overlay.show .popup-card {
        transform: translateY(0);
    }
</style>

<script>
    function bukaPopupTambahJadwal() {
        document.getElementById('popupTambahJadwal').classList.add('show');
    }

    function tutupPopupTambahJadwal() {
        document.getElementById('popupTambahJadwal').classList.remove('show');
    }

    function simpanJadwal() {
        // Logika simpan sementara
        tutupPopupTambahJadwal();
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
@endsection
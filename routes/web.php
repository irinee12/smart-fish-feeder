<?php

use App\Http\Controllers\RiwayatController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\Penjaga2Controller;

/*
|--------------------------------------------------------------------------
| Autentikasi (Login & Logout)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Aksi menambah dan menghapus data jadwal
    Route::post('/jadwal', [JadwalController::class, 'store'])->name('jadwal.store');
    Route::delete('/jadwal/{id}', [JadwalController::class, 'destroy'])->name('jadwal.destroy');
});

/*
|--------------------------------------------------------------------------
| Halaman Utama & Fitur Deteksi Mata Ikan (Langsung Akses)
|--------------------------------------------------------------------------
*/
// Halaman utama langsung ke Deteksi Mata Ikan
Route::get('/', [Penjaga2Controller::class, 'deteksiMata'])->name('deteksi.index');
Route::post('/deteksi-mata', [Penjaga2Controller::class, 'processDeteksi'])->name('deteksi.process');

// Halaman Sistem Lainnya (Publik / Read-Only)
Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal');
Route::get('/stok', function () {
    return view('Stok');
})->name('stok');
Route::get('/prediksi', function () {
    return view('Prediksi');
})->name('prediksi');
Route::get('/riwayat', [RiwayatController::class, 'index'])->name('riwayat');
Route::get('/riwayat/export-pdf', [RiwayatController::class, 'exportPdf'])->name('riwayat.export.pdf');
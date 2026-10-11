<?php

use App\Http\Controllers\RiwayatController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JadwalController;

/*
|--------------------------------------------------------------------------
| Autentikasi (Login & Logout)
|--------------------------------------------------------------------------
*/
// Hanya bisa diakses oleh orang yang BELUM login
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Hanya bisa diakses oleh orang yang SUDAH login (Penjaga/Admin)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Aksi menambah dan menghapus data
    Route::post('/jadwal', [JadwalController::class, 'store'])->name('jadwal.store');
    Route::delete('/jadwal/{id}', [JadwalController::class, 'destroy'])->name('jadwal.destroy');
});


/*
|--------------------------------------------------------------------------
| Halaman Sistem (Publik / Read-Only)
|--------------------------------------------------------------------------
| Semua pengunjung bisa mengakses rute ini untuk melihat data (Tampilan Front-end).
| Tombol aksi disembunyikan dari view menggunakan @auth ... @endauth.
*/

// Dashboard
Route::get('/', function () {
    return view('Dashboard');
})->name('dashboard');

// Jadwal Pakan
Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal');

// Stok Pakan
Route::get('/stok', function () {
    return view('Stok');
})->name('stok');

// Prediksi Refill
Route::get('/prediksi', function () {
    return view('Prediksi');
})->name('prediksi');

// Riwayat Pemberian Pakan
Route::get('/riwayat', [RiwayatController::class, 'index'])->name('riwayat');

// Export Riwayat ke PDF
Route::get('/riwayat/export-pdf', [RiwayatController::class, 'exportPdf'])->name('riwayat.export.pdf');
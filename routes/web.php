<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\Penjaga2Controller;
use App\Http\Controllers\RiwayatController;
use App\Http\Controllers\PrediksiStokController;


// ======================================================
// ROUTE AWAL
// ======================================================

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route(auth()->user()->homeRoute())
        : redirect()->route('login');
});


// ======================================================
// LOGIN
// ======================================================

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login']);
});


// ======================================================
// ROUTE USER LOGIN
// ======================================================

Route::middleware('auth')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');


    // ==================================================
    // PENJAGA 1
    // ==================================================

    Route::middleware('role:penjaga1')->group(function () {

        Route::get('/dashboard', function () {
            return view('Dashboard');
        })->name('dashboard');


        // Jadwal
        Route::get('/jadwal', [JadwalController::class, 'index'])
            ->name('jadwal');

        Route::post('/jadwal', [JadwalController::class, 'store'])
            ->name('jadwal.store');

        Route::delete('/jadwal/{id}', [JadwalController::class, 'destroy'])
            ->name('jadwal.destroy');


        // Stok
        Route::get('/stok', function () {
            return view('Stok');
        })->name('stok');


        // Prediksi refill
        Route::get('/prediksi', [PrediksiStokController::class, 'index'])
            ->name('prediksi');


        // Riwayat
        Route::get('/riwayat', [RiwayatController::class, 'index'])
            ->name('riwayat');

        Route::get('/riwayat/export-pdf', [RiwayatController::class, 'exportPdf'])
            ->name('riwayat.export.pdf');

    });


    // ==================================================
    // PENJAGA 2
    // ==================================================

    Route::middleware('role:penjaga2')->group(function () {

        Route::get('/deteksi-mata', [Penjaga2Controller::class, 'deteksiMata'])
            ->name('deteksi.index');

        Route::post('/deteksi-mata', [Penjaga2Controller::class, 'processDeteksi'])
            ->name('deteksi.process');

    });

});
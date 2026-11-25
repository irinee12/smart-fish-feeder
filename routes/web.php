<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\Penjaga2Controller;
use App\Http\Controllers\RiwayatController;
use App\Http\Controllers\PrediksiStokController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RiwayatDeteksiController;

// ======================================================
// ROUTE AWAL
// ======================================================

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route(auth()->user()->homeRoute())
        : redirect()->route('login');
});

// ======================================================
// API JADWAL UNTUK ESP32
// ======================================================

Route::get('/api/esp32/jadwal', [JadwalController::class, 'apiEsp32'])
    ->name('api.esp32.jadwal');

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

        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        // Jadwal
        Route::get('/jadwal', [JadwalController::class, 'index'])
            ->name('jadwal');

        Route::post('/jadwal', [JadwalController::class, 'store'])
            ->name('jadwal.store');

        Route::delete('/jadwal/{id}', [JadwalController::class, 'destroy'])
            ->name('jadwal.destroy');

        // Stok
        Route::get('/stok', function () {

            $data = \Illuminate\Support\Facades\DB::table('monitoring_stoks')
                ->latest('id')
                ->first();

            $jarakSensor = $data && isset($data->jarak_sensor)
                ? (float) $data->jarak_sensor
                : null;

            $waktuData = $data->created_at
                ?? $data->updated_at
                ?? $data->waktu_pembacaan
                ?? null;

            $umur = $waktuData
                ? now()->timestamp - \Carbon\Carbon::parse($waktuData)->timestamp
                : null;

            $sensorTerhubung = $umur !== null && $umur >= -2 && $umur <= 30;

            return view('stok', [
                'jarakSensor' => $jarakSensor,
                'sensorTerhubung' => $sensorTerhubung,
                'sensorData' => $data
            ]);

        })->name('stok');

        // Prediksi
        Route::get('/prediksi', [PrediksiStokController::class, 'index'])
            ->name('prediksi.index');

        Route::post('/prediksi', [PrediksiStokController::class, 'update'])
            ->name('prediksi.update');

        // Riwayat
        Route::get('/riwayat', [RiwayatController::class, 'index'])
            ->name('riwayat');

        Route::get('/riwayat/export-pdf', [RiwayatController::class, 'exportPdf'])
            ->name('riwayat.export.pdf');

        Route::get('/riwayat/export-csv', [RiwayatController::class, 'exportCsv'])
            ->name('riwayat.export.csv');

    });


    // ==================================================
    // PENJAGA 2
    // ==================================================

    Route::middleware('role:penjaga2')->group(function () {

        Route::get('/deteksi-mata', [Penjaga2Controller::class, 'deteksiMata'])
            ->name('deteksi.index');

        Route::post('/deteksi-mata', [Penjaga2Controller::class, 'processDeteksi'])
            ->name('deteksi.process');

        // Riwayat deteksi mata ikan
        Route::post('/deteksi/simpan', [RiwayatDeteksiController::class, 'simpan'])
            ->name('deteksi-riwayat.simpan');

        Route::get('/deteksi-riwayat', [RiwayatDeteksiController::class, 'index'])
            ->name('deteksi-riwayat.index');

        Route::get('/deteksi-riwayat/pdf', [RiwayatDeteksiController::class, 'pdf'])
            ->name('deteksi-riwayat.pdf');

        Route::get('/deteksi-riwayat/excel', [RiwayatDeteksiController::class, 'excel'])
            ->name('deteksi-riwayat.excel');

    });

});
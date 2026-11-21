<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\Penjaga2Controller;
use App\Http\Controllers\RiwayatController;
use App\Http\Controllers\PrediksiStokController;
use App\Http\Controllers\DashboardController;

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

        // Route Dashboard memanggil DashboardController dan diberi nama 'dashboard'
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        // Jadwal
        Route::get('/jadwal', [JadwalController::class, 'index'])
            ->name('jadwal');

        Route::post('/jadwal', [JadwalController::class, 'store'])
            ->name('jadwal.store');

        Route::delete('/jadwal/{id}', [JadwalController::class, 'destroy'])
            ->name('jadwal.destroy');


Route::get('/stok', function () {

    $data = \Illuminate\Support\Facades\DB::table('sensor_data')
        ->latest('id')
        ->first();

    $jarakSensor = $data && isset($data->jarak)
        ? (float) $data->jarak
        : null;

    $waktuData = $data->created_at
        ?? $data->updated_at
        ?? $data->waktu
        ?? null;

    $sensorTerhubung = $data
        && $waktuData
        && now()->diffInSeconds(
            \Carbon\Carbon::parse($waktuData)
        ) <= 30;

    return view('Stok', [
        'jarakSensor' => $jarakSensor,
        'sensorTerhubung' => $sensorTerhubung,
        'sensorData' => $data
    ]);

})->name('stok');

 Route::get('/prediksi', [PrediksiStokController::class, 'index'])->name('prediksi.index');
Route::post('/prediksi', [PrediksiStokController::class, 'update'])->name('prediksi.update');
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
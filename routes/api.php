<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\IoTController;

// Route bawaan Laravel Sanctum
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Route khusus perangkat ESP32
Route::prefix('iot')
    ->middleware('auth:sanctum')
    ->group(function () {

        // Mengambil jadwal pemberian pakan
        Route::get('/jadwal', [IoTController::class, 'getJadwal']);

        // Mengirim data monitoring stok
        Route::post('/monitoring-stok', [IoTController::class, 'storeMonitoring']);

        // Mengirim riwayat pemberian pakan
        Route::post('/riwayat-pakan', [IoTController::class, 'storeRiwayat']);

        // Memperbarui status perangkat
        Route::post('/status', [IoTController::class, 'updateStatus']);
    });

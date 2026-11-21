<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SensorDataController;
use App\Http\Controllers\Api\IoTController;

// ==========================================
// SENSOR DATA ESP32
// ==========================================

// Menerima data sensor dan hasil Fuzzy Mamdani dari ESP32
Route::post('/sensor-data', [SensorDataController::class, 'store']);

// Mengambil data sensor terbaru untuk website
Route::get('/sensor-data/latest', [SensorDataController::class, 'latest']);


// ==========================================
// ROUTE SANCTUM
// ==========================================

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// ==========================================
// ROUTE KHUSUS PERANGKAT IoT (Tanpa Sanctum dulu agar ESP32 langsung tembus)
// ==========================================

Route::prefix('iot')->group(function () {

    // Mengambil jadwal pemberian pakan -> URL: http://172.14.9.61:8000/api/iot/jadwal
    Route::get('/jadwal', [IoTController::class, 'getJadwal']);

    // Mengirim data monitoring stok -> URL: http://172.14.9.61:8000/api/iot/monitoring-stok
    Route::post('/monitoring-stok', [IoTController::class, 'storeMonitoring']);

    // Mengirim riwayat pemberian pakan -> URL: http://172.14.9.61:8000/api/iot/riwayat-pakan
    Route::post('/riwayat-pakan', [IoTController::class, 'storeRiwayat']);

    // Memperbarui status perangkat -> URL: http://172.14.9.61:8000/api/iot/status
    Route::post('/status', [IoTController::class, 'updateStatus']);

});
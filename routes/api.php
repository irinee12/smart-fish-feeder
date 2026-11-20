
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SensorDataController;
use App\Http\Controllers\Api\IoTController;

/*
|--------------------------------------------------------------------------
| API Routes Smart Fish Feeder
|--------------------------------------------------------------------------
*/

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
// ROUTE KHUSUS PERANGKAT IoT
// ==========================================

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

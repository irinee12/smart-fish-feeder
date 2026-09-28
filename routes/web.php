<?php

use App\Http\Controllers\RiwayatController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('Dashboard');
})->name('dashboard');


/*
|--------------------------------------------------------------------------
| Jadwal Pakan
|--------------------------------------------------------------------------
*/

Route::get('/jadwal', function () {
    return view('Jadwal');
})->name('jadwal');


/*
|--------------------------------------------------------------------------
| Stok Pakan
|--------------------------------------------------------------------------
*/

Route::get('/stok', function () {
    return view('Stok');
})->name('stok');


/*
|--------------------------------------------------------------------------
| Prediksi Refill
|--------------------------------------------------------------------------
*/

Route::get('/prediksi', function () {
    return view('Prediksi');
})->name('prediksi');


/*
|--------------------------------------------------------------------------
| Riwayat Pemberian Pakan
|--------------------------------------------------------------------------
*/

Route::get('/riwayat', [RiwayatController::class, 'index'])
    ->name('riwayat');


/*
|--------------------------------------------------------------------------
| Export Riwayat ke PDF
|--------------------------------------------------------------------------
*/

Route::get('/riwayat/export-pdf', [RiwayatController::class, 'exportPdf'])
    ->name('riwayat.export.pdf');
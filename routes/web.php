<?php

use App\Http\Controllers\RiwayatController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Autentikasi (Login)
|--------------------------------------------------------------------------
*/
Route::get('/login', function () {
    return view('auth.login');
})->name('login');


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
    return view('Stok'); // Ganti jadi huruf kecil 'stok'
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

Route::view('/mataikan', 'mataikan')
    ->name('mataikan.index');

/*
|--------------------------------------------------------------------------
| Export Riwayat ke PDF
|--------------------------------------------------------------------------
*/
Route::get('/riwayat/export-pdf', [RiwayatController::class, 'exportPdf'])
    ->name('riwayat.export.pdf');

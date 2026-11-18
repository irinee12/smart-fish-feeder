<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SensorData; // Panggil model yang baru dibuat

class DashboardController extends Controller
{
    public function index()
    {
        // Mengambil 10 data terbaru diurutkan dari waktu paling akhir
        $data_sensor = SensorData::orderBy('waktu', 'desc')->limit(10)->get();
        
        // Mengirim data ke file view bernama 'dashboard'
        return view('dashboard', compact('data_sensor'));
    }
}
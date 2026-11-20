<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SensorDataController extends Controller
{
    // ESP32 mengirim data jarak ke database
    public function store(Request $request)
    {
        $validated = $request->validate([
            'jarak' => 'required|numeric|min:0|max:400',
        ]);

        $id = DB::table('sensor_data')->insertGetId([
            'jarak' => $validated['jarak'],
            'waktu' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data sensor berhasil disimpan',
            'id' => $id,
        ], 201);
    }

    // Mengambil data sensor terakhir sekaligus memeriksa koneksi
    public function latest()
    {
        $data = DB::table('sensor_data')
            ->latest('id')
            ->first();

        $sensorTerhubung = false;
        $umurData = null;

        if ($data && !empty($data->waktu)) {
            $waktuTerakhir = Carbon::parse($data->waktu);
            $umurData = now()->timestamp - $waktuTerakhir->timestamp;

            $sensorTerhubung = $umurData >= 0 && $umurData <= 30;
        }

        return response()->json([
            'success' => true,
            'sensor_terhubung' => $sensorTerhubung,
            'umur_data_detik' => $umurData,
            'data' => $data,
        ]);
    }
}
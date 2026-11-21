<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SensorDataController extends Controller
{
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
        ], 200);
    }

    // Mengambil data sensor terakhir dari tabel monitoring_stoks
    public function latest()
    {
        $data = DB::table('monitoring_stoks')
            ->latest('id')
            ->first();

        $sensorTerhubung = false;
        $umurData = null;

        if ($data && !empty($data->waktu_pembacaan)) {
            $waktuTerakhir = Carbon::parse($data->waktu_pembacaan);
            $umurData = now()->timestamp - $waktuTerakhir->timestamp;

            // Anggap terhubung jika ada data baru dalam 30 detik terakhir
            $sensorTerhubung = $umurData >= 0 && $umurData <= 30;
        }

        // Format ulang agar kompatibel dengan field tabel lama maupun baru
        $formattedData = $data ? [
            'id'              => $data->id,
            'jarak'           => $data->jarak_sensor,
            'jarak_sensor'    => $data->jarak_sensor,
            'stok_gram'       => $data->stok_gram,
            'stok_kg'         => round($data->stok_gram / 1000, 2),
            'persentase_stok' => $data->persentase_stok,
            'waktu'           => $data->waktu_pembacaan,
            'waktu_pembacaan' => $data->waktu_pembacaan,
        ] : null;

        return response()->json([
            'success'          => true,
            'sensor_terhubung' => $sensorTerhubung,
            'umur_data_detik'  => $umurData,
            'data'             => $formattedData,
        ]);
    }
}
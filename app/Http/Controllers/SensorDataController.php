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
            'jarak'        => 'required|numeric|min:0|max:400',
            'persen_isi'   => 'nullable|numeric',
            'status_fuzzy' => 'nullable|string',
        ]);

        $id = DB::table('sensor_data')->insertGetId([
            'jarak' => $validated['jarak'],
            'waktu' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data sensor berhasil disimpan',
            'id'      => $id,
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

        if ($data) {
            // Prioritaskan created_at (format UTC standar Laravel) agar kebal selisih timezone
            $waktuRef = $data->created_at ?? $data->waktu_pembacaan;

            if (!empty($waktuRef)) {
                $waktuTerakhir = Carbon::parse($waktuRef);
                $umurData = abs(now()->diffInSeconds($waktuTerakhir));

                // Toleransi 180 detik (3 menit) agar status tidak putus saat ESP32 
                // memblokir komunikasi HTTP selama proses menimbang & menuang pakan (ISI/SETTLE/ALIR)
                $sensorTerhubung = $umurData <= 180;
            }
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
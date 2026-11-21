<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PerangkatIot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class IoTController extends Controller
{
    /**
     * Mengambil perangkat dari token jika ada, atau fallback ke perangkat pertama
     * agar ESP32 tanpa token tetap bisa terhubung.
     */
    private function resolveDevice(Request $request): ?PerangkatIot
    {
        $device = $request->user();
        if ($device instanceof PerangkatIot) {
            return $device;
        }
        return PerangkatIot::first();
    }

    private function touchDevice(?PerangkatIot $device): void
    {
        if ($device) {
            $device->update([
                'status'    => 'online',
                'last_seen' => now(),
            ]);
        }
    }

    /**
     * 1. Mengambil jadwal pemberian pakan.
     * GET /api/iot/jadwal
     * Output disesuaikan dengan parseJadwal() di ESP32:
     * { "waktu_server": "YYYY-MM-DD HH:MM:SS", "jadwal": [ { "jam": "HH:MM", "target_g": 50 } ] }
     */
    public function getJadwal(Request $request)
    {
        $device = $this->resolveDevice($request);

        $rows = DB::table('jadwal_pakans')
            ->select('waktu', 'porsi')
            ->orderBy('waktu')
            ->get();

        // Format ulang agar sesuai key yang dibaca ESP32 ("jam" dan "target_g")
        $jadwalEsp = $rows->map(function ($row) {
            return [
                'jam'      => Carbon::parse($row->waktu)->format('H:i'),
                'target_g' => (int) $row->porsi,
            ];
        });

        $this->touchDevice($device);

        return response()->json([
            'success'      => true,
            'waktu_server' => now()->format('Y-m-d H:i:s'),
            'jadwal'       => $jadwalEsp,
        ], 200);
    }

    /**
     * 2. Menyimpan monitoring stok pakan.
     * POST /api/iot/monitoring-stok
     * Input dari ESP32: jarak, persen_isi, status_fuzzy
     */
    public function storeMonitoring(Request $request)
    {
        $device = $this->resolveDevice($request);

        $validator = Validator::make($request->all(), [
            'jarak'        => 'required|numeric|min:0|max:400',
            'persen_isi'   => 'nullable|numeric|between:0,100',
            'status_fuzzy' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ], 422);
        }

        // Hitung estimasi stok_gram dari persen_isi (misal kapasitas penuh wadah = 1000 gram)
        $kapasitasMaxGram = 1000; 
        $persen = (float) ($request->persen_isi ?? 0);
        $estimasiGram = ($persen / 100) * $kapasitasMaxGram;

        $id = DB::table('monitoring_stoks')->insertGetId([
            'perangkat_iot_id' => $device ? $device->id : 1,
            'jarak_sensor'     => $request->jarak,
            'stok_gram'        => $estimasiGram,
            'persentase_stok'  => $persen,
            'waktu_pembacaan'  => now(),
            'created_at'       => now(),
            'updated_at'       => now(),
        ]);

        $this->touchDevice($device);

        // Wajib kode 200 agar httpPostForm() di ESP32 menganggap sukses
        return response()->json([
            'success' => true,
            'id'      => $id,
        ], 200);
    }

    /**
     * 3. Menyimpan riwayat pemberian pakan.
     * POST /api/iot/riwayat-pakan
     * Input dari ESP32: jam, target_g, aktual_g, error
     */
    public function storeRiwayat(Request $request)
    {
        $device = $this->resolveDevice($request);

        // Ambil nilai dengan fallback jika ada data riwayat lama yang kosong
        $jamInput = $request->input('jam');
        if (empty($jamInput)) {
            $jamInput = now()->format('H:i');
        }

        $tanggalHariIni = now()->format('Y-m-d');
        $waktuPakan = strlen($jamInput) === 5 ? $jamInput . ':00' : $jamInput;
        $trxId = 'TRX-' . str_replace('-', '', $tanggalHariIni) . '-' . str_replace(':', '', $waktuPakan);

        $existing = DB::table('riwayat_pakans')->where('transaction_id', $trxId)->first();
        if ($existing) {
            $this->touchDevice($device);
            return response()->json(['success' => true, 'duplicate' => true], 200);
        }

        $jadwalMatch = DB::table('jadwal_pakans')
            ->where('waktu', 'like', substr($jamInput, 0, 5) . '%')
            ->first();

        DB::table('riwayat_pakans')->insert([
            'perangkat_iot_id' => $device ? $device->id : 1,
            'transaction_id'   => $trxId,
            'jadwal_pakan_id'  => $jadwalMatch ? $jadwalMatch->id : null,
            'tanggal'          => $tanggalHariIni,
            'waktu'            => $waktuPakan,
            'porsi_target'     => (float) $request->input('target_g', 0),
            'porsi_aktual'     => (float) $request->input('aktual_g', 0),
            'status'           => ((int) $request->input('error', 0) === 0) ? 'berhasil' : 'gagal',
            'created_at'       => now(),
            'updated_at'       => now(),
        ]);

        $this->touchDevice($device);

        return response()->json(['success' => true], 200);
    }

    /**
     * 4. Memperbarui status perangkat.
     * POST /api/iot/status
     */
    public function updateStatus(Request $request)
    {
        $device = $this->resolveDevice($request);
        $this->touchDevice($device);

        return response()->json([
            'success' => true,
        ], 200);
    }
}
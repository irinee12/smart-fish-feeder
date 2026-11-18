<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PerangkatIot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class IoTController extends Controller
{
    /**
     * Memastikan token memiliki hak akses dan
     * perangkat yang melakukan request valid.
     */
    private function checkDevice(Request $request, string $ability)
    {
        $device = $request->user();

        if (!$device instanceof PerangkatIot) {
            return response()->json([
                'success' => false,
                'message' => 'Token bukan milik perangkat IoT.'
            ], 403);
        }

        if (!$device->tokenCan($ability)) {
            return response()->json([
                'success' => false,
                'message' => 'Token tidak memiliki izin akses.'
            ], 403);
        }

        return $device;
    }

    /**
     * Memperbarui status perangkat menjadi online.
     */
    private function touchDevice(PerangkatIot $device): void
    {
        $device->update([
            'status' => 'online',
            'last_seen' => now(),
        ]);
    }

    /**
     * 1. Mengambil jadwal pemberian pakan.
     * GET /api/iot/jadwal
     */
    public function getJadwal(Request $request)
    {
        $device = $this->checkDevice($request, 'iot:read');

        if ($device instanceof \Illuminate\Http\JsonResponse) {
            return $device;
        }

        $jadwal = DB::table('jadwal_pakans')
            ->select('id', 'waktu', 'sesi', 'porsi')
            ->orderBy('waktu')
            ->get();

        $this->touchDevice($device);

        return response()->json([
            'success' => true,
            'message' => 'Jadwal pakan berhasil diambil.',
            'data' => $jadwal,
        ]);
    }

    /**
     * 2. Menyimpan monitoring stok pakan.
     * POST /api/iot/monitoring-stok
     */
    public function storeMonitoring(Request $request)
    {
        $device = $this->checkDevice($request, 'iot:write');

        if ($device instanceof \Illuminate\Http\JsonResponse) {
            return $device;
        }

        $validator = Validator::make($request->all(), [
            'jarak_sensor' => 'nullable|numeric|min:0',
            'stok_gram' => 'required|numeric|min:0',
            'persentase_stok' => 'nullable|numeric|between:0,100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Data monitoring tidak valid.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $id = DB::table('monitoring_stoks')->insertGetId([
            'perangkat_iot_id' => $device->id,
            'jarak_sensor' => $request->jarak_sensor,
            'stok_gram' => $request->stok_gram,
            'persentase_stok' => $request->persentase_stok,
            'waktu_pembacaan' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->touchDevice($device);

        return response()->json([
            'success' => true,
            'message' => 'Monitoring stok berhasil disimpan.',
            'data' => [
                'id' => $id,
                'stok_gram' => $request->stok_gram,
                'persentase_stok' => $request->persentase_stok,
            ],
        ], 201);
    }

    /**
     * 3. Menyimpan riwayat pemberian pakan.
     * POST /api/iot/riwayat-pakan
     */
    public function storeRiwayat(Request $request)
    {
        $device = $this->checkDevice($request, 'iot:write');

        if ($device instanceof \Illuminate\Http\JsonResponse) {
            return $device;
        }

        $validator = Validator::make($request->all(), [
            'transaction_id' => 'required|string|max:100',
            'jadwal_pakan_id' => 'nullable|integer|exists:jadwal_pakans,id',
            'tanggal' => 'required|date_format:Y-m-d',
            'waktu' => 'required|date_format:H:i:s',
            'porsi_target' => 'required|numeric|min:0',
            'porsi_aktual' => 'nullable|numeric|min:0',
            'status' => 'required|in:berhasil,gagal,menunggu',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Data riwayat pakan tidak valid.',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Mencegah riwayat ganda saat ESP32 mengirim ulang
        // data yang sebelumnya belum mendapat respons server.
        $existing = DB::table('riwayat_pakans')
            ->where('transaction_id', $request->transaction_id)
            ->first();

        if ($existing) {
            if ((int) $existing->perangkat_iot_id !== (int) $device->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Transaction ID sudah digunakan perangkat lain.',
                ], 409);
            }

            $this->touchDevice($device);

            return response()->json([
                'success' => true,
                'duplicate' => true,
                'message' => 'Riwayat sudah pernah tersimpan.',
                'data' => [
                    'id' => $existing->id,
                    'transaction_id' => $existing->transaction_id,
                ],
            ]);
        }

        $id = DB::table('riwayat_pakans')->insertGetId([
            'perangkat_iot_id' => $device->id,
            'transaction_id' => $request->transaction_id,
            'jadwal_pakan_id' => $request->jadwal_pakan_id,
            'tanggal' => $request->tanggal,
            'waktu' => $request->waktu,
            'porsi_target' => $request->porsi_target,
            'porsi_aktual' => $request->porsi_aktual,
            'status' => $request->status,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->touchDevice($device);

        return response()->json([
            'success' => true,
            'message' => 'Riwayat pemberian pakan berhasil disimpan.',
            'data' => [
                'id' => $id,
                'transaction_id' => $request->transaction_id,
            ],
        ], 201);
    }

    /**
     * 4. Memperbarui status perangkat.
     * POST /api/iot/status
     */
    public function updateStatus(Request $request)
    {
        $device = $this->checkDevice($request, 'iot:write');

        if ($device instanceof \Illuminate\Http\JsonResponse) {
            return $device;
        }

        $this->touchDevice($device);

        return response()->json([
            'success' => true,
            'message' => 'Status perangkat berhasil diperbarui.',
            'data' => [
                'device_code' => $device->device_code,
                'status' => $device->status,
                'last_seen' => $device->last_seen,
            ],
        ]);
    }
}

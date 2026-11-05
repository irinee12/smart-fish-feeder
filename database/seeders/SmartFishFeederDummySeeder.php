<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RiwayatPakan;
use App\Models\MonitoringStok;
use App\Models\PengaturanStok;
use App\Models\PerangkatIot;
use Carbon\Carbon;

class SmartFishFeederDummySeeder extends Seeder
{
    public function run(): void
    {
        // ==================================
        // 1. DATA KONSUMSI PAKAN 7 HARI
        // ==================================

        $dataHarian = [
            400,
            450,
            420,
            380,
            450,
            400,
            440,
        ];

        foreach ($dataHarian as $index => $totalHarian) {

            $tanggal = Carbon::today()->subDays(6 - $index);

            // Pembagian pakan sesi pagi dan sore
            $porsiPagi = $totalHarian / 2;
            $porsiSore = $totalHarian / 2;

            // Sesi pagi
            RiwayatPakan::updateOrCreate(
                [
                    'tanggal' => $tanggal->toDateString(),
                    'waktu' => '08:00:00',
                ],
                [
                    'jadwal_pakan_id' => null,
                    'porsi_target' => $porsiPagi,
                    'porsi_aktual' => $porsiPagi,
                    'status' => 'berhasil',
                ]
            );

            // Sesi sore
            RiwayatPakan::updateOrCreate(
                [
                    'tanggal' => $tanggal->toDateString(),
                    'waktu' => '16:00:00',
                ],
                [
                    'jadwal_pakan_id' => null,
                    'porsi_target' => $porsiSore,
                    'porsi_aktual' => $porsiSore,
                    'status' => 'berhasil',
                ]
            );
        }

        // ==================================
        // 2. DATA MONITORING STOK
        // ==================================

        MonitoringStok::updateOrCreate(
            ['id' => 1],
            [
                'jarak_sensor' => 8.50,
                'stok_gram' => 2000,
                'persentase_stok' => 20,
                'waktu_pembacaan' => now(),
            ]
        );

        // ==================================
        // 3. PENGATURAN STOK
        // ==================================

        PengaturanStok::updateOrCreate(
            ['id' => 1],
            [
                'kapasitas_maksimum' => 10000,
                'batas_minimum' => 500,
                'satuan' => 'gram',
                'is_active' => true,
            ]
        );

        // ==================================
        // 4. DATA PERANGKAT ESP32
        // ==================================

        PerangkatIot::updateOrCreate(
            ['device_code' => 'ESP32-MUJ-001'],
            [
                'nama_perangkat' => 'ESP32 Tambak 1',
                'status' => 'online',
                'last_seen' => now(),
            ]
        );

    }
}
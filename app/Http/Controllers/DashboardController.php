<?php
namespace App\Http\Controllers;

use App\Models\SensorData;
use App\Models\JadwalPakan;
use App\Models\RiwayatPakan;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Data sensor terbaru
        $data_sensor = SensorData::orderBy('waktu', 'desc')
            ->limit(10)
            ->get();

        // 2. Ambil semua jadwal
        $semuaJadwal = JadwalPakan::orderBy('waktu', 'asc')->get();

        // 3. Ambil riwayat pakan hari ini
        $riwayatHariIni = RiwayatPakan::whereDate(
            'tanggal',
            Carbon::today()->toDateString()
        )
        ->orderBy('id', 'desc')
        ->get()
        ->unique('jadwal_pakan_id')
        ->keyBy('jadwal_pakan_id');

        // 4. Gabungkan jadwal dan riwayat
        $jadwal = $semuaJadwal->map(function ($item) use ($riwayatHariIni) {
            $riwayat = $riwayatHariIni->get($item->id);

            $item->target = (float) $item->porsi;

            $item->aktual = $riwayat && $riwayat->porsi_aktual !== null
                ? (float) $riwayat->porsi_aktual
                : null;

            $item->status_pakan = $riwayat->status ?? 'belum';

            return $item;
        });

        // 5. Jadwal berikutnya
        $jamSekarang = Carbon::now()->format('H:i:s');

        $next = $jadwal->first(function ($item) use ($jamSekarang) {
            return $item->waktu > $jamSekarang;
        });

        // 6. Total target dan aktual
        $targetHariIni = $jadwal->sum('target');

        $aktualHariIni = $jadwal->sum(function ($item) {
            return $item->aktual ?? 0;
        });

        $selisihHariIni = $targetHariIni - $aktualHariIni;

        $selisihPct = $targetHariIni > 0
            ? ($selisihHariIni / $targetHariIni) * 100
            : 0;

        // 7. Status pakan
        if ($targetHariIni <= 0) {
            $stHariIni = 'Belum ada jadwal';
            $stHariIniColor = 'secondary';
        } elseif ($aktualHariIni <= 0) {
            $stHariIni = 'Belum diberi pakan';
            $stHariIniColor = 'warning';
        } elseif ($aktualHariIni >= $targetHariIni) {
            $stHariIni = 'Target tercapai';
            $stHariIniColor = 'success';
        } else {
            $stHariIni = 'Pakan belum mencukupi';
            $stHariIniColor = 'warning';
        }

        return view('dashboard', compact(
            'data_sensor',
            'jadwal',
            'next',
            'targetHariIni',
            'aktualHariIni',
            'selisihHariIni',
            'selisihPct',
            'stHariIni',
            'stHariIniColor'
        ));
    }
}

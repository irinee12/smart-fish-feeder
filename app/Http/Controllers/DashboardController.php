<?php
namespace App\Http\Controllers;

use App\Models\SensorData;
use App\Models\JadwalPakan;
use App\Models\RiwayatPakan;
use App\Services\PrediksiService;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Data sensor terbaru
        // Sumber data: monitoring_stoks (tabel yang diisi ESP32 lewat /api/iot/monitoring-stok)
        $data_sensor = \Illuminate\Support\Facades\DB::table('monitoring_stoks')
            ->orderByDesc('id')
            ->limit(10)
            ->get()
            ->map(fn ($r) => (object) [
                'jarak' => $r->jarak_sensor,
                'waktu' => $r->waktu_pembacaan,
            ]);

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

        // 3b. Semua sesi pakan hari ini (termasuk tes manual) untuk kartu "Pakan Hari Ini"
        $riwayatSemuaHariIni = RiwayatPakan::whereDate('tanggal', Carbon::today()->toDateString())->get();

        // Pakan yang benar-benar keluar = yang ditimbang - sisa yang tertahan di wadah load cell
        $keluarHariIni = (float) $riwayatSemuaHariIni->sum(fn ($r) => $r->gram_keluar);

        // Tertahan = sisa 0-10 g di wadah load cell, dicatat dan ikut dihitung di stok gudang
        $tertahanHariIni = (float) $riwayatSemuaHariIni->sum(fn ($r) => (float) ($r->sisa_g ?? 0));

        // 4. Gabungkan jadwal dan riwayat
        $jadwal = $semuaJadwal->map(function ($item) use ($riwayatHariIni) {
            $riwayat = $riwayatHariIni->get($item->id);

            // Target = yang dipakai alat saat memberi pakan (kalau sudah ada riwayatnya), kalau belum = target jadwal.
            $item->target = $riwayat && (float) $riwayat->porsi_target > 0
                ? (float) $riwayat->porsi_target
                : (float) $item->porsi;

            // Aktual = pakan yang benar-benar keluar (ditimbang - tertahan), sama dengan Riwayat & Prediksi.
            $item->aktual = $riwayat
                ? round($riwayat->gram_keluar, 1)
                : null;

            $item->status_pakan = $riwayat->status ?? 'belum';

            return $item;
        });

        // 5. Jadwal berikutnya
        $jamSekarang = Carbon::now()->format('H:i:s');

        $next = $jadwal->first(function ($item) use ($jamSekarang) {
            return $item->waktu > $jamSekarang;
        });

        // Target = jadwal terdekat yang akan keluar. Kalau jadwal hari ini sudah lewat semua,
        // pakai jadwal pertama besok.
        $jadwalTerdekat   = $next ?? $jadwal->first();
        $targetBerikutnya = $jadwalTerdekat ? (float) $jadwalTerdekat->porsi : null;
        $jamBerikutnya    = $jadwalTerdekat ? substr($jadwalTerdekat->waktu, 0, 5) : null;
        $targetBesok      = $next === null && $jadwalTerdekat !== null;

        // 6. Total target dan aktual
        $targetHariIni = $jadwal->sum('target');

        $aktualHariIni = $keluarHariIni;

        $selisihHariIni = $targetHariIni - $aktualHariIni;

        $selisihPct = $targetHariIni > 0
            ? ($selisihHariIni / $targetHariIni) * 100
            : 0;

        // 7. Status pakan berdasarkan jumlah sesi jadwal yang sudah selesai
        $jumlahSesi  = $jadwal->count();
        $sesiSelesai = $jadwal->filter(fn ($j) => $j->status_pakan === 'berhasil')->count();
        $adaGagal    = $riwayatSemuaHariIni->where('status', 'gagal')->isNotEmpty();

        if ($jumlahSesi === 0) {
            $stHariIni = 'Belum ada jadwal';
            $stHariIniColor = 'secondary';
        } elseif ($adaGagal) {
            $stHariIni = 'Ada sesi pakan gagal';
            $stHariIniColor = 'danger';
        } elseif ($sesiSelesai === 0) {
            $stHariIni = 'Belum diberi pakan';
            $stHariIniColor = 'warning';
        } elseif ($sesiSelesai >= $jumlahSesi) {
            $stHariIni = 'Semua sesi selesai';
            $stHariIniColor = 'success';
        } else {
            $stHariIni = $sesiSelesai . ' dari ' . $jumlahSesi . ' sesi selesai';
            $stHariIniColor = 'info';
        }

        // 8. Prediksi stok gudang (sumber sama dengan menu Prediksi Refill)
        $prediksi    = PrediksiService::hitung();
        $sisaHari    = $prediksi['hariTahan'] !== null ? (int) floor($prediksi['hariTahan']) : null;
        $gudangOk    = $prediksi['perluBeliKg'] !== null && $prediksi['perluBeliKg'] <= 0;
        $perluBeliKg = $prediksi['perluBeliKg'];
        $sisaStokKg  = $prediksi['sisaKg'];
        $nSma        = $prediksi['n'];

        // 9. Grafik 7 hari terakhir: target vs pakan yang benar-benar keluar (data asli)
        $targetPerHari = (float) $semuaJadwal->sum('porsi');
        $mulaiGrafik   = Carbon::today()->subDays(6);

        $keluarPerHari = RiwayatPakan::asli()
            ->whereDate('tanggal', '>=', $mulaiGrafik->toDateString())
            ->selectRaw('DATE(tanggal) as hari, SUM(' . RiwayatPakan::sqlGramKeluar() . ') as total')
            ->groupBy(\Illuminate\Support\Facades\DB::raw('DATE(tanggal)'))
            ->pluck('total', 'hari');

        $gLabel = $gTarget = $gAktual = [];
        for ($d = $mulaiGrafik->copy(); $d->lte(Carbon::today()); $d->addDay()) {
            $gLabel[]  = $d->format('d M');
            $gTarget[] = round($targetPerHari, 1);
            $gAktual[] = round((float) ($keluarPerHari[$d->format('Y-m-d')] ?? 0), 1);
        }
        $hasChart = array_sum($gAktual) > 0;

        return view('dashboard', compact(
            'sisaHari', 'gudangOk', 'perluBeliKg', 'sisaStokKg', 'nSma',
            'gLabel', 'gTarget', 'gAktual', 'hasChart',
            'data_sensor',
            'jadwal',
            'next',
            'targetHariIni',
            'aktualHariIni',
            'selisihHariIni',
            'selisihPct',
            'keluarHariIni',
            'tertahanHariIni',
            'targetBerikutnya',
            'jamBerikutnya',
            'targetBesok',
            'stHariIni',
            'stHariIniColor'
        ));
    }
}

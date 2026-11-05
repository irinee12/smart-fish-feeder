<?php

namespace App\Http\Controllers;

use App\Models\RiwayatPakan;
use App\Models\MonitoringStok;
use App\Models\PengaturanStok;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PrediksiStokController extends Controller
{
    /**
     * Menampilkan halaman prediksi refill pakan.
     */
    public function index()
    {
        // ==================================================
        // 1. AMBIL DATA KONSUMSI PAKAN 7 HARI TERAKHIR
        // ==================================================

        $tanggalMulai = Carbon::today()->subDays(6);
        $tanggalAkhir = Carbon::today();

        $riwayatKonsumsi = RiwayatPakan::query()
            ->whereDate('tanggal', '>=', $tanggalMulai)
            ->whereDate('tanggal', '<=', $tanggalAkhir)
            ->where('status', 'berhasil')
            ->whereNotNull('porsi_aktual')
            ->select(
                DB::raw('DATE(tanggal) as tanggal'),
                DB::raw('SUM(porsi_aktual) as total_konsumsi')
            )
            ->groupBy(DB::raw('DATE(tanggal)'))
            ->orderBy('tanggal')
            ->get()
            ->keyBy(function ($item) {
                return Carbon::parse($item->tanggal)->format('Y-m-d');
            });


        // ==================================================
        // 2. SIAPKAN DATA 7 HARI
        // ==================================================

        $dataKonsumsi = [];
        $totalKonsumsi = 0;
        $jumlahHariTercatat = 0;

        for ($i = 0; $i < 7; $i++) {

            $tanggal = Carbon::today()->subDays(6 - $i);
            $tanggalKey = $tanggal->format('Y-m-d');

            $konsumsi = 0;
            $adaData = false;

            if ($riwayatKonsumsi->has($tanggalKey)) {

                $konsumsi = (float) $riwayatKonsumsi
                    ->get($tanggalKey)
                    ->total_konsumsi;

                $adaData = true;
                $jumlahHariTercatat++;
            }

            $totalKonsumsi += $konsumsi;

            $dataKonsumsi[] = [
                'tanggal' => $tanggal->format('d-m-Y'),
                'konsumsi' => $konsumsi,
                'ada_data' => $adaData,
            ];
        }


        // ==================================================
        // 3. HITUNG SMA (SIMPLE MOVING AVERAGE) 7 HARI
        // ==================================================

        $dataCukup = $jumlahHariTercatat === 7;

        if ($dataCukup) {
            $sma = $totalKonsumsi / 7;
        } else {
            $sma = null;
        }


        // ==================================================
        // 4. AMBIL DATA MONITORING STOK TERBARU
        // ==================================================

        $monitoring = MonitoringStok::query()
            ->orderBy('waktu_pembacaan', 'desc')
            ->first();


        // ==================================================
        // 5. AMBIL PENGATURAN STOK AKTIF
        // ==================================================

        $pengaturan = PengaturanStok::query()
            ->latest()
            ->first();


        // ==================================================
        // 6. AMBIL STOK SAAT INI DAN BATAS MINIMUM
        // ==================================================

        $stokSaatIni = null;
        $batasMinimum = null;

        if ($monitoring) {
            $stokSaatIni = is_numeric($monitoring->stok_gram)
                ? (float) $monitoring->stok_gram
                : null;
        }

        if ($pengaturan) {
            $batasMinimum = is_numeric($pengaturan->batas_minimum)
                ? (float) $pengaturan->batas_minimum
                : null;
        }


        // ==================================================
        // 7. HITUNG ESTIMASI HARI MENUJU BATAS MINIMUM
        // ==================================================

        $prediksiHari = null;
        $estimasiTanggal = null;

        $statusPrediksi = 'Belum Tersedia';
        $pesanPrediksi = 'Data belum cukup untuk membuat prediksi.';
        $rekomendasiRefill = 'Belum tersedia';


        if (
            $dataCukup &&
            $sma !== null &&
            $sma > 0 &&
            $stokSaatIni !== null &&
            $batasMinimum !== null
        ) {

            // Jika stok sudah mencapai atau di bawah batas minimum
            if ($stokSaatIni <= $batasMinimum) {

                $prediksiHari = 0;
                $estimasiTanggal = Carbon::today()->format('d-m-Y');

                $statusPrediksi = 'Perlu Refill';
                $pesanPrediksi = 'Stok pakan sudah mencapai atau berada di bawah batas minimum.';
                $rekomendasiRefill = 'Refill Sekarang';

            } else {

                // Sisa stok yang masih bisa digunakan
                $stokTersedia = $stokSaatIni - $batasMinimum;

                // Estimasi hari menuju batas minimum
                $prediksiHari = $stokTersedia / $sma;

                // Estimasi tanggal refill
                $estimasiTanggal = Carbon::today()
                    ->addDays((int) ceil($prediksiHari))
                    ->format('d-m-Y');


                // Tentukan status prediksi
                if ($prediksiHari <= 1) {

                    $statusPrediksi = 'Segera Refill';
                    $pesanPrediksi = 'Stok diperkirakan mencapai batas minimum dalam waktu kurang dari atau sama dengan 1 hari.';
                    $rekomendasiRefill = 'Refill Besok';

                } elseif ($prediksiHari <= 3) {

                    $statusPrediksi = 'Persiapan Refill';
                    $pesanPrediksi = 'Stok diperkirakan mencapai batas minimum dalam beberapa hari ke depan.';
                    $rekomendasiRefill = 'Siapkan Pakan';

                } else {

                    $statusPrediksi = 'Stok Aman';
                    $pesanPrediksi = 'Stok pakan masih mencukupi berdasarkan rata-rata konsumsi 7 hari terakhir.';
                    $rekomendasiRefill = 'Belum Perlu Refill';

                }
            }

        } elseif (!$dataCukup) {

            $statusPrediksi = 'Data Belum Cukup';
            $pesanPrediksi = 'Prediksi membutuhkan data konsumsi pakan selama 7 hari berturut-turut.';
            $rekomendasiRefill = 'Menunggu Data';

        } elseif ($sma === null || $sma <= 0) {

            $statusPrediksi = 'Konsumsi Belum Tersedia';
            $pesanPrediksi = 'Rata-rata konsumsi pakan belum dapat dihitung atau bernilai nol.';
            $rekomendasiRefill = 'Menunggu Data';

        } elseif ($stokSaatIni === null) {

            $statusPrediksi = 'Stok Belum Tersedia';
            $pesanPrediksi = 'Data monitoring stok pakan belum tersedia.';
            $rekomendasiRefill = 'Periksa Sensor Stok';

        } elseif ($batasMinimum === null) {

            $statusPrediksi = 'Batas Minimum Belum Diatur';
            $pesanPrediksi = 'Silakan atur batas minimum stok pakan terlebih dahulu.';
            $rekomendasiRefill = 'Atur Batas Minimum';

        }


        // ==================================================
        // 8. SIAPKAN DATA GRAFIK
        // ==================================================

        $grafikLabels = [];
        $grafikStok = [];

        if ($stokSaatIni !== null) {

            $stokProyeksi = $stokSaatIni;

            // Hari ke-0 adalah stok saat ini
            $grafikLabels[] = 'Hari ini';
            $grafikStok[] = round($stokProyeksi, 2);

            // Proyeksi stok untuk 7 hari ke depan
            for ($i = 1; $i <= 7; $i++) {

                if ($sma !== null && $sma > 0) {
                    $stokProyeksi -= $sma;
                }

                // Stok proyeksi tidak boleh negatif
                $stokProyeksi = max(0, $stokProyeksi);

                $grafikLabels[] = 'Hari ke-' . $i;
                $grafikStok[] = round($stokProyeksi, 2);
            }
        }


        // ==================================================
        // 9. KIRIM SEMUA DATA KE VIEW
        // ==================================================

        return view('prediksi', compact(
            'dataKonsumsi',
            'totalKonsumsi',
            'jumlahHariTercatat',
            'dataCukup',
            'sma',
            'monitoring',
            'pengaturan',
            'stokSaatIni',
            'batasMinimum',
            'prediksiHari',
            'estimasiTanggal',
            'statusPrediksi',
            'pesanPrediksi',
            'rekomendasiRefill',
            'grafikLabels',
            'grafikStok'
        ));
    }
}
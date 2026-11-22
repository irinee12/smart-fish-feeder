<?php

namespace App\Services;

use App\Models\RiwayatPakan;
use App\Models\StokGudang;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Satu-satunya tempat perhitungan stok gudang & prediksi refill.
 * Dipakai oleh Dashboard, Prediksi Refill, dan Jadwal supaya angkanya selalu sama.
 *
 * Logika (4 langkah):
 *  1. Stok gudang   = total pakan dibeli - total pakan keluar
 *  2. SMA n hari    = rata-rata pakan keluar per hari dari n hari terakhir
 *  3. Stok bertahan = stok gudang / SMA
 *  4. Perlu beli    = (SMA x 30 hari) - stok gudang
 */
class PrediksiService
{
    public const HARI_KEBUTUHAN = 30;

    /** Pilihan SMA (3 atau 7) disimpan di session supaya Dashboard & Prediksi memakai pilihan yang sama. */
    public static function pilihanSma(): int
    {
        $n = (int) session('n_sma', 7);

        return in_array($n, [3, 7], true) ? $n : 7;
    }

    public static function hitung(?int $n = null): array
    {
        $n = $n ?? self::pilihanSma();

        // Langkah 1
        $stok    = StokGudang::ringkasan();
        $sisaKg  = $stok['sisa'];

        // Langkah 2
        $rincian  = self::pemakaianHarian($n);
        $smaGram  = $rincian->count() > 0 ? (float) $rincian->avg('gram') : null;
        $smaKg    = $smaGram !== null ? $smaGram / 1000 : null;

        // Langkah 3
        $hariTahan    = ($sisaKg !== null && $smaKg > 0) ? $sisaKg / $smaKg : null;
        $tanggalHabis = $hariTahan !== null ? Carbon::today()->addDays((int) floor($hariTahan)) : null;

        // Langkah 4
        $kebutuhanKg = $smaKg !== null ? $smaKg * self::HARI_KEBUTUHAN : null;
        $perluBeliKg = ($kebutuhanKg !== null && $sisaKg !== null)
            ? (int) ceil(round(max($kebutuhanKg - $sisaKg, 0), 2))
            : null;

        return [
            'n'             => $n,
            'masukKg'       => $stok['masuk'],
            'keluarKg'      => $stok['keluar'],
            'sisaKg'        => $sisaKg,
            'rincian'       => $rincian,
            'jumlahData'    => $rincian->count(),
            'smaGram'       => $smaGram,
            'smaKg'         => $smaKg,
            'hariTahan'     => $hariTahan,
            'mingguTahan'   => $hariTahan !== null ? $hariTahan / 7 : null,
            'tanggalHabis'  => $tanggalHabis,
            'hariKebutuhan' => self::HARI_KEBUTUHAN,
            'kebutuhanKg'   => $kebutuhanKg,
            'perluBeliKg'   => $perluBeliKg,
            'adaDummy'      => $rincian->contains('dummy', true),
        ];
    }

    /**
     * Pakan keluar per hari (gram) pada n hari terakhir SEBELUM hari ini yang ada datanya.
     * Hari ini tidak ikut karena belum selesai. Urut dari terlama ke terbaru.
     */
    public static function pemakaianHarian(int $n)
    {
        $dummyLike = RiwayatPakan::DUMMY_PREFIX . '%';
        $gram      = RiwayatPakan::sqlGramKeluar();

        return RiwayatPakan::query()
            ->whereDate('tanggal', '<', Carbon::today()->toDateString())
            ->whereDate('tanggal', '>=', Carbon::today()->subDays(30)->toDateString())
            ->select(
                DB::raw('DATE(tanggal) as hari'),
                DB::raw("SUM({$gram}) as total"),
                DB::raw("MAX(CASE WHEN transaction_id LIKE '{$dummyLike}' THEN 1 ELSE 0 END) as dummy")
            )
            ->groupBy(DB::raw('DATE(tanggal)'))
            ->havingRaw("SUM({$gram}) > 0")
            ->orderByDesc('hari')
            ->limit($n)
            ->get()
            ->reverse()
            ->map(fn ($r) => [
                'tanggal' => Carbon::parse($r->hari)->format('d M'),
                'gram'    => (float) $r->total,
                'dummy'   => (bool) $r->dummy,
            ])
            ->values();
    }
}

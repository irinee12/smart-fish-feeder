<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class StokGudang extends Model
{
    protected $table = 'stok_gudangs';

    protected $fillable = [
        'tanggal',
        'jumlah_kg',
    ];

    protected $casts = [
        'tanggal'   => 'date',
        'jumlah_kg' => 'decimal:2',
    ];

    /**
     * Satu-satunya perhitungan sisa stok gudang (dipakai Jadwal dan Prediksi).
     * sisa = total stok yang diinput - pakan yang benar-benar keluar sejak stok pertama dicatat.
     *
     * @return array{masuk: ?float, keluar: float, sisa: ?float}
     */
    public static function ringkasan(): array
    {
        $tanggalAwal = static::min('tanggal');

        if ($tanggalAwal === null) {
            return ['masuk' => null, 'keluar' => 0.0, 'sisa' => null];
        }

        $masukKg = (float) static::sum('jumlah_kg');

        $keluarGram = (float) RiwayatPakan::asli()
            ->whereDate('tanggal', '>=', Carbon::parse($tanggalAwal)->toDateString())
            ->selectRaw('COALESCE(SUM(' . RiwayatPakan::sqlGramKeluar() . '), 0) as total')
            ->value('total');

        $keluarKg = $keluarGram / 1000;

        return [
            'masuk'  => $masukKg,
            'keluar' => $keluarKg,
            'sisa'   => max($masukKg - $keluarKg, 0),
        ];
    }
}

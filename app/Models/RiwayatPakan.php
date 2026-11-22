<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatPakan extends Model
{
    /** Awalan transaction_id untuk data contoh (hanya untuk uji perhitungan SMA). */
    public const DUMMY_PREFIX = 'DUMMY-';

    /** Hanya data asli dari alat (tanpa data contoh SMA). */
    public function scopeAsli($query)
    {
        return $query->where(function ($w) {
            $w->whereNull('transaction_id')
              ->orWhere('transaction_id', 'not like', self::DUMMY_PREFIX . '%');
        });
    }

    protected $fillable = [
        'jadwal_pakan_id',
        'tanggal',
        'waktu',
        'porsi_target',
        'porsi_aktual',
        'sisa_g',
        'status',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'porsi_target' => 'decimal:2',
        'porsi_aktual' => 'decimal:2',
        'sisa_g' => 'decimal:2',
    ];

    /**
     * Gram yang benar-benar keluar pada satu sesi = yang ditimbang - sisa yang tertahan di wadah.
     * Data lama (sisa_g kosong) dihitung seperti sebelumnya: porsi_aktual jika berhasil.
     */
    public function getGramKeluarAttribute(): float
    {
        $aktual = (float) $this->porsi_aktual;

        if ($this->sisa_g === null) {
            return $this->status === 'berhasil' ? $aktual : 0.0;
        }

        return max($aktual - (float) $this->sisa_g, 0.0);
    }

    /** Selisih pakan keluar terhadap target (gram). Negatif = kurang dari target. */
    public function getSelisihGramAttribute(): float
    {
        return $this->gram_keluar - (float) $this->porsi_target;
    }

    /** Hasil pemberian: [label, warna]. Batas: <=5% sesuai, <=15% hampir sesuai, selain itu tidak sesuai. */
    public function getHasilAttribute(): array
    {
        if ($this->status === 'gagal') {
            return ['Gagal', 'danger'];
        }

        $target = (float) $this->porsi_target;
        if ($target <= 0) {
            return ['-', 'secondary'];
        }

        $persen = abs($this->gram_keluar - $target) / $target * 100;

        if ($persen <= 5) {
            return ['Sesuai', 'success'];
        }

        return $persen <= 15 ? ['Hampir sesuai', 'warning'] : ['Tidak sesuai', 'danger'];
    }

    /** Versi SQL dari gram_keluar, untuk dipakai di SUM(). */
    public static function sqlGramKeluar(): string
    {
        return "CASE "
            . "WHEN sisa_g IS NULL THEN (CASE WHEN status = 'berhasil' THEN COALESCE(porsi_aktual, 0) ELSE 0 END) "
            . "WHEN COALESCE(porsi_aktual, 0) > sisa_g THEN porsi_aktual - sisa_g "
            . "ELSE 0 END";
    }

    /**
     * Total gram pakan yang benar-benar keluar pada satu tanggal (default: hari ini).
     * Dipakai bersama oleh Jadwal, Dashboard, dan Prediksi supaya angkanya selalu sama.
     */
    public static function totalGramKeluar(?string $tanggal = null): float
    {
        $tanggal = $tanggal ?? now()->toDateString();

        return (float) static::asli()
            ->whereDate('tanggal', $tanggal)
            ->selectRaw('COALESCE(SUM(' . static::sqlGramKeluar() . '), 0) as total')
            ->value('total');
    }
}

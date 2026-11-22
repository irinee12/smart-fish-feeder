<?php

namespace App\Console\Commands;

use App\Models\RiwayatPakan;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SmaDummy extends Command
{
    protected $signature = 'sma:dummy {--hapus : Hapus semua data contoh}';

    protected $description = 'Buat/hapus data contoh pemakaian pakan 14 hari terakhir, khusus untuk uji perhitungan SMA';

    // Total gram per hari, dari yang terlama sampai kemarin (sengaja naik-turun supaya SMA 3 dan SMA 7 beda)
    private const TOTAL_PER_HARI = [132, 141, 138, 150, 147, 156, 162, 158, 171, 168, 176, 181, 177, 189];

    // Jam tiga sesi pakan per hari
    private const JAM = ['07:00:00', '12:00:00', '17:00:00'];

    public function handle(): int
    {
        $hapus = RiwayatPakan::where('transaction_id', 'like', RiwayatPakan::DUMMY_PREFIX . '%')->delete();

        if ($this->option('hapus')) {
            $this->info("Data contoh dihapus ({$hapus} baris).");
            return self::SUCCESS;
        }

        $jumlahHari = count(self::TOTAL_PER_HARI);
        $dibuat = 0;

        foreach (self::TOTAL_PER_HARI as $i => $total) {
            $tanggal = Carbon::yesterday()->subDays($jumlahHari - 1 - $i)->toDateString();

            // Jangan menimpa hari yang sudah punya data asli dari alat
            if (RiwayatPakan::asli()->whereDate('tanggal', $tanggal)->exists()) {
                continue;
            }

            $perSesi = round($total / count(self::JAM), 1);

            foreach (self::JAM as $jam) {
                DB::table('riwayat_pakans')->insert([
                    'transaction_id' => RiwayatPakan::DUMMY_PREFIX . str_replace('-', '', $tanggal) . '-' . str_replace(':', '', $jam),
                    'tanggal'        => $tanggal,
                    'waktu'          => $jam,
                    'porsi_target'   => $perSesi,
                    'porsi_aktual'   => $perSesi,
                    'sisa_g'         => 0,
                    'status'         => 'berhasil',
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ]);
                $dibuat++;
            }
        }

        $this->info("Data contoh dibuat: {$dibuat} baris. Hanya dipakai untuk SMA, tidak mengurangi stok gudang dan tidak tampil di Riwayat.");
        $this->line('Hapus dengan: php artisan sma:dummy --hapus');

        return self::SUCCESS;
    }
}

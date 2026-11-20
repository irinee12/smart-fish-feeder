<?php

namespace App\Http\Controllers;

use App\Models\PengaturanStok;
use App\Models\RiwayatPakan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PrediksiStokController extends Controller
{
    public function index()
    {
        $pengaturan = PengaturanStok::latest()->first();

        $nSma         = (int) ($pengaturan->n_sma ?? 7);
        $waktuTunggu  = (int) ($pengaturan->waktu_tunggu ?? 3);
        $stokAwalKg   = $pengaturan && $pengaturan->stok_gudang_kg !== null
            ? (float) $pengaturan->stok_gudang_kg
            : null;
        $tanggalInput = $pengaturan->tanggal_stok_input ?? null;

        $konsumsiSejakInput = 0;
        if ($stokAwalKg !== null && $tanggalInput) {
            $konsumsiSejakInput = (float) RiwayatPakan::query()
                ->where('status', 'berhasil')
                ->whereNotNull('porsi_aktual')
                ->whereDate('tanggal', '>=', Carbon::parse($tanggalInput)->toDateString())
                ->sum('porsi_aktual');
        }

        $stokKg   = $stokAwalKg !== null ? max($stokAwalKg - ($konsumsiSejakInput / 1000), 0) : null;
        $stokGram = $stokKg !== null ? $stokKg * 1000 : null;

        $data = collect($this->konsumsiHarian($nSma))->values();
        $vals = $data->pluck('konsumsi')->map(fn ($v) => (float) $v)->all();

        $rows = [];
        foreach ($data as $i => $d) {
            $sma = $i + 1 >= $nSma
                ? array_sum(array_slice($vals, $i + 1 - $nSma, $nSma)) / $nSma
                : null;
            $rows[] = [
                'tanggal'  => $d['tanggal'],
                'konsumsi' => $vals[$i],
                'ada_data' => $d['ada_data'],
                'sma'      => $sma,
            ];
        }
        $rows = collect($rows);

        $smaSekarang   = $rows->count() ? $rows->last()['sma'] : null;
        $prediksiBesok = $smaSekarang;
        $prediksi7Hari = $smaSekarang !== null ? $smaSekarang * 7 : null;
        $sisaHari      = ($stokGram !== null && $smaSekarang > 0) ? $stokGram / $smaSekarang : null;

        if ($stokKg === null) {
            $statusKey   = 'none';
            $rekomendasi = 'Stok belum diinput';
            $pesan       = 'Isi stok gudang (kg) terlebih dahulu agar prediksi bisa dihitung.';
        } elseif ($sisaHari === null) {
            $statusKey   = 'none';
            $rekomendasi = 'Belum bisa dihitung';
            $pesan       = "Butuh data konsumsi minimal {$nSma} hari untuk menghitung rata-rata SMA.";
        } elseif ($sisaHari < $waktuTunggu) {
            $statusKey   = 'danger';
            $rekomendasi = 'Segera beli pakan';
            $pesan       = "Sisa stok gudang kurang dari waktu tunggu pembelian ({$waktuTunggu} hari). Pesan pakan sekarang agar tidak kehabisan.";
        } else {
            $statusKey   = 'ok';
            $rekomendasi = 'Stok mencukupi';
            $pesan       = "Stok gudang masih cukup melewati waktu tunggu pembelian ({$waktuTunggu} hari).";
        }

        $tglHabis    = $sisaHari !== null ? now()->addDays((int) floor($sisaHari)) : null;
        $tglBeli     = $tglHabis ? $tglHabis->copy()->subDays($waktuTunggu) : null;
        $tglBeliTeks = $tglBeli
            ? ($tglBeli->isPast() ? 'Sekarang' : $tglBeli->translatedFormat('d M Y'))
            : 'Belum tersedia';

        $gLabels = $rows->pluck('tanggal')->all();
        $gAktual = $rows->pluck('konsumsi')->map(fn ($v) => round($v))->all();
        $gSma    = $rows->pluck('sma')->map(fn ($v) => $v !== null ? round($v) : null)->all();
        $gProy   = array_fill(0, max(count($gLabels) - 1, 0), null);

        if ($smaSekarang !== null) {
            $gProy[] = round($smaSekarang);
            for ($i = 1; $i <= 7; $i++) {
                $gLabels[] = now()->addDays($i)->format('d M');
                $gAktual[] = null;
                $gSma[]    = null;
                $gProy[]   = round($smaSekarang);
            }
        }

        $adaGrafik = $rows->count() > 0;

        return view('prediksi', compact(
            'nSma', 'waktuTunggu', 'stokAwalKg', 'konsumsiSejakInput', 'tanggalInput',
            'stokKg', 'rows', 'smaSekarang', 'prediksiBesok', 'prediksi7Hari', 'sisaHari',
            'statusKey', 'rekomendasi', 'pesan', 'tglHabis', 'tglBeliTeks',
            'gLabels', 'gAktual', 'gSma', 'gProy', 'adaGrafik'
        ));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'stok_gudang_kg' => 'nullable|numeric|min:0',
            'n_sma'          => 'required|in:3,7',
            'waktu_tunggu'   => 'required|integer|min:0|max:60',
        ]);

        $pengaturan = PengaturanStok::latest()->first() ?? new PengaturanStok();

        $stokBaru = $data['stok_gudang_kg'] ?? null;
        if ($stokBaru !== null && (float) $stokBaru !== (float) $pengaturan->stok_gudang_kg) {
            $pengaturan->stok_gudang_kg     = $stokBaru;
            $pengaturan->tanggal_stok_input = now();
        }

        $pengaturan->n_sma        = $data['n_sma'];
        $pengaturan->waktu_tunggu = $data['waktu_tunggu'];
        $pengaturan->save();

        return redirect()->route('prediksi.index')->with('success', 'Pengaturan berhasil disimpan.');
    }

    private function konsumsiHarian(int $nSma): array
    {
        $mulai = Carbon::yesterday()->subDays($nSma + 6);
        $akhir = Carbon::yesterday();

        $riwayat = RiwayatPakan::query()
            ->where('status', 'berhasil')
            ->whereNotNull('porsi_aktual')
            ->whereDate('tanggal', '>=', $mulai)
            ->whereDate('tanggal', '<=', $akhir)
            ->select(DB::raw('DATE(tanggal) as hari'), DB::raw('SUM(porsi_aktual) as total'))
            ->groupBy(DB::raw('DATE(tanggal)'))
            ->pluck('total', 'hari');

        $hasil = [];
        for ($d = $mulai->copy(); $d->lte($akhir); $d->addDay()) {
            $key = $d->format('Y-m-d');
            $hasil[] = [
                'tanggal'  => $d->format('d M'),
                'konsumsi' => (float) ($riwayat[$key] ?? 0),
                'ada_data' => isset($riwayat[$key]),
            ];
        }

        while ($hasil && !$hasil[0]['ada_data']) {
            array_shift($hasil);
        }

        return $hasil;
    }
}
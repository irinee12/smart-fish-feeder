<?php

namespace App\Http\Controllers;

use App\Models\RiwayatPakan;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RiwayatController extends Controller
{
    // Jumlah hari terakhir pada grafik.
    private const HARI_GRAFIK = 14;

    // Batas baris di tabel supaya halaman tetap ringan.
    private const BATAS_BARIS = 300;

    public function index(Request $request)
    {
        $tanggal = $this->tanggalFilter($request);

        $riwayat = $this->queryRiwayat($tanggal)->limit(self::BATAS_BARIS)->get();

        // Grafik: total pakan yang benar-benar keluar per hari (gram), sumber sama dengan Dashboard & Prediksi
        $akhir = Carbon::today();
        $mulai = $akhir->copy()->subDays(self::HARI_GRAFIK - 1);

        $perHari = RiwayatPakan::asli()
            ->whereDate('tanggal', '>=', $mulai->toDateString())
            ->whereDate('tanggal', '<=', $akhir->toDateString())
            ->select(DB::raw('DATE(tanggal) as hari'), DB::raw('SUM(' . RiwayatPakan::sqlGramKeluar() . ') as total'))
            ->groupBy(DB::raw('DATE(tanggal)'))
            ->pluck('total', 'hari');

        $labelGrafik = [];
        $dataGrafik  = [];
        for ($d = $mulai->copy(); $d->lte($akhir); $d->addDay()) {
            $labelGrafik[] = $d->format('d M');
            $dataGrafik[]  = round((float) ($perHari[$d->format('Y-m-d')] ?? 0), 1);
        }

        // Ringkasan (dari data yang tampil di tabel)
        $berhasil = $riwayat->where('status', 'berhasil');
        $akurasi  = $berhasil->filter(fn ($r) => (float) $r->porsi_target > 0)
            ->map(fn ($r) => max(0, 100 - abs($r->selisih_gram) / (float) $r->porsi_target * 100));

        $ringkasan = [
            'keluar'   => (float) $riwayat->sum(fn ($r) => $r->gram_keluar),
            'sesi'     => $riwayat->count(),
            'berhasil' => $berhasil->count(),
            'gagal'    => $riwayat->where('status', 'gagal')->count(),
            'akurasi'  => $akurasi->count() > 0 ? $akurasi->avg() : null,
        ];

        return view('Riwayat', [
            'ringkasan'   => $ringkasan,
            'riwayat'     => $riwayat,
            'tanggal'     => $tanggal,
            'labelGrafik' => $labelGrafik,
            'dataGrafik'  => $dataGrafik,
            'adaGrafik'   => array_sum($dataGrafik) > 0,
        ]);
    }

    public function exportPdf(Request $request)
    {
        $tanggal = $this->tanggalFilter($request);

        $riwayat = $this->queryRiwayat($tanggal)->get();

        $pdf = Pdf::loadView('riwayat-pdf', [
            'riwayat' => $riwayat,
            'tanggal' => $tanggal,
        ]);

        $pdf->setPaper('A4', 'portrait');

        return $pdf->download('riwayat-pakan' . ($tanggal ? '-' . $tanggal : '') . '.pdf');
    }

    public function exportCsv(Request $request)
    {
        $tanggal = $this->tanggalFilter($request);
        $rows    = $this->queryRiwayat($tanggal)->get();
        $nama    = 'riwayat-pakan' . ($tanggal ? '-' . $tanggal : '') . '.csv';
        $g       = fn ($v) => number_format((float) $v, 1, ',', '');

        return response()->streamDownload(function () use ($rows, $g) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF"); // BOM supaya Excel membaca UTF-8
            fputcsv($out, ['Tanggal', 'Waktu', 'Target (g)', 'Keluar (g)', 'Tertahan (g)', 'Selisih (g)', 'Hasil'], ';');

            foreach ($rows as $r) {
                fputcsv($out, [
                    $r->tanggal->format('Y-m-d'),
                    substr($r->waktu, 0, 5),
                    $g($r->porsi_target),
                    $g($r->gram_keluar),
                    $g($r->sisa_g ?? 0),
                    $g($r->selisih_gram),
                    $r->hasil[0],
                ], ';');
            }

            fclose($out);
        }, $nama, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    private function tanggalFilter(Request $request): ?string
    {
        $tanggal = $request->query('tanggal');

        if (! $tanggal || ! preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggal)) {
            return null;
        }

        return $tanggal;
    }

    private function queryRiwayat(?string $tanggal)
    {
        $query = RiwayatPakan::asli()
            ->orderByDesc('tanggal')
            ->orderByDesc('waktu')
            ->orderByDesc('id');

        if ($tanggal) {
            $query->whereDate('tanggal', $tanggal);
        }

        return $query;
    }
}

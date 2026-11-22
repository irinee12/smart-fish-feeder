<?php

namespace App\Http\Controllers;

use App\Models\RiwayatPakan;
use App\Models\StokGudang;
use App\Services\PrediksiService;
use Illuminate\Http\Request;

class PrediksiStokController extends Controller
{
    public function index(Request $request)
    {
        // Pilihan SMA disimpan di session, jadi Dashboard ikut memakai pilihan yang sama
        if ($request->has('n_sma')) {
            $n = (int) $request->query('n_sma');
            if (in_array($n, [3, 7], true)) {
                session(['n_sma' => $n]);
            }
        }

        $hasil = PrediksiService::hitung();

        return view('prediksi', $hasil + [
            'nSma'        => $hasil['n'],
            'riwayatStok' => StokGudang::orderByDesc('tanggal')->orderByDesc('id')->limit(5)->get(),
            'hariIniGram' => RiwayatPakan::totalGramKeluar(),
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'tanggal_stok'   => 'required|date|before_or_equal:today',
            'jumlah_stok_kg' => 'required|numeric|min:0.01|max:100000',
        ], [
            'tanggal_stok.required'        => 'Tanggal pembelian wajib diisi.',
            'tanggal_stok.date'            => 'Format tanggal tidak valid.',
            'tanggal_stok.before_or_equal' => 'Tanggal pembelian tidak boleh di masa depan.',
            'jumlah_stok_kg.required'      => 'Jumlah pakan wajib diisi.',
            'jumlah_stok_kg.numeric'       => 'Jumlah pakan harus berupa angka.',
            'jumlah_stok_kg.min'           => 'Jumlah pakan minimal 0,01 kg.',
        ]);

        StokGudang::create([
            'tanggal'   => $data['tanggal_stok'],
            'jumlah_kg' => $data['jumlah_stok_kg'],
        ]);

        return redirect()
            ->route('prediksi.index')
            ->with('success', 'Stok ' . number_format((float) $data['jumlah_stok_kg'], 2, ',', '.') . ' kg berhasil ditambahkan.');
    }
}

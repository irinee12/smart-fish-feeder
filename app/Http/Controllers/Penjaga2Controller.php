<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class Penjaga2Controller extends Controller
{
    public function deteksiMata()
    {
        return view('penjaga2.deteksi_mata');
    }

    /**
     * Deteksi sekarang dijalankan di browser (TensorFlow.js).
     * Server hanya menyimpan gambar dan menampilkan hasil yang dikirim browser.
     */
    public function processDeteksi(Request $request)
    {
        $request->validate([
            'foto_mata'   => 'required|image|mimes:jpg,jpeg,png|max:5120',
            'mata_segar'  => 'required|numeric|between:0,100',
            'tidak_segar' => 'required|numeric|between:0,100',
        ], [
            'foto_mata.required'  => 'Pilih gambar mata ikan terlebih dahulu.',
            'foto_mata.image'     => 'File harus berupa gambar.',
            'foto_mata.mimes'     => 'Format harus JPG atau PNG.',
            'foto_mata.max'       => 'Ukuran gambar maksimal 5 MB.',
            'mata_segar.required' => 'Analisis gambar belum selesai. Muat ulang halaman lalu coba lagi.',
            'tidak_segar.required' => 'Analisis gambar belum selesai. Muat ulang halaman lalu coba lagi.',
        ]);

        $file   = $request->file('foto_mata');
        $nama   = $file->getClientOriginalName();
        $ukuran = number_format($file->getSize() / 1024 / 1024, 2) . ' MB';

        $segar      = round((float) $request->input('mata_segar'), 1);
        $tidakSegar = round((float) $request->input('tidak_segar'), 1);

        $namaSimpan = time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/deteksi'), $namaSimpan);

        return view('penjaga2.deteksi_mata', [
            'hasil' => [
                'kategori_utama'     => $segar >= $tidakSegar ? 'segar' : 'tidak segar',
                'persen_segar'       => $segar,
                'persen_tidak_segar' => $tidakSegar,
            ],
            'gambar_path' => 'uploads/deteksi/' . $namaSimpan,
            'nama_file'   => $nama,
            'ukuran_file' => $ukuran,
        ]);
    }
}
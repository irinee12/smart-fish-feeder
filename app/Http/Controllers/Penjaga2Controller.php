<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class Penjaga2Controller extends Controller
{
    private function getApiUrl(): string
    {
        return config('services.fish_api.url', 'http://127.0.0.1:5000');
    }

    public function deteksiMata()
    {
        return view('penjaga2.deteksi_mata');
    }

    public function processDeteksi(Request $request)
    {
        set_time_limit(150);

        $request->validate([
            'foto_mata' => 'required|image|mimes:jpg,jpeg,png|max:5120',
        ], [
            'foto_mata.required' => 'Pilih gambar mata ikan terlebih dahulu.',
            'foto_mata.image'    => 'File harus berupa gambar.',
            'foto_mata.mimes'    => 'Format harus JPG atau PNG.',
            'foto_mata.max'      => 'Ukuran gambar maksimal 5 MB.',
        ]);

        $file   = $request->file('foto_mata');
        $nama   = $file->getClientOriginalName();
        $ukuran = number_format($file->getSize() / 1024 / 1024, 2) . ' MB';
        $isi    = file_get_contents($file->getRealPath());

        try {
            // Tembak langsung ke API Python FastAPI
            $res = Http::timeout(60)
                ->attach('file', $isi, $nama)
                ->post($this->getApiUrl() . '/predict');
        } catch (\Exception $e) {
            return back()->withErrors(['foto_mata' => 'Server deteksi tidak merespons. Pastikan Uvicorn/FastAPI sudah berjalan di terminal.']);
        }

        if ($res->failed()) {
            return back()->withErrors(['foto_mata' => 'Gagal memproses gambar. Coba gambar lain.']);
        }

        $data = $res->json();

        $namaSimpan = time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/deteksi'), $namaSimpan);

        return view('penjaga2.deteksi_mata', [
            'hasil' => [
                'kategori_utama'     => ($data['mata_segar'] ?? 0) >= ($data['tidak_segar'] ?? 0) ? 'segar' : 'tidak segar',
                'persen_segar'       => $data['mata_segar'] ?? 0,
                'persen_tidak_segar' => $data['tidak_segar'] ?? 0,
            ],
            'gambar_path' => 'uploads/deteksi/' . $namaSimpan,
            'nama_file'   => $nama,
            'ukuran_file' => $ukuran,
        ]);
    }
}
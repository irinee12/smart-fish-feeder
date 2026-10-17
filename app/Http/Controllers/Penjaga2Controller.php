<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class Penjaga2Controller extends Controller
{
    // Cek apakah API Python sudah hidup
    private function apiHidup(): bool
    {
        try {
            return Http::timeout(2)->get(config('services.fish_api.url') . '/')->successful();
        } catch (\Exception $e) {
            return false;
        }
    }

    // Nyalakan API Python di latar belakang kalau belum hidup
    private function nyalakanApi(): void
    {
        if ($this->apiHidup()) {
            return;
        }
        pclose(popen('start "API Python" /MIN "D:\\fish_api\\jalankan_api.bat"', 'r'));
    }

    // Tunggu sampai API siap (maksimal 60 detik)
    private function tungguApi(): bool
    {
        for ($i = 0; $i < 60; $i++) {
            if ($this->apiHidup()) {
                return true;
            }
            sleep(1);
        }
        return false;
    }

    public function deteksiMata()
    {
        // Mulai menyalakan API begitu halaman dibuka, supaya model sudah siap saat Deteksi diklik
        $this->nyalakanApi();

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

        // Pastikan API hidup (kalau belum, nyalakan dan tunggu)
        $this->nyalakanApi();
        if (! $this->tungguApi()) {
            return back()->withErrors(['foto_mata' => 'Server deteksi belum siap. Tunggu sebentar lalu coba lagi.']);
        }

        try {
            $res = Http::timeout(60)
                ->attach('file', $isi, $nama)
                ->post(config('services.fish_api.url') . '/predict');
        } catch (\Exception $e) {
            return back()->withErrors(['foto_mata' => 'Server deteksi tidak merespons. Coba lagi.']);
        }

        if ($res->failed()) {
            return back()->withErrors(['foto_mata' => 'Gagal memproses gambar. Coba gambar lain.']);
        }

        $data = $res->json();

        $namaSimpan = time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/deteksi'), $namaSimpan);

        return view('penjaga2.deteksi_mata', [
            'hasil' => [
                'kategori_utama'     => $data['mata_segar'] >= $data['tidak_segar'] ? 'segar' : 'tidak segar',
                'persen_segar'       => $data['mata_segar'],
                'persen_tidak_segar' => $data['tidak_segar'],
            ],
            'gambar_path' => 'uploads/deteksi/' . $namaSimpan,
            'nama_file'   => $nama,
            'ukuran_file' => $ukuran,
        ]);
    }
}
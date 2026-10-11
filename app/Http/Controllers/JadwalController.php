<?php

namespace App\Http\Controllers;

use App\Models\JadwalPakan;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function index()
    {
        // Ambil data dari database, urutkan berdasarkan waktu
        $jadwals = JadwalPakan::orderBy('waktu', 'asc')->get();
        return view('Jadwal', compact('jadwals'));
    }

    public function store(Request $request)
    {
        // 1. Validasi inputan
        $request->validate([
            'waktu' => 'required',
            'sesi' => 'required',
            'porsi' => 'required|numeric',
        ]);

        // 2. Simpan ke database
        JadwalPakan::create([
            'waktu' => $request->waktu,
            'sesi' => $request->sesi,
            'porsi' => $request->porsi,
        ]);

        // 3. Kembali ke halaman jadwal dengan membawa pesan sukses
        return redirect()->route('jadwal')->with('success', 'Jadwal pakan berhasil ditambahkan!');

        
    }

    // ... method index dan store sebelumnya ...

    // Menghapus data jadwal pakan
    public function destroy($id)
    {
        $jadwal = JadwalPakan::findOrFail($id); // Cari data berdasarkan ID
        $jadwal->delete(); // Hapus data

        return redirect()->route('jadwal')->with('success', 'Jadwal pakan berhasil dihapus!');
    }
}
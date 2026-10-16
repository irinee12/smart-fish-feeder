<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Penjaga2Controller extends Controller
{
    public function index()
    {
        return view('penjaga2.dashboard');
    }

    public function deteksiMata()
    {
        // Di sini nanti tempat menaruh logika atau integrasi model computer vision
        return view('penjaga2.deteksi_mata');
    }
}
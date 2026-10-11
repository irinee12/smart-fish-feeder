<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class RiwayatController extends Controller
{
    public function index()
    {
        return view('Riwayat');
    }

    public function exportPdf(Request $request)
    {
        // Database belum terhubung.
        // Untuk sementara data dibuat kosong.
        $riwayat = [];

        $pdf = Pdf::loadView('riwayat-pdf', [
            'riwayat' => $riwayat,
        ]);

        $pdf->setPaper('A4', 'portrait');

        return $pdf->download('riwayat-pakan.pdf');
    }
}
<?php
namespace App\Http\Controllers;

use App\Exports\RiwayatExport;
use App\Models\RiwayatDeteksi;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class RiwayatDeteksiController extends Controller
{
    private function query(Request $r)
    {
        $q = RiwayatDeteksi::query();
        if ($r->filled('dari'))   $q->whereDate('created_at', '>=', $r->dari);
        if ($r->filled('sampai')) $q->whereDate('created_at', '<=', $r->sampai);
        return $q->latest();
    }

    private function ringkasan($data): array
    {
        return [
            'total' => $data->count(),
            'segar' => $data->where('hasil', 'Segar')->count(),
            'tidak' => $data->where('hasil', 'Tidak Segar')->count(),
            'rata'  => round($data->avg('akurasi') ?? 0, 2),
        ];
    }

    // Dipanggil dari JavaScript setelah deteksi selesai
    public function simpan(Request $request)
    {
        $v = $request->validate([
            'hasil'   => 'required|in:Segar,Tidak Segar',
            'akurasi' => 'required|numeric|min:0|max:100',
            'sumber'  => 'required|in:upload,kamera',
        ]);

        $v['user_id'] = auth()->id();
        RiwayatDeteksi::create($v);

        return response()->json(['ok' => true]);
    }

    public function index(Request $request)
    {
        $ringkasan = $this->ringkasan($this->query($request)->get());
        $data = $this->query($request)->paginate(20)->withQueryString();

        return view('penjaga2.riwayat', compact('data', 'ringkasan'));
    }

    public function pdf(Request $request)
    {
        $data = $this->query($request)->get();
        $ringkasan = $this->ringkasan($data);
        $dari = $request->dari;
        $sampai = $request->sampai;

        return Pdf::loadView('penjaga2.riwayat_pdf', compact('data', 'ringkasan', 'dari', 'sampai'))
            ->setPaper('a4')
            ->download('laporan-deteksi-mata-ikan.pdf');
    }

    public function excel(Request $request)
    {
        return Excel::download(
            new RiwayatExport($this->query($request)->get()),
            'laporan-deteksi-mata-ikan.xlsx'
        );
    }
}
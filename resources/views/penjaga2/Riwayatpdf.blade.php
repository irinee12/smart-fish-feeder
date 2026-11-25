<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #444; padding: 6px; text-align: left; }
        th { background: #eee; }
    </style>
</head>
<body>
    <h2>Laporan Deteksi Mata Ikan</h2>
    <p>Periode: {{ $dari ?: 'awal' }} s/d {{ $sampai ?: 'sekarang' }}</p>
    <p>
        Total: {{ $ringkasan['total'] }} |
        Segar: {{ $ringkasan['segar'] }} |
        Tidak Segar: {{ $ringkasan['tidak'] }} |
        Rata-rata akurasi: {{ $ringkasan['rata'] }}%
    </p>
    <table>
        <tr><th>No</th><th>Tanggal</th><th>Hasil</th><th>Akurasi</th><th>Sumber</th></tr>
        @foreach ($data as $i => $row)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $row->created_at->format('d-m-Y H:i') }}</td>
            <td>{{ $row->hasil }}</td>
            <td>{{ $row->akurasi }}%</td>
            <td>{{ ucfirst($row->sumber) }}</td>
        </tr>
        @endforeach
    </table>
</body>
</html>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">

    <title>Riwayat Pakan</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #1a3a5c;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 20px;
        }

        .header p {
            margin: 5px 0 0;
            color: #64748b;
            font-size: 10px;
        }

        .info {
            margin-bottom: 15px;
        }

        .info table {
            width: 100%;
            border: none;
        }

        .info td {
            padding: 4px 0;
            border: none;
        }

        table.data {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table.data th {
            background: #1a3a5c;
            color: white;
            padding: 8px;
            text-align: left;
        }

        table.data td {
            padding: 8px;
            border: 1px solid #dbe3ec;
        }

        .empty {
            text-align: center;
            padding: 30px;
            color: #64748b;
        }

        .footer {
            margin-top: 25px;
            font-size: 9px;
            color: #64748b;
            text-align: center;
        }
    </style>
</head>

<body>

    <div class="header">
        <h1>Riwayat Pemberian Pakan</h1>

        <p>
            Smart Fish Feeder — Sistem Pemberian Pakan Otomatis Berbasis IoT
        </p>
    </div>

    <div class="info">
        <table>
            <tr>
                <td><strong>Dicetak pada</strong></td>
                <td>{{ now()->format('d M Y H:i') }}</td>
            </tr>

            <tr>
                <td><strong>Total Data</strong></td>
                <td>{{ count($riwayat) }} data</td>
            </tr>
        </table>
    </div>

    <table class="data">

        <thead>
            <tr>
                <th>No.</th>
                <th>Tanggal</th>
                <th>Waktu</th>
                <th>Porsi</th>
                <th>Status</th>
            </tr>
        </thead>

        <tbody>

            @forelse($riwayat as $index => $item)

                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->tanggal }}</td>
                    <td>{{ $item->waktu }}</td>
                    <td>{{ $item->porsi }} g</td>
                    <td>{{ $item->status }}</td>
                </tr>

            @empty

                <tr>
                    <td colspan="5" class="empty">
                        Belum ada data riwayat pemberian pakan.
                    </td>
                </tr>

            @endforelse

        </tbody>

    </table>

    <div class="footer">
        Sistem Pakan Ikan Otomatis · Berbasis ESP32
    </div>

</body>
</html>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Riwayat Deteksi Mata Ikan</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        :root { --sea-deep:#052e4a; --sea-mid:#0a6a8f; --sea-bright:#1296c4; --ink:#0f2a3a; --muted:#5b7384; --line:#e1ecf2; }
        *, *::before, *::after { box-sizing: border-box; }
        body { margin:0; min-height:100vh; font-family:'Plus Jakarta Sans',system-ui,sans-serif; font-size:14px; color:var(--ink);
               background:linear-gradient(160deg,#e8f6fa 0%,#f4fafc 50%,#e3f1f7 100%); background-attachment:fixed; }
        .header { color:#fff; padding:1.25rem 0 2rem;
                  background:radial-gradient(circle at 15% 10%,rgba(94,234,212,.3),transparent 45%),linear-gradient(135deg,var(--sea-bright),var(--sea-mid) 55%,var(--sea-deep));
                  box-shadow:0 10px 24px -12px rgba(5,46,74,.5); }
        .wrap { max-width:1200px; margin:0 auto; padding:0 1.5rem; }
        .header .wrap { display:flex; justify-content:space-between; align-items:center; gap:1rem; flex-wrap:wrap; }
        .header h1 { margin:0; font-size:1.25rem; font-weight:800; letter-spacing:-.02em; }
        .header p { margin:2px 0 0; font-size:.8125rem; color:rgba(255,255,255,.8); }
        .back { color:#fff; text-decoration:none; font-weight:600; font-size:.8125rem; padding:.45rem .9rem;
                background:rgba(255,255,255,.16); border:1px solid rgba(255,255,255,.35); border-radius:10px; }
        .back:hover { background:rgba(255,255,255,.28); }
        main { padding:1.5rem 0 3rem; }
        .card { background:#fff; border:1px solid var(--line); border-radius:16px; padding:1.25rem; margin-bottom:1.25rem;
                box-shadow:0 8px 24px -16px rgba(5,46,74,.25); }
        form.filter { display:flex; flex-wrap:wrap; gap:.75rem; align-items:flex-end; }
        label { display:block; font-size:.75rem; font-weight:600; color:var(--muted); margin-bottom:4px; }
        input[type=date] { font:inherit; padding:.5rem .7rem; border:1px solid #c9dde7; border-radius:10px; background:#fff; color:var(--ink); }
        .btn { font:inherit; font-weight:600; padding:.55rem 1rem; border-radius:10px; border:1px solid transparent; cursor:pointer; text-decoration:none; display:inline-block; }
        .btn-primary { background:var(--sea-mid); color:#fff; } .btn-primary:hover { background:var(--sea-deep); }
        .btn-outline { background:#fff; color:var(--sea-mid); border-color:#c9dde7; } .btn-outline:hover { background:#f0f8fb; }
        .spacer { flex:1; }
        .stats { display:grid; grid-template-columns:repeat(auto-fit,minmax(170px,1fr)); gap:1rem; margin-bottom:1.25rem; }
        .stat { background:#fff; border:1px solid var(--line); border-radius:16px; padding:1rem 1.25rem; }
        .stat span { display:block; font-size:.75rem; font-weight:600; color:var(--muted); }
        .stat b { display:block; font-size:1.75rem; font-weight:800; margin-top:4px; }
        .stat.fresh b { color:#059669; } .stat.stale b { color:#dc2626; }
        .table-wrap { overflow-x:auto; }
        table { width:100%; border-collapse:collapse; }
        th { text-align:left; font-size:.75rem; text-transform:uppercase; letter-spacing:.04em; color:var(--muted); padding:.65rem .75rem; border-bottom:2px solid var(--line); }
        td { padding:.7rem .75rem; border-bottom:1px solid var(--line); }
        tr:hover td { background:#f7fbfd; }
        .badge { display:inline-block; padding:.2rem .65rem; border-radius:999px; font-size:.75rem; font-weight:700; }
        .badge.segar { background:#d1fae5; color:#047857; } .badge.tidak { background:#fee2e2; color:#b91c1c; }
        .empty { text-align:center; color:var(--muted); padding:2rem; }
        .pager { margin-top:1rem; } .pager nav svg { width:18px; height:18px; display:inline; }
        .pager nav > div:first-child { display:none; }
    </style>
</head>
<body>
    <header class="header">
        <div class="wrap">
            <div>
                <h1>Riwayat Deteksi Mata Ikan</h1>
                <p>Rekap hasil deteksi kesegaran berdasarkan tanggal.</p>
            </div>
            <a href="javascript:history.back()" class="back">&larr; Kembali ke deteksi</a>
        </div>
    </header>

    <main class="wrap">
        <div class="card">
            <form method="GET" action="{{ route('deteksi-riwayat.index') }}" class="filter">
                <div><label>Dari tanggal</label><input type="date" name="dari" value="{{ request('dari') }}"></div>
                <div><label>Sampai tanggal</label><input type="date" name="sampai" value="{{ request('sampai') }}"></div>
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('deteksi-riwayat.index') }}" class="btn btn-outline">Reset</a>
                <span class="spacer"></span>
                <a href="{{ route('deteksi-riwayat.pdf', request()->query()) }}" class="btn btn-outline">Unduh PDF</a>
                <a href="{{ route('deteksi-riwayat.excel', request()->query()) }}" class="btn btn-outline">Unduh Excel</a>
            </form>
        </div>

        <div class="stats">
            <div class="stat"><span>Total deteksi</span><b>{{ $ringkasan['total'] }}</b></div>
            <div class="stat fresh"><span>Segar</span><b>{{ $ringkasan['segar'] }}</b></div>
            <div class="stat stale"><span>Tidak segar</span><b>{{ $ringkasan['tidak'] }}</b></div>
            <div class="stat"><span>Rata-rata akurasi</span><b>{{ $ringkasan['rata'] }}%</b></div>
        </div>

        <div class="card table-wrap">
            <table>
                <thead>
                    <tr><th>No</th><th>Tanggal</th><th>Hasil</th><th>Akurasi</th><th>Sumber</th></tr>
                </thead>
                <tbody>
                @forelse ($data as $i => $row)
                    <tr>
                        <td>{{ $data->firstItem() + $i }}</td>
                        <td>{{ $row->created_at->format('d-m-Y H:i') }}</td>
                        <td><span class="badge {{ $row->hasil === 'Segar' ? 'segar' : 'tidak' }}">{{ $row->hasil }}</span></td>
                        <td>{{ $row->akurasi }}%</td>
                        <td>{{ ucfirst($row->sumber) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="empty">Belum ada data.</td></tr>
                @endforelse
                </tbody>
            </table>
            <div class="pager">{{ $data->links() }}</div>
        </div>
    </main>
</body>
</html>
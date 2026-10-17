<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deteksi Kesegaran Mata Ikan</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        nauti: {
                            50: '#f0fdfa',
                            100: '#ccfbf1',
                            500: '#14b8a6',
                            700: '#0f766e',
                            800: '#115e59',
                            900: '#134e4a',
                            dark: '#006d77'
                        }
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-[#f4f7f6] text-slate-700 min-h-screen flex flex-col justify-between">

    <!-- NAVBAR HEADER (HANYA JUDUL DIBAGIAN KIRI & TOMBOL KELUAR DI KANAN) -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            
            <!-- Judul Halaman Kiri (Tanpa Logo & Teks NautiLab) -->
            <div>
                <h1 class="font-bold text-lg sm:text-xl text-slate-800 leading-none">Deteksi Mata Ikan</h1>
            </div>

            <!-- Tombol Keluar (Logout) Kanan -->
            <div>
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="text-xs sm:text-sm font-medium text-red-600 hover:text-red-700 bg-red-50 hover:bg-red-100 px-3.5 py-1.5 rounded-lg border border-red-200 flex items-center space-x-1.5 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        <span>Keluar</span>
                    </button>
                </form>
            </div>

        </div>
    </header>

    <!-- MAIN CONTENT CONTAINER -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full flex-grow">
        
        <!-- TITLE & STEPS -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-8">
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold text-slate-800">Deteksi kesegaran mata ikan</h2>
                <p class="text-sm text-slate-500 mt-1">Unggah foto mata ikan, lalu lihat estimasi kesegaran dalam satu analisis.</p>
            </div>
            
            <!-- Step Indicators -->
            <div class="flex items-center space-x-4 sm:space-x-6 text-xs sm:text-sm font-medium self-start lg:self-auto">
                <div class="flex items-center space-x-2">
                    <span class="w-6 h-6 rounded-full bg-[#007a78] text-white flex items-center justify-center text-xs font-bold">1</span>
                    <span class="text-slate-700">Unggah gambar</span>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="w-6 h-6 rounded-full {{ isset($hasil) ? 'bg-[#007a78] text-white' : 'bg-slate-200 text-slate-600' }} flex items-center justify-center text-xs font-bold">2</span>
                    <span class="{{ isset($hasil) ? 'text-slate-700' : 'text-slate-400' }}">Deteksi</span>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="w-6 h-6 rounded-full {{ isset($hasil) ? 'bg-[#007a78] text-white' : 'bg-slate-200 text-slate-600' }} flex items-center justify-center text-xs font-bold">3</span>
                    <span class="{{ isset($hasil) ? 'text-slate-700' : 'text-slate-400' }}">Lihat hasil</span>
                </div>
            </div>
        </div>

        <!-- MAIN GRID SECTION -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

            <!-- LEFT PANEL: UPLOAD & PREVIEW -->
            <div class="lg:col-span-7 bg-white rounded-2xl p-4 sm:p-6 border border-slate-200/80 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="font-bold text-slate-800 text-base sm:text-lg">Gambar mata ikan</h3>
                        <button type="button" class="bg-slate-100 text-slate-600 hover:bg-slate-200 px-3 py-1 rounded-full text-xs font-medium border border-slate-200 transition">
                            Gambar contoh
                        </button>
                    </div>
                    <p class="text-xs text-slate-400 mb-4">Gunakan foto close-up dengan mata terlihat jelas.</p>

                    <!-- IMAGE DISPLAY AREA (EMPTY STATE BY DEFAULT) -->
                    <form action="{{ route('deteksi.process') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                        @csrf
                        <div id="dropArea" onclick="if(event.target.id!=='fileInput') document.getElementById('fileInput').click()" class="relative w-full h-64 sm:h-80 bg-slate-100 rounded-xl overflow-hidden border-2 border-dashed border-slate-200 flex flex-col items-center justify-center group hover:border-[#007a78] transition cursor-pointer">
                            
                            @if(isset($gambar_path))
                                <img src="{{ asset($gambar_path) }}" alt="Preview Mata Ikan" class="w-full h-full object-cover">
                                <span class="absolute top-3 left-3 bg-black/60 backdrop-blur-md text-white text-xs px-2.5 py-1 rounded-md flex items-center space-x-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <span>Pratinjau gambar</span>
                                </span>
                            @else
                                <div id="placeholder" class="text-center p-6 flex flex-col items-center">
                                    <div class="w-12 h-12 rounded-full bg-slate-200 flex items-center justify-center text-slate-400 mb-3 group-hover:bg-[#e6f4f3] group-hover:text-[#007a78] transition">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <p class="text-sm font-semibold text-slate-600 group-hover:text-[#007a78]">Klik untuk mengunggah atau seret file ke sini</p>
                                    <p class="text-xs text-slate-400 mt-1">Belum ada gambar yang dipilih</p>
                                </div>
                            @endif

                            <input type="file" name="foto_mata" id="fileInput" class="hidden" accept="image/png, image/jpeg">
                            <img id="previewImg" src="" alt="Preview" class="hidden absolute inset-0 w-full h-full object-cover">
                        </div>

                        <!-- FILE INFO BAR -->
                        <div class="mt-4 bg-slate-50 border border-slate-100 rounded-xl p-3 flex items-center justify-between">
                            <div class="flex items-center space-x-3 overflow-hidden">
                                <div class="p-2 bg-slate-200/60 rounded-lg text-slate-500 flex-shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </div>
                                <div class="truncate">
                                    <p id="namaFile" class="text-xs font-semibold text-slate-700 truncate">
                                        {{ $nama_file ?? 'Belum ada file terpilih' }}
                                    </p>
                                    <p id="infoFile" class="text-[11px] text-slate-400">
                                        {{ isset($ukuran_file) ? $ukuran_file : 'JPG, PNG • Maks 5 MB' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- PESAN ERROR -->
                        @if($errors->any())
                            <div class="mt-4 bg-red-50 border border-red-200 text-red-700 text-xs rounded-xl p-3">
                                {{ $errors->first() }}
                            </div>
                        @endif

                        <!-- ACTION BUTTONS -->
                        <div class="mt-4 flex flex-col sm:flex-row items-center gap-3">
                            <button type="button" onclick="document.getElementById('fileInput').click()" class="w-full sm:w-auto flex-1 bg-white hover:bg-slate-50 border border-slate-300 text-slate-700 font-medium py-2.5 px-4 rounded-xl text-xs sm:text-sm flex items-center justify-center space-x-2 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                <span>Input File</span>
                            </button>
                            <button type="submit" id="btnDeteksi" class="w-full sm:w-auto flex-1 bg-[#007a78] hover:bg-[#006866] text-white font-medium py-2.5 px-4 rounded-xl text-xs sm:text-sm flex items-center justify-center space-x-2 transition shadow-sm disabled:opacity-70 disabled:cursor-not-allowed">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <span>Deteksi</span>
                            </button>
                        </div>
                    </form>
                </div>

                <div class="mt-4 flex items-center justify-between text-[11px] text-slate-400 border-t border-slate-100 pt-3">
                    <span>Format JPG atau PNG · Maksimal 5 MB</span>
                    <span class="hidden sm:inline">Input File untuk mengganti gambar</span>
                </div>
            </div>

            <!-- RIGHT PANEL: DETECTION RESULTS -->
            <div class="lg:col-span-5 bg-white rounded-2xl p-4 sm:p-6 border border-slate-200/80 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <h3 class="font-bold text-slate-800 text-base sm:text-lg">Hasil deteksi</h3>
                        <span class="bg-teal-50 text-teal-700 text-[10px] font-semibold px-2.5 py-0.5 rounded-full border border-teal-100">Hasil model</span>
                    </div>
                    <p class="text-xs text-slate-400 mb-5">Estimasi berdasarkan gambar mata ikan.</p>

                    @if(isset($hasil))
                        <div class="bg-[#e6f4f3] border border-[#bce3e0] rounded-xl p-3.5 mb-5 flex items-start space-x-3">
                            <div class="w-6 h-6 rounded-full bg-teal-600 text-white flex items-center justify-center flex-shrink-0 mt-0.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <div>
                                <h4 class="text-xs sm:text-sm font-bold text-teal-900">Mata ikan terindikasi {{ $hasil['kategori_utama'] ?? 'segar' }}</h4>
                                <p class="text-[11px] text-teal-700 mt-0.5">{{ ($hasil['kategori_utama'] ?? 'segar') === 'segar' ? 'Mata Segar' : 'Tidak Segar' }} adalah kategori dengan persentase tertinggi.</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3 mb-6">
                            <div class="bg-[#eaf6f5] p-3.5 rounded-xl border border-teal-100">
                                <div class="flex items-center space-x-1.5 text-xs text-teal-800 font-medium mb-1">
                                    <span class="w-2 h-2 rounded-full bg-teal-600"></span>
                                    <span>Mata Segar</span>
                                </div>
                                <div class="text-2xl sm:text-3xl font-extrabold text-teal-950">
                                    {{ $hasil['persen_segar'] ?? 0 }}<span class="text-lg font-bold">%</span>
                                </div>
                            </div>
                            <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-100">
                                <div class="flex items-center space-x-1.5 text-xs text-slate-500 font-medium mb-1">
                                    <span class="w-2 h-2 rounded-full bg-orange-400"></span>
                                    <span>Tidak Segar</span>
                                </div>
                                <div class="text-2xl sm:text-3xl font-extrabold text-slate-700">
                                    {{ $hasil['persen_tidak_segar'] ?? 0 }}<span class="text-lg font-bold">%</span>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- EMPTY STATE -->
                        <div class="py-12 px-4 border-2 border-dashed border-slate-100 rounded-xl text-center flex flex-col items-center justify-center my-auto">
                            <div class="w-14 h-14 bg-slate-50 rounded-full flex items-center justify-center text-slate-300 mb-3">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                            </div>
                            <h4 class="text-sm font-semibold text-slate-600">Belum Ada Hasil Analisis</h4>
                            <p class="text-xs text-slate-400 mt-1 max-w-xs">Silakan unggah gambar mata ikan terlebih dahulu dan klik tombol "Deteksi" untuk melihat hasil analisis.</p>
                        </div>
                    @endif
                </div>

                <div class="mt-6 border-t border-slate-100 pt-3 flex items-start space-x-2 text-[11px] text-slate-400 leading-snug">
                    <svg class="w-4 h-4 text-slate-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Persentase menunjukkan estimasi kategori, bukan jaminan keamanan pangan. Lakukan pemeriksaan lanjutan.</span>
                </div>
            </div>

        </div>

        <!-- BOTTOM TIPS SECTION -->
        <div class="mt-8 bg-white rounded-2xl p-4 sm:p-6 border border-slate-200/80 shadow-sm">
            <h4 class="font-bold text-slate-800 text-sm mb-4">Tips untuk gambar yang lebih baik</h4>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="flex items-start space-x-3 bg-slate-50/70 p-3.5 rounded-xl border border-slate-100">
                    <div class="p-2 bg-emerald-100/60 text-emerald-700 rounded-lg flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h0.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div>
                        <h5 class="text-xs font-bold text-slate-800">Fokus pada mata</h5>
                        <p class="text-[11px] text-slate-400 mt-0.5">Ambil foto dari dekat. Pastikan seluruh mata terlihat dan tidak buram.</p>
                    </div>
                </div>
                <div class="flex items-start space-x-3 bg-slate-50/70 p-3.5 rounded-xl border border-slate-100">
                    <div class="p-2 bg-teal-100/60 text-teal-700 rounded-lg flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </div>
                    <div>
                        <h5 class="text-xs font-bold text-slate-800">Pencahayaan merata</h5>
                        <p class="text-[11px] text-slate-400 mt-0.5">Gunakan cahaya yang cukup. Hindari bayangan dan pantulan berlebihan.</p>
                    </div>
                </div>
                <div class="flex items-start space-x-3 bg-slate-50/70 p-3.5 rounded-xl border border-slate-100">
                    <div class="p-2 bg-teal-100/60 text-teal-700 rounded-lg flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <h5 class="text-xs font-bold text-slate-800">Satu gambar, satu ikan</h5>
                        <p class="text-[11px] text-slate-400 mt-0.5">Gunakan satu mata ikan sebagai objek utama agar gambar mudah dianalisis.</p>
                    </div>
                </div>
            </div>
        </div>

    </main>

    <!-- FOOTER POLOS -->
    <footer class="bg-white border-t border-slate-200 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 text-xs text-slate-400 text-center">
            © {{ date('Y') }} Deteksi Mata Ikan
        </div>
    </footer>

    <!-- LOGIKA: PREVIEW GAMBAR & STATUS TOMBOL -->
    <script>
        const input = document.getElementById('fileInput');
        const preview = document.getElementById('previewImg');
        const placeholder = document.getElementById('placeholder');
        const namaFile = document.getElementById('namaFile');
        const infoFile = document.getElementById('infoFile');

        input.addEventListener('change', () => {
            const f = input.files[0];
            if (!f) return;

            // sembunyikan gambar lama (hasil sebelumnya) dan placeholder
            document.querySelectorAll('#dropArea img:not(#previewImg)').forEach(i => i.classList.add('hidden'));
            if (placeholder) placeholder.classList.add('hidden');

            preview.src = URL.createObjectURL(f);
            preview.classList.remove('hidden');
            namaFile.textContent = f.name;
            infoFile.textContent = (f.size / 1024 / 1024).toFixed(2) + ' MB';
        });

        document.getElementById('uploadForm').addEventListener('submit', () => {
            const b = document.getElementById('btnDeteksi');
            b.disabled = true;
            b.querySelector('span').textContent = 'Memproses...';
        });
    </script>

</body>
</html>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deteksi Kesegaran Mata Ikan</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            background: #f1f6f6;
            color: #1e293b;
            font-family: 'Inter', ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
        }

        .main-header {
            width: 100%;
            height: 58px;
            background: #ffffff;
            border-bottom: 1px solid rgba(226, 232, 240, 0.85);
            padding: 0 40px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 1px 3px rgba(15, 23, 42, 0.02);
        }

        .header-title {
            font-size: 16px;
            font-weight: 700;
            letter-spacing: -0.01em;
            color: #173f43;
        }

        .btn-keluar {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 7px 16px;
            font-size: 12px;
            font-weight: 600;
            color: #475569;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-keluar:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
            color: #0f172a;
        }

        .main-container {
            width: 100%;
            max-width: 1140px;
            margin: 0 auto;
            padding: 28px 24px 36px;
        }

        .page-heading {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 22px;
        }

        .page-heading h1 {
            color: #163f43;
            font-size: 26px;
            line-height: 1.2;
            font-weight: 600;
            letter-spacing: -0.02em;
        }

        .page-heading p {
            margin-top: 6px;
            color: #71858a;
            font-size: 12px;
            line-height: 1.5;
        }

        .session-badge {
            flex-shrink: 0;
            padding: 5px 12px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.8);
            border: 1px solid rgba(226, 232, 240, 0.9);
            color: #64777b;
            font-size: 11px;
            font-weight: 500;
        }

        .content-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 20px;
            align-items: start;
        }

        @media (max-width: 868px) {
            .content-grid {
                grid-template-columns: 1fr;
            }
            .main-header {
                padding: 0 20px;
            }
        }

        .card {
            background: #ffffff;
            border: 1px solid rgba(226, 232, 240, 0.9);
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 1px 3px rgba(15, 23, 42, 0.02);
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }

        .card-title-wrapper {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .card-title-wrapper h2 {
            font-size: 14px;
            font-weight: 700;
            color: #24383b;
        }

        .number-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 22px;
            height: 22px;
            padding: 0 6px;
            border-radius: 6px;
            background: #effafa;
            border: 1px solid #d5efed;
            color: #0f807f;
            font-size: 10px;
            font-weight: 700;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 10px;
            font-weight: 600;
            white-space: nowrap;
        }

        .status-badge.gray {
            background: #f1f5f6;
            color: #71858a;
            border: 1px solid #e2e8f0;
        }

        .status-badge.neutral {
            background: #f8fafc;
            color: #94a3b8;
            border: 1px solid #e2e8f0;
        }

        .image-preview {
            width: 100%;
            height: 210px;
            border-radius: 12px;
            overflow: hidden;
            position: relative;
            background: linear-gradient(135deg, #edf4f4 0%, #f8fafb 50%, #edf4f4 100%);
            border: 1px solid #e2ebeb;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .empty-preview {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 10px;
            color: #9aabad;
            font-size: 12px;
            font-weight: 500;
        }

        .empty-icon {
            width: 46px;
            height: 46px;
            border-radius: 12px;
            background: #ffffff;
            border: 1px solid #e2ebeb;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 1px 2px rgba(0,0,0,0.03);
        }

        .empty-icon svg {
            width: 22px;
            height: 22px;
            fill: none;
            stroke: #8ca3a6;
            stroke-width: 1.5;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .preview-label {
            position: absolute;
            top: 12px;
            left: 12px;
            display: flex;
            align-items: center;
            gap: 5px;
            padding: 4px 8px;
            border-radius: 6px;
            background: rgba(255, 255, 255, 0.9);
            color: #4d696c;
            border: 1px solid rgba(210, 226, 226, 0.9);
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 0.05em;
        }

        .preview-label svg {
            width: 12px;
            height: 12px;
            fill: none;
            stroke: currentColor;
            stroke-width: 1.8;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .preview-expand {
            position: absolute;
            right: 12px;
            bottom: 12px;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #dce9e9;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.92);
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .preview-expand:hover {
            background: #ffffff;
            transform: scale(1.05);
        }

        .preview-expand svg {
            width: 15px;
            height: 15px;
            fill: none;
            stroke: #587174;
            stroke-width: 1.7;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .file-information {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 2px 0;
        }

        .file-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .file-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: #f1f5f5;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #71888b;
        }

        .file-icon svg {
            width: 16px;
            height: 16px;
            fill: none;
            stroke: currentColor;
            stroke-width: 1.7;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .file-name {
            font-size: 12px;
            font-weight: 700;
            color: #304448;
            line-height: 1.3;
        }

        .file-size {
            margin-top: 2px;
            font-size: 10px;
            color: #99a9ac;
        }

        .file-check {
            color: #cbd5e1;
        }

        .file-check svg {
            width: 18px;
            height: 18px;
            fill: none;
            stroke: currentColor;
            stroke-width: 1.8;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .input-file-button {
            width: 100%;
            min-height: 38px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 8px 14px;
            border-radius: 10px;
            border: 1px solid #dfe8e9;
            background: #ffffff;
            color: #52676a;
            font-size: 11px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .input-file-button:hover {
            background: #f8fbfb;
            border-color: #c6dddd;
            color: #0b7977;
        }

        .input-file-button svg {
            width: 15px;
            height: 15px;
            fill: none;
            stroke: currentColor;
            stroke-width: 1.8;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .file-hint {
            text-align: center;
            color: #99a8aa;
            font-size: 10px;
            margin-top: -6px;
        }

        .btn-detection {
            width: 100%;
            min-height: 40px;
            border: none;
            border-radius: 10px;
            background: #078d8a;
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 16px;
            font-size: 11px;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 3px 8px rgba(7, 141, 138, 0.15);
            transition: all 0.2s ease;
        }

        .btn-detection:hover {
            background: #067b79;
            transform: translateY(-1px);
            box-shadow: 0 5px 12px rgba(7, 141, 138, 0.2);
        }

        .btn-detection svg {
            width: 15px;
            height: 15px;
            fill: none;
            stroke: currentColor;
            stroke-width: 1.8;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .simulation-note {
            text-align: center;
            color: #9aa8aa;
            font-size: 9.5px;
            margin-top: -6px;
        }

        .result-status {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 12px;
            border-radius: 12px;
            background: #f8faf9;
            border: 1px solid #edf1f1;
        }

        .result-status-icon {
            width: 30px;
            height: 30px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: #f1f5f5;
            color: #94a3b8;
            border: 1px solid #e2e8f0;
        }

        .result-status-icon svg {
            width: 16px;
            height: 16px;
            fill: none;
            stroke: currentColor;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .result-status h3 {
            font-size: 14px;
            line-height: 1.2;
            color: #64748b;
            font-weight: 700;
        }

        .result-status p {
            margin-top: 3px;
            color: #94a3b8;
            font-size: 10px;
        }

        .percentage-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
        }

        .percentage-card {
            min-height: 76px;
            padding: 12px;
            border-radius: 12px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            border: 1px solid;
        }

        .percentage-card.fresh {
            background: #f8fafb;
            border-color: #edf1f1;
        }

        .percentage-card.not-fresh {
            background: #f8fafb;
            border-color: #edf1f1;
        }

        .percentage-label {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 10px;
            font-weight: 500;
            color: #607477;
        }

        .dot {
            width: 7px;
            height: 7px;
            display: inline-block;
            border-radius: 50%;
        }

        .dot.teal {
            background: #cbd5e1;
        }

        .gray-dot {
            background: #cbd5e1;
        }

        .percentage-value {
            display: flex;
            align-items: baseline;
            margin-top: 6px;
        }

        .percentage-value span {
            font-size: 30px;
            line-height: 1;
            font-weight: 800;
            letter-spacing: -0.04em;
            color: #94a3b8;
        }

        .percentage-value small {
            font-size: 16px;
            font-weight: 700;
            margin-left: 2px;
            color: #94a3b8;
        }

        .distribution-section {
            padding-top: 2px;
        }

        .distribution-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-bottom: 10px;
            border-bottom: 1px solid #edf1f1;
        }

        .distribution-header h3 {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #32484b;
        }

        .distribution-header span {
            font-size: 9px;
            color: #8b9b9d;
        }

        .distribution-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            padding: 14px 4px 4px;
        }

        .donut-wrapper {
            width: 130px;
            height: 130px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .donut-chart {
            width: 122px;
            height: 122px;
            border-radius: 50%;
            background: conic-gradient(#e2e8f0 0deg, #e2e8f0 360deg);
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .donut-chart::before {
            content: "";
            position: absolute;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: #ffffff;
        }

        .donut-center {
            position: relative;
            z-index: 2;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .donut-center strong {
            color: #94a3b8;
            font-size: 24px;
            line-height: 1;
            font-weight: 800;
        }

        .donut-center span {
            margin-top: 4px;
            color: #8a9b9e;
            font-size: 9px;
            font-weight: 500;
        }

        .distribution-list {
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .distribution-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            color: #647679;
            font-size: 10px;
        }

        .distribution-name {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .legend-dot {
            width: 8px;
            height: 8px;
            border-radius: 2px;
            display: inline-block;
            background: #cbd5e1;
        }

        .distribution-item strong {
            color: #94a3b8;
            font-size: 10px;
        }

        .distribution-divider {
            height: 1px;
            width: 100%;
            background: #edf1f1;
            margin: 2px 0;
        }

        .distribution-item.total {
            color: #849497;
        }

        .disclaimer {
            display: flex;
            align-items: flex-start;
            gap: 8px;
            padding: 10px 12px;
            border-radius: 10px;
            background: #f7f9f9;
            border: 1px solid #edf1f1;
        }

        .disclaimer svg {
            width: 14px;
            height: 14px;
            flex-shrink: 0;
            margin-top: 1px;
            fill: none;
            stroke: #94a4a6;
            stroke-width: 1.7;
            stroke-linecap: round;
        }

        .disclaimer p {
            color: #849497;
            font-size: 9px;
            line-height: 1.5;
        }

        .guidance-card {
            margin-top: 20px;
            background: #ffffff;
            border: 1px solid rgba(226, 232, 240, 0.9);
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 1px 3px rgba(15, 23, 42, 0.02);
        }

        .guidance-card > h3 {
            color: #243c3f;
            font-size: 12px;
            font-weight: 700;
            margin-bottom: 16px;
            letter-spacing: -0.01em;
        }

        .guidance-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 24px;
        }

        @media (max-width: 768px) {
            .guidance-grid {
                grid-template-columns: 1fr;
                gap: 16px;
            }
        }

        .guidance-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }

        .guidance-icon {
            width: 32px;
            height: 32px;
            flex-shrink: 0;
            border-radius: 8px;
            background: #eff9f8;
            border: 1px solid #dcefed;
            color: #0a817e;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .guidance-icon svg {
            width: 16px;
            height: 16px;
            fill: none;
            stroke: currentColor;
            stroke-width: 1.8;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .guidance-item h4 {
            font-size: 11px;
            font-weight: 700;
            color: #253d40;
        }

        .guidance-item p {
            margin-top: 3px;
            font-size: 10px;
            color: #7b8e91;
            line-height: 1.45;
        }
    </style>
</head>
<body>

    <header class="main-header">
        <div class="header-title">
            Deteksi Kesegaran Mata Ikan
        </div>
        <button type="button" class="btn-keluar">
            Keluar
        </button>
    </header>

    <main class="main-container">

        <div class="page-heading">
            <div>
                <h1>Deteksi kesegaran mata ikan</h1>
                <p>
                    Unggah gambar mata ikan, jalankan deteksi, lalu lihat komposisi hasilnya.
                </p>
            </div>
            
        </div>

        <div class="content-grid">

            <!-- Card 01: Input Gambar -->
            <section class="card input-card">
                <div class="card-header">
                    <div class="card-title-wrapper">
                        <span class="number-badge">01</span>
                        <h2>Input gambar</h2>
                    </div>
                    <span class="status-badge gray">
                        Belum ada gambar
                    </span>
                </div>

                <!-- Empty Image Preview Area -->
                <div class="image-preview">
                    <div class="empty-preview">
                        <div class="empty-icon">
                            <svg viewBox="0 0 24 24">
                                <rect x="3" y="3" width="18" height="18" rx="2"></rect>
                                <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                <path d="M21 15l-5-5L5 21"></path>
                            </svg>
                        </div>
                        <span>Pratinjau gambar</span>
                    </div>

                    <div class="preview-label">
                        <svg viewBox="0 0 24 24">
                            <rect x="3" y="3" width="18" height="18" rx="2"></rect>
                            <circle cx="8.5" cy="8.5" r="1.5"></circle>
                            <path d="M21 15l-5-5L5 21"></path>
                        </svg>
                        PRATINJAU
                    </div>

                    <button type="button" class="preview-expand" aria-label="Perbesar Pratinjau">
                        <svg viewBox="0 0 24 24">
                            <path d="M4 8V4m0 0h4M4 4l5 5"></path>
                            <path d="M20 8V4m0 0h-4M20 4l-5 5"></path>
                            <path d="M4 16v4m0 0h4M4 20l5-5"></path>
                            <path d="M20 16v4m0 0h-4M20 20l-5-5"></path>
                        </svg>
                    </button>
                </div>

                <!-- Empty File Info -->
                <div class="file-information">
                    <div class="file-left">
                        <div class="file-icon">
                            <svg viewBox="0 0 24 24">
                                <path d="M9 12h6"></path>
                                <path d="M9 16h6"></path>
                                <path d="M7 3h5.5L19 9.5V19a2 2 0 01-2 2H7a2 2 0 01-2-2V5a2 2 0 012-2z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="file-name">Belum ada gambar</p>
                            <p class="file-size">JPG / JPEG / PNG • Maksimal 5 MB</p>
                        </div>
                    </div>
                    <div class="file-check">
                        <svg viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="9"></circle>
                            <path d="M9 12l2 2 4-4"></path>
                        </svg>
                    </div>
                </div>

                <!-- Input Buttons -->
                <label for="inputGambar" class="input-file-button">
                    <svg viewBox="0 0 24 24">
                        <path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1"></path>
                        <path d="M12 4v12"></path>
                        <path d="M8 8l4-4 4 4"></path>
                    </svg>
                    <span>Input File</span>
                </label>

                <input type="file" id="inputGambar" name="gambar" accept=".jpg,.jpeg,.png" hidden>

                <p class="file-hint">
                    JPG, JPEG, atau PNG • Maksimal 5 MB
                </p>

                <button type="button" class="btn-detection">
                    <svg viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="3"></circle>
                        <path d="M3 7V5a2 2 0 012-2h2"></path>
                        <path d="M17 3h2a2 2 0 012 2v2"></path>
                        <path d="M21 17v2a2 2 0 01-2 2h-2"></path>
                        <path d="M7 21H5a2 2 0 01-2-2v-2"></path>
                    </svg>
                    <span>Deteksi</span>
                </button>

              
            </section>

            <!-- Card 02: Hasil Deteksi (Empty State) -->
            <section class="card result-card">
                <div class="card-header">
                    <div class="card-title-wrapper">
                        <span class="number-badge">02</span>
                        <h2>Hasil deteksi</h2>
                    </div>
                    <span class="status-badge neutral">
                        Belum ada hasil
                    </span>
                </div>

                <!-- Result Status Header Empty -->
                <div class="result-status">
                    <div class="result-status-icon">
                        <svg viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="9"></circle>
                            <path d="M12 8v4"></path>
                            <path d="M12 16h.01"></path>
                        </svg>
                    </div>
                    <div>
                        <h3>Belum Terdeteksi</h3>
                        <p>Unggah gambar mata ikan lalu tekan tombol Deteksi.</p>
                    </div>
                </div>

                <!-- Empty Percentage Cards Grid -->
                <div class="percentage-grid">
                    <!-- Segar Empty -->
                    <div class="percentage-card fresh">
                        <div class="percentage-label">
                            <span class="dot teal"></span>
                            <span>Mata Segar</span>
                        </div>
                        <div class="percentage-value">
                            <span>--</span>
                            <small>%</small>
                        </div>
                    </div>

                    <!-- Tidak Segar Empty -->
                    <div class="percentage-card not-fresh">
                        <div class="percentage-label">
                            <span class="dot gray-dot"></span>
                            <span>Tidak Segar</span>
                        </div>
                        <div class="percentage-value">
                            <span>--</span>
                            <small>%</small>
                        </div>
                    </div>
                </div>

                <!-- Distribution Section Empty -->
                <div class="distribution-section">
                    <div class="distribution-header">
                        <h3>Distribusi hasil deteksi</h3>
                        <span>Komposisi kelas (%)</span>
                    </div>

                    <div class="distribution-content">
                        <!-- Empty Donut Chart -->
                        <div class="donut-wrapper">
                            <div class="donut-chart">
                                <div class="donut-center">
                                    <strong>--%</strong>
                                    <span>Mata Segar</span>
                                </div>
                            </div>
                        </div>

                        <!-- Empty Legend List -->
                        <div class="distribution-list">
                            <div class="distribution-item">
                                <div class="distribution-name">
                                    <span class="legend-dot"></span>
                                    <span>Mata Segar</span>
                                </div>
                                <strong>--%</strong>
                            </div>

                            <div class="distribution-item">
                                <div class="distribution-name">
                                    <span class="legend-dot"></span>
                                    <span>Tidak Segar</span>
                                </div>
                                <strong>--%</strong>
                            </div>

                            <div class="distribution-divider"></div>

                            <div class="distribution-item total">
                                <span>Total</span>
                                <strong>--%</strong>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Disclaimer -->
                <div class="disclaimer">
                    <svg viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="9"></circle>
                        <path d="M12 8v4"></path>
                        <path d="M12 16h.01"></path>
                    </svg>
                    <p>
                        Persentase ini hanya ilustrasi antarmuka. Bukan penilaian kelayakan konsumsi atau pengganti pemeriksaan mutu ikan.
                    </p>
                </div>
            </section>

        </div>

        <section class="guidance-card">
            <h3>Siapkan gambar yang jelas</h3>

            <div class="guidance-grid">
                <!-- Tips 1 -->
                <div class="guidance-item">
                    <div class="guidance-icon">
                        <svg viewBox="0 0 24 24">
                            <path d="M3 7V5a2 2 0 012-2h2"></path>
                            <path d="M17 3h2a2 2 0 012 2v2"></path>
                            <path d="M21 17v2a2 2 0 01-2 2h-2"></path>
                            <path d="M7 21H5a2 2 0 01-2-2v-2"></path>
                            <circle cx="12" cy="12" r="2.5"></circle>
                        </svg>
                    </div>
                    <div>
                        <h4>Fokus pada mata ikan</h4>
                        <p>Ambil dari samping agar seluruh bagian mata terlihat.</p>
                    </div>
                </div>

                <!-- Tips 2 -->
                <div class="guidance-item">
                    <div class="guidance-icon">
                        <svg viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="4"></circle>
                            <path d="M12 2v2"></path>
                            <path d="M12 20v2"></path>
                            <path d="M2 12h2"></path>
                            <path d="M20 12h2"></path>
                            <path d="M4.93 4.93l1.41 1.41"></path>
                            <path d="M17.66 17.66l1.41 1.41"></path>
                            <path d="M4.93 19.07l1.41-1.41"></path>
                            <path d="M17.66 6.34l1.41-1.41"></path>
                        </svg>
                    </div>
                    <div>
                        <h4>Gunakan cahaya merata</h4>
                        <p>Hindari bayangan pekat dan pantulan lampu berlebih.</p>
                    </div>
                </div>

                <!-- Tips 3 -->
                <div class="guidance-item">
                    <div class="guidance-icon">
                        <svg viewBox="0 0 24 24">
                            <rect x="3" y="3" width="18" height="18" rx="2"></rect>
                            <circle cx="8.5" cy="8.5" r="1.5"></circle>
                            <path d="M21 15l-5-5L5 21"></path>
                        </svg>
                    </div>
                    <div>
                        <h4>Satu ikan, satu gambar</h4>
                        <p>Gunakan foto tajam tanpa filter atau objek penghalang.</p>
                    </div>
                </div>
            </div>
        </section>

    </main>

</body>
</html>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deteksi Kesegaran Mata Ikan</title>

    {{-- CSS TAMPILAN (dibawa langsung di dalam file ini) --}}
    <style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

/* ==========================================
   DETEKSI MATA IKAN - TAMPILAN (TEMA LAUT)
   Simpan di: public/css/deteksi.css
   Tanpa Tailwind, serasi dengan halaman lain
   ========================================== */

:root {
    --sea-deep: #052e4a;
    --sea-mid: #0a6a8f;
    --sea-bright: #1296c4;
    --aqua: #5eead4;
    --ink: #0f2a3a;
    --muted: #5b7384;
    --subtle: #7a93a3;
    --line: #e1ecf2;
    --line-strong: #c9dde7;
}

*,
*::before,
*::after {
    box-sizing: border-box;
}

html {
    -webkit-text-size-adjust: 100%;
}

body.dt-body {
    margin: 0;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    font-family: 'Plus Jakarta Sans', system-ui, -apple-system, 'Segoe UI', sans-serif;
    font-size: 14px;
    color: var(--ink);
    background: linear-gradient(160deg, #e8f6fa 0%, #f4fafc 50%, #e3f1f7 100%);
    background-attachment: fixed;
    -webkit-font-smoothing: antialiased;
}

h1, h2, h3, h4, h5, p, ul { margin: 0; padding: 0; }

button, input { font: inherit; }
button { cursor: pointer; }
svg { display: block; flex-shrink: 0; }

/* Dipakai oleh JavaScript untuk menyembunyikan / menampilkan elemen */
.hidden { display: none !important; }

/* Ukuran ikon SVG */
.dt-ico    { width: 16px; height: 16px; }
.dt-ico-sm { width: 14px; height: 14px; }
.dt-ico-md { width: 20px; height: 20px; }
.dt-ico-lg { width: 24px; height: 24px; }
.dt-ico-xl { width: 28px; height: 28px; }

/* ---------- HEADER ---------- */
.dt-header {
    position: relative;
    overflow: hidden;
    color: #ffffff;
    padding: 1rem 0 2.25rem;
    background:
        radial-gradient(circle at 15% 10%, rgba(94, 234, 212, 0.3), transparent 45%),
        linear-gradient(135deg, var(--sea-bright) 0%, var(--sea-mid) 55%, var(--sea-deep) 100%);
    box-shadow: 0 10px 24px -12px rgba(5, 46, 74, 0.5);
    animation: dtHeaderIn 0.7s ease-out both;
}

@keyframes dtHeaderIn {
    from { opacity: 0; transform: translateY(-16px); }
    to   { opacity: 1; transform: translateY(0); }
}

.dt-header-inner {
    position: relative;
    z-index: 3;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 1.5rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
}

.dt-header-left {
    display: flex;
    align-items: center;
    gap: 1rem;
    min-width: 0;
}

.dt-header-icon {
    padding: 0.55rem;
    border-radius: 12px;
    background: rgba(255, 255, 255, 0.2);
    border: 1px solid rgba(255, 255, 255, 0.3);
    backdrop-filter: blur(4px);
    animation: dtFloat 5s ease-in-out infinite;
}

@keyframes dtFloat {
    0%, 100% { transform: translateY(0); }
    50%      { transform: translateY(-5px); }
}

.dt-header-title {
    font-size: 1.25rem;
    font-weight: 800;
    letter-spacing: -0.02em;
    line-height: 1.2;
}

.dt-header-sub {
    margin-top: 2px;
    font-size: 0.8125rem;
    color: rgba(255, 255, 255, 0.8);
}

.dt-bubbles {
    position: absolute;
    inset: 0;
    z-index: 1;
    pointer-events: none;
}

.dt-bubbles span {
    position: absolute;
    bottom: -20px;
    left: var(--x);
    width: var(--s);
    height: var(--s);
    border-radius: 50%;
    background: radial-gradient(circle at 30% 30%, rgba(255, 255, 255, 0.75), rgba(255, 255, 255, 0.1));
    border: 1px solid rgba(255, 255, 255, 0.4);
    animation: dtBubble var(--d) var(--delay) linear infinite;
}

@keyframes dtBubble {
    0%   { transform: translateY(0) translateX(0); opacity: 0; }
    15%  { opacity: 1; }
    100% { transform: translateY(-120px) translateX(12px); opacity: 0; }
}

.dt-waves {
    position: absolute;
    left: 0;
    right: 0;
    bottom: 0;
    height: 30px;
    z-index: 2;
    overflow: hidden;
    pointer-events: none;
}

.dt-wave {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 200%;
    height: 100%;
}

.dt-wave-back  { fill: rgba(255, 255, 255, 0.18); animation: dtWave 10s linear infinite; }
.dt-wave-front { fill: rgba(255, 255, 255, 0.3); height: 70%; animation: dtWave 6s linear infinite reverse; }

@keyframes dtWave {
    from { transform: translateX(0); }
    to   { transform: translateX(-50%); }
}

/* Tombol keluar di header */
.dt-logout {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.45rem 0.9rem;
    font-size: 0.8125rem;
    font-weight: 600;
    color: #ffffff;
    background: rgba(255, 255, 255, 0.16);
    border: 1px solid rgba(255, 255, 255, 0.35);
    border-radius: 10px;
    transition: background-color 0.2s, border-color 0.2s;
}

.dt-logout:hover {
    background: rgba(255, 255, 255, 0.28);
    border-color: rgba(255, 255, 255, 0.6);
}

.dt-logout:focus-visible {
    outline: 2px solid #ffffff;
    outline-offset: 2px;
}

/* ---------- KONTEN UTAMA ---------- */
.dt-main {
    width: 100%;
    max-width: 1200px;
    margin: 0 auto;
    padding: 1.75rem 1.5rem 2rem;
    flex-grow: 1;
}

.dt-title-row {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.dt-title-row h2 {
    font-size: 1.6rem;
    font-weight: 800;
    letter-spacing: -0.02em;
    color: var(--ink);
}

.dt-title-row p {
    margin-top: 4px;
    font-size: 0.875rem;
    color: var(--muted);
}

/* ---------- LANGKAH 1-2-3 ---------- */
.dt-steps {
    display: flex;
    align-items: center;
    gap: 1.25rem;
    font-size: 0.8125rem;
    font-weight: 600;
}

.dt-step {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #8aa3b3;
}

.dt-step-num {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
    font-weight: 700;
    background: #dbe9f0;
    color: var(--muted);
}

.dt-step.is-active { color: var(--ink); }
.dt-step.is-active .dt-step-num { background: var(--sea-mid); color: #ffffff; }

/* ---------- GRID DAN KARTU ---------- */
.dt-grid {
    display: grid;
    grid-template-columns: 7fr 5fr;
    gap: 1.25rem;
}

.dt-card {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    background: #ffffff;
    border: 1px solid var(--line);
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 1px 3px rgba(5, 46, 74, 0.06);
}

.dt-card-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 0.75rem;
    margin-bottom: 0.25rem;
}

.dt-card-title {
    font-size: 1.05rem;
    font-weight: 700;
    color: var(--ink);
}

.dt-card-desc {
    font-size: 0.75rem;
    color: var(--subtle);
    margin-bottom: 1rem;
}

.dt-chip {
    padding: 0.25rem 0.75rem;
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--muted);
    background: #f1f7fa;
    border: 1px solid var(--line);
    border-radius: 999px;
    transition: background-color 0.2s;
}

button.dt-chip:hover { background: #e4eff5; }

.dt-chip-teal {
    color: var(--sea-mid);
    background: #e4f3f9;
    border-color: #cfe5ee;
    font-size: 0.6875rem;
}

/* ---------- AREA UNGGAH GAMBAR ---------- */
.dt-drop {
    position: relative;
    width: 100%;
    height: 16rem;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background: #f3f8fb;
    border: 2px dashed var(--line-strong);
    border-radius: 12px;
    cursor: pointer;
    transition: border-color 0.2s, background-color 0.2s;
}

.dt-drop:hover {
    border-color: var(--sea-bright);
    background: #eef7fb;
}

.dt-drop img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

#previewImg {
    position: absolute;
    inset: 0;
}

.dt-placeholder {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    padding: 1.5rem;
}

.dt-placeholder-icon {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 0.75rem;
    background: #dbe9f0;
    color: #7a93a3;
    transition: background-color 0.2s, color 0.2s;
}

.dt-drop:hover .dt-placeholder-icon { background: #d3eaf3; color: var(--sea-mid); }

.dt-placeholder strong {
    font-size: 0.875rem;
    font-weight: 600;
    color: #36566b;
}

.dt-drop:hover .dt-placeholder strong { color: var(--sea-mid); }

.dt-placeholder span {
    margin-top: 4px;
    font-size: 0.75rem;
    color: var(--subtle);
}

.dt-preview-badge {
    position: absolute;
    top: 12px;
    left: 12px;
    display: flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.25rem 0.6rem;
    font-size: 0.75rem;
    color: #ffffff;
    background: rgba(5, 46, 74, 0.65);
    backdrop-filter: blur(6px);
    border-radius: 6px;
}

/* ---------- INFO FILE ---------- */
.dt-fileinfo {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-top: 1rem;
    padding: 0.75rem;
    overflow: hidden;
    background: #f6fafc;
    border: 1px solid var(--line);
    border-radius: 12px;
}

.dt-fileinfo-icon {
    padding: 0.5rem;
    border-radius: 8px;
    background: #e4eef4;
    color: var(--muted);
    flex-shrink: 0;
}

.dt-fileinfo-text { min-width: 0; }

.dt-fileinfo-name {
    font-size: 0.75rem;
    font-weight: 600;
    color: #1f3b4d;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.dt-fileinfo-meta {
    font-size: 0.6875rem;
    color: var(--subtle);
}

/* ---------- PESAN ERROR ---------- */
.dt-error {
    margin-top: 1rem;
    padding: 0.75rem;
    font-size: 0.75rem;
    color: #b91c1c;
    background: #fef2f2;
    border: 1px solid #fecaca;
    border-radius: 12px;
}

/* ---------- TOMBOL ---------- */
.dt-actions {
    display: flex;
    gap: 0.75rem;
    margin-top: 1rem;
}

.dt-btn {
    flex: 1;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.65rem 1rem;
    font-size: 0.8125rem;
    font-weight: 600;
    border: 1px solid transparent;
    border-radius: 10px;
    transition: background-color 0.2s, border-color 0.2s, color 0.2s;
}

.dt-btn:focus-visible {
    outline: 3px solid rgba(18, 150, 196, 0.35);
    outline-offset: 2px;
}

.dt-btn-outline {
    color: #36566b;
    background: #ffffff;
    border-color: var(--line-strong);
}

.dt-btn-outline:hover {
    color: var(--sea-mid);
    background: #eef7fb;
    border-color: var(--sea-bright);
}

.dt-btn-primary {
    color: #ffffff;
    background: var(--sea-mid);
    border-color: var(--sea-mid);
}

.dt-btn-primary:hover {
    background: var(--sea-deep);
    border-color: var(--sea-deep);
}

.dt-btn:disabled,
.dt-btn.is-disabled {
    opacity: 0.65;
    cursor: not-allowed;
}

.dt-card-foot {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 0.75rem;
    margin-top: 1rem;
    padding-top: 0.75rem;
    font-size: 0.6875rem;
    color: var(--subtle);
    border-top: 1px solid var(--line);
}

/* ---------- HASIL DETEKSI ---------- */
.dt-banner {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    margin-bottom: 1.25rem;
    padding: 0.875rem;
    border: 1px solid;
    border-radius: 12px;
}

.dt-banner-icon {
    width: 24px;
    height: 24px;
    margin-top: 2px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #ffffff;
    flex-shrink: 0;
}

.dt-banner h4 { font-size: 0.875rem; font-weight: 700; }
.dt-banner p  { margin-top: 2px; font-size: 0.6875rem; }

.dt-banner.is-fresh { background: #eaf7f4; border-color: #bfe5dc; }
.dt-banner.is-fresh .dt-banner-icon { background: #14a38b; }
.dt-banner.is-fresh h4 { color: #0f5f55; }
.dt-banner.is-fresh p  { color: #0f766e; }

.dt-banner.is-stale { background: #fff4e8; border-color: #fbd5a5; }
.dt-banner.is-stale .dt-banner-icon { background: #f97316; }
.dt-banner.is-stale h4 { color: #8a3f0a; }
.dt-banner.is-stale p  { color: #b45309; }

.dt-warning {
    margin-bottom: 1.25rem;
    padding: 0.5rem 0.75rem;
    font-size: 0.6875rem;
    color: #92400e;
    background: #fffbeb;
    border: 1px solid #fde68a;
    border-radius: 8px;
}

.dt-scores {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0.75rem;
    margin-bottom: 1.5rem;
}

.dt-score {
    padding: 0.875rem;
    background: #f6fafc;
    border: 1px solid var(--line);
    border-radius: 12px;
}

.dt-score-label {
    display: flex;
    align-items: center;
    gap: 0.4rem;
    margin-bottom: 4px;
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--muted);
}

.dt-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
}

.dt-dot-fresh { background: #14a38b; }
.dt-dot-stale { background: #f59e0b; }

.dt-score-value {
    font-size: 1.9rem;
    font-weight: 800;
    letter-spacing: -0.02em;
    color: #36566b;
    font-variant-numeric: tabular-nums;
}

.dt-score-value small {
    font-size: 1.1rem;
    font-weight: 700;
}

.dt-score.is-fresh { background: #eaf7f4; border-color: #bfe5dc; }
.dt-score.is-fresh .dt-score-label { color: #0f5f55; }
.dt-score.is-fresh .dt-score-value { color: #0b4a42; }

.dt-score.is-stale { background: #fff4e8; border-color: #fbd5a5; }
.dt-score.is-stale .dt-score-label { color: #8a3f0a; }
.dt-score.is-stale .dt-score-value { color: #6e3008; }

/* ---------- KONDISI KOSONG ---------- */
.dt-empty {
    margin: auto 0;
    padding: 3rem 1rem;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    background: #f8fbfd;
    border: 1px dashed var(--line-strong);
    border-radius: 12px;
}

.dt-empty-icon {
    width: 56px;
    height: 56px;
    margin-bottom: 0.75rem;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #e8f1f6;
    color: #9bb2c0;
}

.dt-empty h4 { font-size: 0.875rem; font-weight: 700; color: #36566b; }
.dt-empty p  { margin-top: 4px; max-width: 20rem; font-size: 0.75rem; color: var(--subtle); }

.dt-note {
    display: flex;
    align-items: flex-start;
    gap: 0.5rem;
    margin-top: 1.5rem;
    padding-top: 0.75rem;
    font-size: 0.6875rem;
    line-height: 1.5;
    color: var(--subtle);
    border-top: 1px solid var(--line);
}

.dt-note svg { margin-top: 2px; }

/* ---------- TIPS ---------- */
.dt-tips {
    margin-top: 1.5rem;
    padding: 1.5rem;
    background: #ffffff;
    border: 1px solid var(--line);
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(5, 46, 74, 0.06);
}

.dt-tips > h4 {
    margin-bottom: 1rem;
    font-size: 0.9375rem;
    font-weight: 700;
}

.dt-tips-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1rem;
}

.dt-tip {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    padding: 0.875rem;
    background: #f6fafc;
    border: 1px solid var(--line);
    border-radius: 12px;
}

.dt-tip-icon {
    padding: 0.5rem;
    border-radius: 8px;
    background: #e4f3f9;
    color: var(--sea-mid);
    flex-shrink: 0;
}

.dt-tip h5 { font-size: 0.75rem; font-weight: 700; color: #1f3b4d; }
.dt-tip p  { margin-top: 2px; font-size: 0.6875rem; line-height: 1.5; color: var(--subtle); }

/* ---------- FOOTER ---------- */
.dt-footer {
    margin-top: 2rem;
    padding: 1rem;
    text-align: center;
    font-size: 0.75rem;
    color: var(--subtle);
    background: #ffffff;
    border-top: 1px solid var(--line);
}

/* ---------- JENDELA KAMERA ---------- */
.dt-modal {
    display: none;
    position: fixed;
    inset: 0;
    z-index: 60;
    align-items: center;
    justify-content: center;
    padding: 1rem;
    background: rgba(5, 46, 74, 0.65);
    backdrop-filter: blur(4px);
}

.dt-modal.flex { display: flex; }

.dt-modal-card {
    width: 100%;
    max-width: 36rem;
    max-height: 96vh;
    overflow-y: auto;
    padding: 1.25rem;
    background: #ffffff;
    border-radius: 16px;
    box-shadow: 0 30px 60px -20px rgba(5, 46, 74, 0.5);
}

.dt-modal-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 0.75rem;
}

.dt-modal-head h3 { font-size: 1rem; font-weight: 800; }

.dt-close {
    padding: 0 0.25rem;
    font-size: 1.6rem;
    line-height: 1;
    color: #9bb2c0;
    background: transparent;
    border: none;
    transition: color 0.2s;
}

.dt-close:hover { color: #dc2626; }

.dt-cam-frame {
    position: relative;
    width: min(100%, 56vh);
    aspect-ratio: 1 / 1;
    margin: 0 auto;
    overflow: hidden;
    background: #000000;
    border-radius: 12px;
}

.dt-cam-frame video {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.dt-cam-overlay {
    position: absolute;
    inset: 0;
    pointer-events: none;
}

.dt-cam-ring {
    position: absolute;
    left: 20%;
    top: 20%;
    width: 60%;
    height: 60%;
    border-radius: 50%;
    border: 4px dashed rgba(255, 255, 255, 0.9);
    box-shadow: 0 0 0 9999px rgba(0, 0, 0, 0.38);
}

.dt-cam-status {
    position: absolute;
    top: 12px;
    left: 50%;
    transform: translateX(-50%);
    max-width: 92%;
    padding: 0.25rem 0.75rem;
    overflow: hidden;
    font-size: 0.75rem;
    font-weight: 600;
    white-space: nowrap;
    text-overflow: ellipsis;
    color: #ffffff;
    border-radius: 999px;
}

.dt-cam-status.is-gray  { background: #334155; }
.dt-cam-status.is-red   { background: #dc2626; }
.dt-cam-status.is-amber { background: #d97706; }
.dt-cam-status.is-green { background: #059669; }

.dt-cam-hint {
    position: absolute;
    left: 0;
    right: 0;
    bottom: 12px;
    padding: 0 0.5rem;
    text-align: center;
    font-size: 0.6875rem;
    color: rgba(255, 255, 255, 0.9);
}

.dt-zoom { margin-top: 0.75rem; }

.dt-zoom label {
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: 0.75rem;
    color: var(--muted);
}

.dt-zoom input[type="range"] {
    width: 100%;
    accent-color: var(--sea-mid);
}

.dt-cam-tips {
    margin-top: 0.75rem;
    padding-left: 1rem;
    font-size: 0.6875rem;
    line-height: 1.6;
    color: var(--muted);
}

.dt-modal-actions {
    display: flex;
    gap: 0.75rem;
    margin-top: 1rem;
}

/* ---------- LAYAR KECIL ---------- */
@media (max-width: 991px) {
    .dt-grid { grid-template-columns: 1fr; }
}

@media (max-width: 767px) {
    .dt-tips-grid { grid-template-columns: 1fr; }
    .dt-header-sub { display: none; }
}

@media (max-width: 640px) {
    .dt-header-inner,
    .dt-main { padding-left: 1rem; padding-right: 1rem; }
    .dt-header-title { font-size: 1.1rem; }
    .dt-title-row h2 { font-size: 1.35rem; }
    .dt-card, .dt-tips { padding: 1.1rem; }
    .dt-drop { height: 20rem; }
    .dt-actions,
    .dt-modal-actions { flex-direction: column; }
    .dt-steps { gap: 0.9rem; }
    .dt-card-foot span:last-child { display: none; }
}

@media (min-width: 641px) {
    .dt-drop { height: 20rem; }
}


/* ==========================================
   MODE 1 LAYAR (LAPTOP / DESKTOP)
   Semua komponen muat dalam satu layar tanpa scroll.
   Di HP / tablet / layar pendek tetap bisa di-scroll seperti biasa.
   ========================================== */
@media (min-width: 992px) and (min-height: 600px) {

    body.dt-body {
        height: 100vh;
        height: 100dvh;
        overflow: hidden;
    }

    /* Header diringkas */
    .dt-header { padding: 0.6rem 0 1.6rem; }
    .dt-header-icon { padding: 0.4rem; }
    .dt-header-title { font-size: 1.1rem; }
    .dt-header-sub { font-size: 0.75rem; }
    .dt-waves { height: 20px; }

    /* Konten utama mengisi sisa tinggi layar */
    .dt-main {
        display: flex;
        flex-direction: column;
        flex: 1;
        min-height: 0;
        padding-top: 0.9rem;
        padding-bottom: 0.6rem;
    }

    .dt-title-row { margin-bottom: 0.75rem; gap: 0.5rem 1rem; }
    .dt-title-row h2 { font-size: 1.2rem; }
    .dt-title-row p { margin-top: 2px; font-size: 0.78rem; }

    /* Dua kartu berdampingan mengisi ruang yang tersedia */
    .dt-grid {
        flex: 1;
        min-height: 0;
        grid-template-rows: minmax(0, 1fr);
        gap: 1rem;
    }

    .dt-card {
        min-height: 0;
        padding: 1rem 1.15rem;
        overflow-y: auto;
    }

    .dt-card > div:first-child {
        display: flex;
        flex-direction: column;
        flex: 1;
        min-height: 0;
    }

    .dt-card-desc { margin-bottom: 0.6rem !important; }
    .dt-card-title { font-size: 1rem; }

    /* Kartu kiri: area gambar melebar, info file + tombol dalam satu baris */
    #uploadForm {
        display: grid;
        grid-template-columns: minmax(0, 1fr) auto;
        grid-template-rows: minmax(0, 1fr);
        column-gap: 0.75rem;
        flex: 1;
        min-height: 0;
    }

    #uploadForm .dt-drop {
        grid-column: 1 / -1;
        order: 1;
        height: auto;
        min-height: 0;
    }

    #uploadForm .dt-error {
        grid-column: 1 / -1;
        order: 2;
        margin-top: 0.6rem;
        padding: 0.5rem 0.75rem;
    }

    #uploadForm .dt-fileinfo {
        order: 3;
        margin-top: 0.6rem;
        padding: 0.45rem 0.65rem;
    }

    #uploadForm .dt-actions {
        order: 4;
        margin-top: 0.6rem;
        gap: 0.5rem;
    }

    #uploadForm .dt-btn {
        flex: none;
        padding: 0.5rem 0.8rem;
        font-size: 0.78rem;
    }

    .dt-fileinfo-icon { padding: 0.35rem; }

    .dt-card-foot {
        margin-top: 0.5rem;
        padding-top: 0.4rem;
    }

    /* Kartu kanan: lebih ringkas */
    .dt-banner { margin-bottom: 0.75rem; padding: 0.65rem 0.75rem; }
    .dt-warning { margin-bottom: 0.75rem; padding: 0.4rem 0.65rem; }
    .dt-scores { margin-bottom: 0.5rem; gap: 0.6rem; }
    .dt-score { padding: 0.65rem 0.75rem; }
    .dt-score-value { font-size: 1.6rem; }
    .dt-empty { padding: 1.25rem 1rem; flex: 1; justify-content: center; }
    .dt-empty-icon { width: 48px; height: 48px; margin-bottom: 0.5rem; }
    .dt-note { margin-top: 0.6rem; padding-top: 0.5rem; }

    /* Tips diringkas jadi satu baris */
    .dt-tips {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-top: 0.75rem;
        padding: 0.6rem 1rem;
    }

    .dt-tips > h4 {
        margin: 0;
        width: 8.5rem;
        flex-shrink: 0;
        font-size: 0.8125rem;
        line-height: 1.3;
    }

    .dt-tips-grid { flex: 1; gap: 0.6rem; }
    .dt-tip { padding: 0.5rem 0.65rem; gap: 0.5rem; align-items: center; }
    .dt-tip-icon { padding: 0.35rem; }
    .dt-tips .dt-ico-md { width: 18px; height: 18px; }
    .dt-tip h5 { font-size: 0.75rem; }
    .dt-tip p {
        margin-top: 1px;
        font-size: 0.6875rem;
        line-height: 1.35;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* Footer tipis */
    .dt-footer { margin-top: 0; padding: 0.45rem 1rem; }
}

/* Hormati pengguna yang mematikan animasi di perangkatnya */
@media (prefers-reduced-motion: reduce) {
    *,
    *::before,
    *::after {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
    }
}

    </style>
</head>
<body class="dt-body">

    <!-- HEADER (JUDUL DI KIRI & TOMBOL KELUAR DI KANAN) -->
    <header class="dt-header">

        <!-- GELEMBUNG DAN GELOMBANG (VISUAL SAJA) -->
        <div class="dt-bubbles" aria-hidden="true">
            <span style="--x:6%;  --s:12px; --d:8s;  --delay:0s;"></span>
            <span style="--x:24%; --s:8px;  --d:6s;  --delay:2s;"></span>
            <span style="--x:47%; --s:16px; --d:9s;  --delay:1s;"></span>
            <span style="--x:68%; --s:9px;  --d:7s;  --delay:3s;"></span>
            <span style="--x:86%; --s:13px; --d:8.5s;--delay:0.6s;"></span>
        </div>
        <div class="dt-waves" aria-hidden="true">
            <svg class="dt-wave dt-wave-back" viewBox="0 0 1200 60" preserveAspectRatio="none">
                <path d="M0,30 C150,60 300,0 450,30 C600,60 750,0 900,30 C1050,60 1200,0 1350,30 L1350,60 L0,60 Z"/>
            </svg>
            <svg class="dt-wave dt-wave-front" viewBox="0 0 1200 60" preserveAspectRatio="none">
                <path d="M0,30 C150,0 300,60 450,30 C600,0 750,60 900,30 C1050,0 1200,60 1350,30 L1350,60 L0,60 Z"/>
            </svg>
        </div>

        <div class="dt-header-inner">

            <!-- Judul Halaman Kiri -->
            <div class="dt-header-left">
                <div class="dt-header-icon">
                    <svg class="dt-ico-md" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                </div>
                <div>
                    <h1 class="dt-header-title">Deteksi Mata Ikan</h1>
                    <p class="dt-header-sub">Estimasi kesegaran ikan dari foto mata.</p>
                </div>
            </div>

            <!-- Tombol Keluar (Logout) Kanan -->
            <div>
                <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="dt-logout">
                        <svg class="dt-ico" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        <span>Keluar</span>
                    </button>
                </form>
            </div>

        </div>
    </header>

    <!-- MAIN CONTENT CONTAINER -->
    <main class="dt-main">

        <!-- TITLE & STEPS -->
        <div class="dt-title-row">
            <div>
                <h2>Deteksi kesegaran mata ikan</h2>
                <p>Unggah foto mata ikan, lalu lihat estimasi kesegaran dalam satu analisis.</p>
            </div>

            <!-- Step Indicators -->
            <div class="dt-steps">
                <div class="dt-step is-active">
                    <span class="dt-step-num">1</span>
                    <span>Unggah gambar</span>
                </div>
                <div class="dt-step {{ isset($hasil) ? 'is-active' : '' }}">
                    <span class="dt-step-num">2</span>
                    <span>Deteksi</span>
                </div>
                <div class="dt-step {{ isset($hasil) ? 'is-active' : '' }}">
                    <span class="dt-step-num">3</span>
                    <span>Lihat hasil</span>
                </div>
            </div>
        </div>

        <!-- MAIN GRID SECTION -->
        <div class="dt-grid">

            <!-- LEFT PANEL: UPLOAD & PREVIEW -->
            <div class="dt-card">
                <div>
                    <div class="dt-card-head">
                        <h3 class="dt-card-title">Gambar mata ikan</h3>
                        <button type="button" class="dt-chip">Gambar contoh</button>
                    </div>
                    <p class="dt-card-desc">Gunakan foto close-up dengan mata terlihat jelas.</p>

                    <!-- IMAGE DISPLAY AREA (EMPTY STATE BY DEFAULT) -->
                    <form action="{{ route('deteksi.process') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                        @csrf
                        <div id="dropArea" onclick="if(event.target.id!=='fileInput') document.getElementById('fileInput').click()" class="dt-drop">

                            @if(isset($gambar_path))
                                <img src="{{ asset($gambar_path) }}" alt="Preview Mata Ikan">
                                <span class="dt-preview-badge">
                                    <svg class="dt-ico-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <span>Pratinjau gambar</span>
                                </span>
                            @else
                                <div id="placeholder" class="dt-placeholder">
                                    <div class="dt-placeholder-icon">
                                        <svg class="dt-ico-lg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <strong>Klik untuk mengunggah atau seret file ke sini</strong>
                                    <span>Belum ada gambar yang dipilih</span>
                                </div>
                            @endif

                            <input type="file" name="foto_mata" id="fileInput" class="hidden" accept="image/png, image/jpeg">
                            <img id="previewImg" src="" alt="Preview" class="hidden">
                        </div>

                        <!-- FILE INFO BAR -->
                        <div class="dt-fileinfo">
                            <div class="dt-fileinfo-icon">
                                <svg class="dt-ico-md" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </div>
                            <div class="dt-fileinfo-text">
                                <p id="namaFile" class="dt-fileinfo-name">
                                    {{ $nama_file ?? 'Belum ada file terpilih' }}
                                </p>
                                <p id="infoFile" class="dt-fileinfo-meta">
                                    {{ isset($ukuran_file) ? $ukuran_file : 'JPG, PNG • Maks 5 MB' }}
                                </p>
                            </div>
                        </div>

                        <!-- PESAN ERROR -->
                        @if($errors->any())
                            <div class="dt-error">
                                {{ $errors->first() }}
                            </div>
                        @endif

                        <!-- ACTION BUTTONS -->
                        <div class="dt-actions">
                            <button type="button" onclick="document.getElementById('fileInput').click()" class="dt-btn dt-btn-outline">
                                <svg class="dt-ico" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                <span>Input File</span>
                            </button>
                            <button type="button" id="btnKamera" class="dt-btn dt-btn-outline">
                                <svg class="dt-ico" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <span>Kamera</span>
                            </button>
                            <button type="submit" id="btnDeteksi" class="dt-btn dt-btn-primary">
                                <svg class="dt-ico" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <span>Deteksi</span>
                            </button>
                        </div>
                    </form>
                </div>

                <div class="dt-card-foot">
                    <span>Format JPG atau PNG · Maksimal 5 MB</span>
                    <span>Input File untuk mengganti gambar</span>
                </div>
            </div>

            <!-- RIGHT PANEL: DETECTION RESULTS -->
            <div class="dt-card">
                <div>
                    <div class="dt-card-head">
                        <h3 class="dt-card-title">Hasil deteksi</h3>
                        <span class="dt-chip dt-chip-teal">Hasil model</span>
                    </div>
                    <p class="dt-card-desc" style="margin-bottom: 1.25rem;">Estimasi berdasarkan gambar mata ikan.</p>

                    @if(isset($hasil))
                        @php
                            $segar = ($hasil['kategori_utama'] ?? 'segar') === 'segar';
                            $tertinggi = max($hasil['persen_segar'] ?? 0, $hasil['persen_tidak_segar'] ?? 0);
                        @endphp

                        @if($segar)
                            <!-- BANNER HASIL: SEGAR -->
                            <div class="dt-banner is-fresh">
                                <div class="dt-banner-icon">
                                    <svg class="dt-ico-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <div>
                                    <h4>Mata ikan terindikasi segar</h4>
                                    <p>Mata Segar adalah kategori dengan persentase tertinggi.</p>
                                </div>
                            </div>
                        @else
                            <!-- BANNER HASIL: TIDAK SEGAR -->
                            <div class="dt-banner is-stale">
                                <div class="dt-banner-icon">
                                    <svg class="dt-ico-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01"/></svg>
                                </div>
                                <div>
                                    <h4>Mata ikan terindikasi tidak segar</h4>
                                    <p>Tidak Segar adalah kategori dengan persentase tertinggi.</p>
                                </div>
                            </div>
                        @endif

                        @if($tertinggi < 65)
                            <p class="dt-warning">
                                Selisih kedua kategori kecil, jadi hasil ini kurang yakin. Coba foto ulang dengan fokus dan cahaya yang lebih baik.
                            </p>
                        @endif

                        <div class="dt-scores">
                            <!-- KARTU MATA SEGAR: berwarna hanya jika hasilnya segar -->
                            <div class="dt-score {{ $segar ? 'is-fresh' : '' }}">
                                <div class="dt-score-label">
                                    <span class="dt-dot dt-dot-fresh"></span>
                                    <span>Mata Segar</span>
                                </div>
                                <div class="dt-score-value">
                                    {{ $hasil['persen_segar'] ?? 0 }}<small>%</small>
                                </div>
                            </div>
                            <!-- KARTU TIDAK SEGAR: berwarna hanya jika hasilnya tidak segar -->
                            <div class="dt-score {{ !$segar ? 'is-stale' : '' }}">
                                <div class="dt-score-label">
                                    <span class="dt-dot dt-dot-stale"></span>
                                    <span>Tidak Segar</span>
                                </div>
                                <div class="dt-score-value">
                                    {{ $hasil['persen_tidak_segar'] ?? 0 }}<small>%</small>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- EMPTY STATE -->
                        <div class="dt-empty">
                            <div class="dt-empty-icon">
                                <svg class="dt-ico-xl" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                            </div>
                            <h4>Belum Ada Hasil Analisis</h4>
                            <p>Silakan unggah gambar mata ikan terlebih dahulu dan klik tombol "Deteksi" untuk melihat hasil analisis.</p>
                        </div>
                    @endif
                </div>

                <div class="dt-note">
                    <svg class="dt-ico" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Persentase menunjukkan estimasi kategori, bukan jaminan keamanan pangan. Lakukan pemeriksaan lanjutan.</span>
                </div>
            </div>

        </div>

        <!-- BOTTOM TIPS SECTION -->
        <div class="dt-tips">
            <h4>Tips untuk gambar yang lebih baik</h4>
            <div class="dt-tips-grid">
                <div class="dt-tip">
                    <div class="dt-tip-icon">
                        <svg class="dt-ico-md" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h0.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div>
                        <h5>Fokus pada mata</h5>
                        <p>Ambil foto dari dekat. Pastikan seluruh mata terlihat dan tidak buram.</p>
                    </div>
                </div>
                <div class="dt-tip">
                    <div class="dt-tip-icon">
                        <svg class="dt-ico-md" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </div>
                    <div>
                        <h5>Pencahayaan merata</h5>
                        <p>Gunakan cahaya yang cukup. Hindari bayangan dan pantulan berlebihan.</p>
                    </div>
                </div>
                <div class="dt-tip">
                    <div class="dt-tip-icon">
                        <svg class="dt-ico-md" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <h5>Satu gambar, satu ikan</h5>
                        <p>Gunakan satu mata ikan sebagai objek utama agar gambar mudah dianalisis.</p>
                    </div>
                </div>
            </div>
        </div>

    </main>

    <!-- FOOTER POLOS -->
    <footer class="dt-footer">
        © {{ date('Y') }} Deteksi Mata Ikan
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

    <!-- JENDELA KAMERA (dengan panduan fokus) -->
    <div id="kameraModal" class="dt-modal hidden">
        <div class="dt-modal-card">
            <div class="dt-modal-head">
                <h3>Ambil foto mata ikan</h3>
                <button type="button" id="btnTutupKamera" class="dt-close">&times;</button>
            </div>

            <!-- Area pratinjau berbentuk persegi = persis area yang akan difoto -->
            <div class="dt-cam-frame">
                <video id="kameraVideo" autoplay playsinline muted></video>

                <!-- Lingkaran bidik -->
                <div class="dt-cam-overlay">
                    <div id="kameraCincin" class="dt-cam-ring"></div>
                </div>

                <!-- Status kualitas -->
                <div id="kameraStatus" class="dt-cam-status is-gray">Menyiapkan kamera...</div>
                <p class="dt-cam-hint">Posisikan mata ikan di tengah lingkaran</p>
            </div>

            <!-- Zoom (muncul hanya jika kamera mendukung) -->
            <div id="zoomWrap" class="dt-zoom hidden">
                <label>
                    <span>Zoom</span><span id="zoomNilai">1x</span>
                </label>
                <input type="range" id="zoomRange" min="1" max="3" step="0.1" value="1">
            </div>

            <ul class="dt-cam-tips">
                <li>Jaga jarak sekitar 10 sampai 20 cm. Terlalu dekat membuat kamera sulit fokus.</li>
                <li>Tahan kamera stabil 1 sampai 2 detik sampai status berwarna hijau.</li>
                <li>Cahaya dari samping atau atas lebih baik. Hindari pantulan lampu langsung.</li>
            </ul>

            <div class="dt-modal-actions">
                <button type="button" id="btnGantiKamera" class="dt-btn dt-btn-outline">Ganti kamera</button>
                <button type="button" id="btnAmbilFoto" class="dt-btn dt-btn-primary">Ambil foto</button>
            </div>
            <canvas id="kameraCanvas" class="hidden"></canvas>
        </div>
    </div>

    <!-- LOGIKA: KAMERA + PANDUAN FOKUS -->
    <script>
    (function () {
        // ====== PENGATURAN (boleh disesuaikan) ======
        const AMBANG_TAJAM   = 40;    // makin besar = makin ketat soal ketajaman
        const AMBANG_GELAP   = 70;    // rata-rata kecerahan di bawah ini dianggap gelap (0-255)
        const AMBANG_TERANG  = 195;   // di atas ini dianggap terlalu terang
        const AMBANG_PANTUL  = 0.06;  // porsi piksel putih pekat; di atas ini dianggap ada pantulan
        const WAJIB_BAGUS    = false; // true = tombol "Ambil foto" mati sampai kualitas bagus
        const TAMPILKAN_ANGKA = location.search.includes('debug'); // tambah ?debug di alamat untuk melihat angka
        // ============================================

        const modal   = document.getElementById('kameraModal');
        const video   = document.getElementById('kameraVideo');
        const canvas  = document.getElementById('kameraCanvas');
        const status  = document.getElementById('kameraStatus');
        const cincin  = document.getElementById('kameraCincin');
        const btnAmbil = document.getElementById('btnAmbilFoto');
        const zoomWrap = document.getElementById('zoomWrap');
        const zoomRange = document.getElementById('zoomRange');
        const zoomNilai = document.getElementById('zoomNilai');
        const inputFile = document.getElementById('fileInput');

        const N = 160;                                  // ukuran kecil untuk analisis
        const ca = document.createElement('canvas');
        ca.width = N; ca.height = N;
        const cx = ca.getContext('2d', { willReadFrequently: true });

        let stream = null, track = null, timer = null;
        let arah = 'environment';                       // kamera belakang dulu (di HP)
        let tajamHalus = null;

        const WARNA = {
            merah:  { chip: 'is-red',   cincin: '#ef4444' },
            kuning: { chip: 'is-amber', cincin: '#f59e0b' },
            hijau:  { chip: 'is-green', cincin: '#10b981' },
            abu:    { chip: 'is-gray',  cincin: 'rgba(255,255,255,.9)' },
        };

        function tampilStatus(warna, teks) {
            status.className = 'dt-cam-status ' + WARNA[warna].chip;
            status.textContent = teks;
            cincin.style.borderColor = WARNA[warna].cincin;
            const bagus = (warna === 'hijau');
            btnAmbil.textContent = bagus ? 'Ambil foto' : 'Ambil foto (kualitas kurang)';
            btnAmbil.disabled = WAJIB_BAGUS && !bagus;
            btnAmbil.classList.toggle('is-disabled', btnAmbil.disabled);
        }

        // Menilai kecerahan, pantulan, dan ketajaman pada area dalam lingkaran
        function analisis() {
            if (!video.videoWidth) return;
            const vw = video.videoWidth, vh = video.videoHeight, sisi = Math.min(vw, vh);
            const reg = sisi * 0.6;
            const sx = (vw - reg) / 2, sy = (vh - reg) / 2;
            cx.drawImage(video, sx, sy, reg, reg, 0, 0, N, N);
            const d = cx.getImageData(0, 0, N, N).data;

            const g = new Float32Array(N * N);
            let jumlah = 0, putih = 0;
            for (let i = 0, p = 0; i < d.length; i += 4, p++) {
                const v = 0.299 * d[i] + 0.587 * d[i + 1] + 0.114 * d[i + 2];
                g[p] = v; jumlah += v;
                if (v >= 250) putih++;
            }
            const rata = jumlah / (N * N);
            const pantul = putih / (N * N);

            // ketajaman = variansi Laplacian (makin tinggi makin tajam)
            let m = 0, m2 = 0, c = 0;
            for (let y = 1; y < N - 1; y++) {
                for (let x = 1; x < N - 1; x++) {
                    const i = y * N + x;
                    const l = g[i - 1] + g[i + 1] + g[i - N] + g[i + N] - 4 * g[i];
                    m += l; m2 += l * l; c++;
                }
            }
            const tajam = m2 / c - (m / c) * (m / c);
            tajamHalus = (tajamHalus === null) ? tajam : (0.6 * tajamHalus + 0.4 * tajam);

            let warna, teks;
            if (rata < AMBANG_GELAP)            { warna = 'merah';  teks = 'Terlalu gelap. Cari cahaya lebih terang'; }
            else if (rata > AMBANG_TERANG)      { warna = 'merah';  teks = 'Terlalu terang. Kurangi cahaya langsung'; }
            else if (pantul > AMBANG_PANTUL)    { warna = 'kuning'; teks = 'Ada pantulan cahaya. Ubah sudut kamera'; }
            else if (tajamHalus < AMBANG_TAJAM) { warna = 'kuning'; teks = 'Kurang tajam. Atur jarak dan tahan stabil'; }
            else                                { warna = 'hijau';  teks = 'Bagus. Tahan, lalu ambil foto'; }

            if (TAMPILKAN_ANGKA) teks += ' [tajam ' + tajamHalus.toFixed(0) + ', terang ' + rata.toFixed(0) + ', pantul ' + (pantul * 100).toFixed(1) + '%]';
            tampilStatus(warna, teks);
        }

        async function aturKameraLanjutan() {
            try {
                const kap = track.getCapabilities ? track.getCapabilities() : {};
                // fokus otomatis terus-menerus (didukung sebagian besar HP Android/Chrome)
                if (kap.focusMode && kap.focusMode.includes('continuous')) {
                    await track.applyConstraints({ advanced: [{ focusMode: 'continuous' }] });
                }
                // slider zoom jika tersedia
                if (kap.zoom) {
                    zoomRange.min = kap.zoom.min; zoomRange.max = kap.zoom.max;
                    zoomRange.step = kap.zoom.step || 0.1;
                    zoomRange.value = (track.getSettings().zoom || kap.zoom.min);
                    zoomNilai.textContent = Number(zoomRange.value).toFixed(1) + 'x';
                    zoomWrap.classList.remove('hidden');
                } else {
                    zoomWrap.classList.add('hidden');
                }
            } catch (e) { /* tidak didukung, abaikan */ }
        }

        zoomRange.addEventListener('input', async () => {
            zoomNilai.textContent = Number(zoomRange.value).toFixed(1) + 'x';
            try { await track.applyConstraints({ advanced: [{ zoom: Number(zoomRange.value) }] }); } catch (e) {}
        });

        async function mulai() {
            berhenti();
            tampilStatus('abu', 'Menyiapkan kamera...');
            if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                tampilStatus('merah', 'Kamera tidak didukung. Buka lewat localhost atau HTTPS');
                return;
            }
            try {
                stream = await navigator.mediaDevices.getUserMedia({
                    video: { facingMode: { ideal: arah }, width: { ideal: 1280 }, height: { ideal: 960 } },
                    audio: false
                });
                track = stream.getVideoTracks()[0];
                video.srcObject = stream;
                await video.play();
                await aturKameraLanjutan();
                tajamHalus = null;
                timer = setInterval(analisis, 350);
            } catch (e) {
                tampilStatus('merah', 'Kamera tidak bisa dibuka. Klik Izinkan di browser');
            }
        }

        function berhenti() {
            if (timer) { clearInterval(timer); timer = null; }
            if (stream) { stream.getTracks().forEach(t => t.stop()); stream = null; track = null; }
            video.srcObject = null;
        }

        function buka()  { modal.classList.remove('hidden'); modal.classList.add('flex'); mulai(); }
        function tutup() { berhenti(); modal.classList.add('hidden'); modal.classList.remove('flex'); }

        document.getElementById('btnKamera').addEventListener('click', buka);
        document.getElementById('btnTutupKamera').addEventListener('click', tutup);
        document.addEventListener('keydown', e => { if (e.key === 'Escape') tutup(); });
        document.getElementById('btnGantiKamera').addEventListener('click', () => {
            arah = (arah === 'environment') ? 'user' : 'environment';
            mulai();
        });

        // Ambil foto: potong persegi di tengah (sama dengan yang terlihat di pratinjau)
        btnAmbil.addEventListener('click', () => {
            if (!video.videoWidth || btnAmbil.disabled) return;
            const vw = video.videoWidth, vh = video.videoHeight, sisi = Math.min(vw, vh);
            const keluar = Math.min(sisi, 1024);
            canvas.width = keluar; canvas.height = keluar;
            canvas.getContext('2d').drawImage(video, (vw - sisi) / 2, (vh - sisi) / 2, sisi, sisi, 0, 0, keluar, keluar);
            canvas.toBlob(blob => {
                if (!blob) return;
                const file = new File([blob], 'kamera_' + Date.now() + '.jpg', { type: 'image/jpeg' });
                const dt = new DataTransfer();
                dt.items.add(file);
                inputFile.files = dt.files;                   // masukkan ke input file yang sudah ada
                inputFile.dispatchEvent(new Event('change')); // memicu pratinjau dan info file
                tutup();
            }, 'image/jpeg', 0.92);
        });
    })();
    </script>

</body>
</html>
@extends('layouts.guest')

@section('title', 'Login - Smart Fish Feeder')

@section('content')
<div class="login-wrapper">

    {{-- DEKORASI LATAR (VISUAL SAJA) --}}
    <div class="bg-orb bg-orb-1" aria-hidden="true"></div>
    <div class="bg-orb bg-orb-2" aria-hidden="true"></div>

    <div class="login-container">

        {{-- SISI KIRI: BRANDING DAN ILUSTRASI --}}
        <div class="login-banner">

            {{-- GELEMBUNG AIR (VISUAL SAJA) --}}
            <div class="bubbles" aria-hidden="true">
                <span style="--x:8%;  --s:14px; --d:9s;  --delay:0s;"></span>
                <span style="--x:22%; --s:8px;  --d:7s;  --delay:2s;"></span>
                <span style="--x:38%; --s:18px; --d:11s; --delay:1s;"></span>
                <span style="--x:55%; --s:10px; --d:8s;  --delay:3.5s;"></span>
                <span style="--x:68%; --s:16px; --d:10s; --delay:0.5s;"></span>
                <span style="--x:82%; --s:9px;  --d:7.5s;--delay:2.8s;"></span>
                <span style="--x:92%; --s:12px; --d:9.5s;--delay:4.5s;"></span>
            </div>

            <div class="banner-content">

                <div class="banner-icon-wrapper">
                    <img
                        src="{{ asset('aset/LOGO1.jpeg') }}"
                        alt="Logo Smart Fish Feeder"
                        class="login-logo"
                    >
                </div>

                <h2>Smart Fish Feeder</h2>

                <p>
                    Sistem monitoring dan kontrol pemberian pakan ikan
                    otomatis berbasis IoT untuk efisiensi budidaya
                    perikanan Anda.
                </p>

                <div class="banner-features">
                    <div class="feature-item">
                        <i class="bi bi-check-circle-fill"></i>
                        Jadwal Pakan Otomatis
                    </div>

                    <div class="feature-item">
                        <i class="bi bi-check-circle-fill"></i>
                        Monitoring Stok Real-time
                    </div>

                    <div class="feature-item">
                        <i class="bi bi-check-circle-fill"></i>
                        Prediksi Refill Akurat
                    </div>
                </div>
            </div>

            <div class="banner-footer">
                &copy; {{ date('Y') }} Politeknik Negeri Jember
                — PSDKU Sidoarjo
            </div>

            {{-- GELOMBANG AIR (VISUAL SAJA) --}}
            <div class="waves" aria-hidden="true">
                <svg class="wave wave-back" viewBox="0 0 1200 60" preserveAspectRatio="none">
                    <path d="M0,30 C150,60 300,0 450,30 C600,60 750,0 900,30 C1050,60 1200,0 1350,30 L1350,60 L0,60 Z"/>
                </svg>
                <svg class="wave wave-front" viewBox="0 0 1200 60" preserveAspectRatio="none">
                    <path d="M0,30 C150,0 300,60 450,30 C600,0 750,60 900,30 C1050,0 1200,60 1350,30 L1350,60 L0,60 Z"/>
                </svg>
            </div>
        </div>

        {{-- SISI KANAN: FORM LOGIN --}}
        <div class="login-form-side">

            <div class="form-header">
                <h3>Selamat Datang Kembali! <span class="wave-hand">👋</span></h3>
                <p>Silakan masuk menggunakan akun yang terdaftar.</p>
            </div>

            {{-- PESAN ERROR --}}
            @if ($errors->any())
                <div class="alert-custom-error">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <span>
                        Email atau password yang Anda masukkan salah.
                    </span>
                </div>
            @endif

            {{-- PESAN STATUS --}}
            @if (session('status'))
                <div class="alert-custom-success">
                    <i class="bi bi-check-circle-fill"></i>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            <form
                method="POST"
                action="{{ route('login') }}"
                class="login-form-group"
            >
                @csrf

                {{-- INPUT EMAIL --}}
                <div class="input-block">
                    <label for="email" class="input-label">
                        Email
                    </label>

                    <div class="input-with-icon">
                        <i class="bi bi-envelope"></i>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            autocomplete="username"
                            placeholder="nama@email.com"
                            class="custom-input"
                        >
                    </div>

                    @error('email')
                        <small class="field-error">{{ $message }}</small>
                    @enderror
                </div>

                {{-- INPUT PASSWORD --}}
                <div class="input-block">

                    <div class="label-row">
                        <label for="password" class="input-label">
                            Password
                        </label>

                        @if (Route::has('password.request'))
                            <a
                                href="{{ route('password.request') }}"
                                class="forgot-link"
                            >
                                Lupa Password?
                            </a>
                        @endif
                    </div>

                    <div class="input-with-icon">
                        <i class="bi bi-lock"></i>

                        <input
                            type="password"
                            id="password"
                            name="password"
                            required
                            autocomplete="current-password"
                            placeholder="Masukkan password"
                            class="custom-input password-input"
                        >

                        {{-- TOMBOL LIHAT PASSWORD --}}
                        <button
                            type="button"
                            id="togglePassword"
                            class="btn-toggle-password"
                            title="Tampilkan Password"
                            aria-label="Tampilkan password"
                        >
                            <i
                                class="bi bi-eye-slash"
                                id="eyeIcon"
                            ></i>
                        </button>
                    </div>

                    @error('password')
                        <small class="field-error">{{ $message }}</small>
                    @enderror
                </div>

                {{-- TOMBOL MASUK --}}
                <button type="submit" class="btn-submit-login">
                    Masuk ke Sistem
                </button>
            </form>
        </div>

    </div>
</div>

{{-- CSS HALAMAN LOGIN --}}
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

    * {
        box-sizing: border-box;
    }

    :root {
        --sea-deep: #052e4a;
        --sea-mid: #0a6a8f;
        --sea-bright: #1296c4;
        --aqua: #5eead4;
        --foam: #eaf8fb;
        --ink: #0f2a3a;
        --muted: #5b7384;
        --line: #d3e4ec;
        --accent: #0e86b5;
        --accent-dark: #0a6a8f;
    }

    .login-wrapper {
        position: relative;
        min-height: 100vh;
        background: linear-gradient(160deg, #e8f6fa 0%, #f4fafc 50%, #e3f1f7 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1.5rem;
        overflow: hidden;
        font-family: 'Plus Jakarta Sans', system-ui, -apple-system, 'Segoe UI', sans-serif;
    }

    /* ORB LATAR YANG MELAYANG PELAN */
    .bg-orb {
        position: absolute;
        border-radius: 50%;
        filter: blur(60px);
        opacity: 0.55;
        pointer-events: none;
    }

    .bg-orb-1 {
        width: 420px;
        height: 420px;
        background: #7fd6ea;
        top: -120px;
        left: -100px;
        animation: orbDrift 16s ease-in-out infinite alternate;
    }

    .bg-orb-2 {
        width: 360px;
        height: 360px;
        background: #9be8d8;
        bottom: -120px;
        right: -80px;
        animation: orbDrift 20s ease-in-out infinite alternate-reverse;
    }

    @keyframes orbDrift {
        from { transform: translate(0, 0) scale(1); }
        to   { transform: translate(50px, 40px) scale(1.12); }
    }

    .login-container {
        position: relative;
        z-index: 1;
        width: 100%;
        max-width: 960px;
        background: #ffffff;
        border-radius: 22px;
        box-shadow:
            0 30px 60px -20px rgba(5, 46, 74, 0.28),
            0 12px 24px -12px rgba(5, 46, 74, 0.12);
        display: grid;
        grid-template-columns: 1fr 1.1fr;
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.8);
        animation: cardRise 0.8s cubic-bezier(0.22, 1, 0.36, 1) both;
    }

    @keyframes cardRise {
        from { opacity: 0; transform: translateY(28px) scale(0.98); }
        to   { opacity: 1; transform: translateY(0) scale(1); }
    }

    /* SISI KIRI: BANNER */
    .login-banner {
        position: relative;
        background:
            radial-gradient(circle at 20% 15%, rgba(94, 234, 212, 0.28), transparent 45%),
            linear-gradient(165deg, var(--sea-bright) 0%, var(--sea-mid) 45%, var(--sea-deep) 100%);
        color: white;
        padding: 2.5rem 2.5rem 4.5rem;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        overflow: hidden;
    }

    .banner-content,
    .banner-footer {
        position: relative;
        z-index: 3;
    }

    .banner-content {
        width: 100%;
    }

    .banner-icon-wrapper {
        width: 80px;
        height: 80px;
        background: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        margin-bottom: 1.25rem;
        padding: 0;
        overflow: hidden;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25);
        border: 3px solid rgba(255, 255, 255, 0.5);
        animation:
            popIn 0.7s 0.25s cubic-bezier(0.34, 1.56, 0.64, 1) both,
            floatLogo 5s 1s ease-in-out infinite;
    }

    @keyframes popIn {
        from { opacity: 0; transform: scale(0.4); }
        to   { opacity: 1; transform: scale(1); }
    }

    @keyframes floatLogo {
        0%, 100% { transform: translateY(0); }
        50%      { transform: translateY(-8px); }
    }

    .login-logo {
        width: 100%;
        height: 100%;
        object-fit: contain;
        display: block;
    }

    .login-banner h2 {
        font-size: 1.6rem;
        font-weight: 800;
        letter-spacing: -0.02em;
        margin: 0 0 0.75rem;
        animation: slideIn 0.6s 0.4s ease-out both;
    }

    .login-banner p {
        font-size: 0.875rem;
        color: rgba(255, 255, 255, 0.85);
        line-height: 1.7;
        margin: 0 0 2rem;
        animation: slideIn 0.6s 0.5s ease-out both;
    }

    .banner-features {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .feature-item {
        font-size: 0.8125rem;
        display: flex;
        align-items: center;
        gap: 0.6rem;
        color: rgba(255, 255, 255, 0.95);
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.16);
        backdrop-filter: blur(4px);
        padding: 0.55rem 0.85rem;
        border-radius: 10px;
        width: fit-content;
        max-width: 100%;
        transition: transform 0.25s ease, background 0.25s ease;
        animation: slideIn 0.6s ease-out both;
    }

    .feature-item:nth-child(1) { animation-delay: 0.65s; }
    .feature-item:nth-child(2) { animation-delay: 0.8s; }
    .feature-item:nth-child(3) { animation-delay: 0.95s; }

    .feature-item:hover {
        transform: translateX(6px);
        background: rgba(255, 255, 255, 0.18);
    }

    .feature-item i {
        color: var(--aqua);
    }

    @keyframes slideIn {
        from { opacity: 0; transform: translateX(-24px); }
        to   { opacity: 1; transform: translateX(0); }
    }

    .banner-footer {
        font-size: 0.75rem;
        color: rgba(255, 255, 255, 0.75);
        margin-top: 2rem;
        line-height: 1.6;
    }

    /* GELEMBUNG AIR */
    .bubbles {
        position: absolute;
        inset: 0;
        z-index: 1;
        pointer-events: none;
    }

    .bubbles span {
        position: absolute;
        bottom: -30px;
        left: var(--x);
        width: var(--s);
        height: var(--s);
        border-radius: 50%;
        background: radial-gradient(circle at 30% 30%, rgba(255, 255, 255, 0.75), rgba(255, 255, 255, 0.12));
        border: 1px solid rgba(255, 255, 255, 0.4);
        animation: bubbleUp var(--d) var(--delay) linear infinite;
    }

    @keyframes bubbleUp {
        0%   { transform: translateY(0) translateX(0); opacity: 0; }
        10%  { opacity: 1; }
        50%  { transform: translateY(-260px) translateX(14px); }
        90%  { opacity: 0.8; }
        100% { transform: translateY(-560px) translateX(-10px); opacity: 0; }
    }

    /* GELOMBANG AIR */
    .waves {
        position: absolute;
        left: 0;
        right: 0;
        bottom: 0;
        height: 70px;
        z-index: 2;
        pointer-events: none;
        overflow: hidden;
    }

    .wave {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 200%;
        height: 100%;
    }

    .wave-back {
        fill: rgba(255, 255, 255, 0.18);
        animation: waveMove 11s linear infinite;
    }

    .wave-front {
        fill: rgba(255, 255, 255, 0.3);
        height: 75%;
        animation: waveMove 7s linear infinite reverse;
    }

    @keyframes waveMove {
        from { transform: translateX(0); }
        to   { transform: translateX(-50%); }
    }

    /* SISI KANAN: FORM LOGIN */
    .login-form-side {
        padding: 2.75rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
        min-width: 0;
        background: #ffffff;
    }

    .form-header {
        margin-bottom: 1.75rem;
        animation: fadeUp 0.6s 0.35s ease-out both;
    }

    .form-header h3 {
        font-size: 1.4rem;
        font-weight: 800;
        letter-spacing: -0.02em;
        color: var(--ink);
        margin: 0 0 0.5rem;
    }

    .wave-hand {
        display: inline-block;
        transform-origin: 70% 70%;
        animation: waveHand 2.4s 1.2s ease-in-out infinite;
    }

    @keyframes waveHand {
        0%, 60%, 100% { transform: rotate(0deg); }
        10%, 30%      { transform: rotate(16deg); }
        20%, 40%      { transform: rotate(-10deg); }
        50%           { transform: rotate(8deg); }
    }

    .form-header p {
        font-size: 0.8125rem;
        color: var(--muted);
        margin: 0;
        line-height: 1.6;
    }

    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(18px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* NOTIFIKASI */
    .alert-custom-error,
    .alert-custom-success {
        padding: 0.75rem 1rem;
        border-radius: 10px;
        font-size: 0.8125rem;
        display: flex;
        align-items: flex-start;
        gap: 0.5rem;
        margin-bottom: 1.25rem;
        line-height: 1.5;
    }

    .alert-custom-error {
        background: #fef2f2;
        border: 1px solid #fecaca;
        color: #dc2626;
        animation: shake 0.5s ease-in-out;
    }

    .alert-custom-success {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        color: #15803d;
        animation: fadeUp 0.5s ease-out both;
    }

    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        20%      { transform: translateX(-8px); }
        40%      { transform: translateX(7px); }
        60%      { transform: translateX(-5px); }
        80%      { transform: translateX(3px); }
    }

    .alert-custom-error i,
    .alert-custom-success i {
        margin-top: 2px;
    }

    .login-form-group {
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
    }

    .input-block {
        display: flex;
        flex-direction: column;
        gap: 0.4rem;
        min-width: 0;
        animation: fadeUp 0.6s ease-out both;
    }

    .input-block:nth-of-type(1) { animation-delay: 0.5s; }
    .input-block:nth-of-type(2) { animation-delay: 0.65s; }

    .input-label {
        font-size: 0.8rem;
        font-weight: 700;
        color: #36566b;
        letter-spacing: 0.01em;
    }

    .label-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .forgot-link {
        font-size: 0.75rem;
        color: var(--accent);
        text-decoration: none;
        font-weight: 600;
        background: linear-gradient(currentColor, currentColor) no-repeat 0 100% / 0 1.5px;
        transition: background-size 0.3s ease, color 0.2s;
    }

    .forgot-link:hover {
        background-size: 100% 1.5px;
        color: var(--accent-dark);
    }

    /* INPUT DENGAN IKON */
    .input-with-icon {
        position: relative;
        display: flex;
        align-items: center;
        width: 100%;
    }

    .input-with-icon > i.bi-envelope,
    .input-with-icon > i.bi-lock {
        position: absolute;
        left: 0.9rem;
        color: #8aa5b5;
        font-size: 0.95rem;
        z-index: 2;
        pointer-events: none;
        transition: color 0.25s, transform 0.25s;
    }

    .input-with-icon:focus-within > i.bi-envelope,
    .input-with-icon:focus-within > i.bi-lock {
        color: var(--accent);
        transform: scale(1.15);
    }

    .custom-input {
        width: 100%;
        min-width: 0;
        padding: 0.8rem 2.75rem 0.8rem 2.6rem;
        font-family: inherit;
        font-size: 0.875rem;
        border: 1.5px solid var(--line);
        border-radius: 12px;
        outline: none;
        background-color: #f7fbfd;
        color: var(--ink);
        transition: border-color 0.25s, box-shadow 0.25s, background-color 0.25s, transform 0.25s;
    }

    .custom-input::placeholder {
        color: #9bb2c0;
    }

    .custom-input:hover {
        border-color: #a9cddd;
    }

    .custom-input:focus {
        border-color: var(--accent);
        background-color: #ffffff;
        box-shadow: 0 0 0 4px rgba(18, 150, 196, 0.15);
        transform: translateY(-1px);
    }

    .field-error {
        color: #dc2626;
        font-size: 0.75rem;
        line-height: 1.5;
    }

    /* TOMBOL IKON MATA PASSWORD */
    .btn-toggle-password {
        position: absolute;
        right: 0.8rem;
        background: transparent;
        border: none;
        color: #8aa5b5;
        cursor: pointer;
        font-size: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 4px;
        z-index: 2;
        transition: color 0.2s, transform 0.2s;
    }

    .btn-toggle-password:hover {
        color: var(--accent);
        transform: scale(1.15);
    }

    .btn-toggle-password:focus-visible {
        outline: 2px solid var(--accent);
        outline-offset: 2px;
        border-radius: 4px;
    }

    /* TOMBOL LOGIN */
    .btn-submit-login {
        position: relative;
        overflow: hidden;
        width: 100%;
        margin-top: 0.5rem;
        padding: 0.9rem;
        background: linear-gradient(135deg, var(--sea-bright), var(--sea-mid));
        color: white;
        border: none;
        border-radius: 12px;
        font-family: inherit;
        font-weight: 700;
        font-size: 0.9rem;
        cursor: pointer;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
        box-shadow: 0 8px 18px -6px rgba(10, 106, 143, 0.55);
        animation: fadeUp 0.6s 0.8s ease-out both;
    }

    /* KILAU YANG LEWAT SAAT HOVER */
    .btn-submit-login::after {
        content: '';
        position: absolute;
        top: 0;
        left: -80%;
        width: 50%;
        height: 100%;
        background: linear-gradient(100deg, transparent, rgba(255, 255, 255, 0.4), transparent);
        transform: skewX(-20deg);
    }

    .btn-submit-login:hover {
        transform: translateY(-2px);
        box-shadow: 0 14px 24px -8px rgba(10, 106, 143, 0.6);
    }

    .btn-submit-login:hover::after {
        left: 130%;
        transition: left 0.7s ease;
    }

    .btn-submit-login:active {
        transform: translateY(0) scale(0.98);
    }

    .btn-submit-login:focus-visible {
        outline: 3px solid rgba(18, 150, 196, 0.35);
        outline-offset: 3px;
    }

    /* RESPONSIF TABLET DAN HP */
    @media (max-width: 768px) {
        .login-wrapper {
            padding: 1rem;
            align-items: flex-start;
        }

        .login-container {
            grid-template-columns: 1fr;
            max-width: 420px;
            margin: auto;
            border-radius: 20px;
        }

        .login-banner {
            padding: 1.5rem 1.5rem 3.5rem;
        }

        .banner-icon-wrapper {
            width: 68px;
            height: 68px;
            margin-bottom: 1rem;
        }

        .login-banner p {
            margin-bottom: 1.25rem;
        }

        .banner-footer {
            margin-top: 1.25rem;
        }

        .bubbles span {
            animation-name: bubbleUpSmall;
        }

        @keyframes bubbleUpSmall {
            0%   { transform: translateY(0); opacity: 0; }
            10%  { opacity: 1; }
            100% { transform: translateY(-380px); opacity: 0; }
        }

        .waves {
            height: 54px;
        }

        .login-form-side {
            padding: 1.75rem 1.5rem 2rem;
        }

        .bg-orb {
            opacity: 0.4;
        }
    }

    @media (max-width: 380px) {
        .login-wrapper {
            padding: 0.5rem;
        }

        .login-banner {
            padding: 1.25rem 1.25rem 3.25rem;
        }

        .login-form-side {
            padding: 1.5rem 1.25rem;
        }

        .form-header h3 {
            font-size: 1.15rem;
        }
    }

    /* HORMATI PENGGUNA YANG MEMATIKAN ANIMASI DI PERANGKATNYA */
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

{{-- JAVASCRIPT UNTUK LIHAT / SEMBUNYIKAN PASSWORD --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');

        if (!togglePassword || !passwordInput || !eyeIcon) {
            return;
        }

        togglePassword.addEventListener('click', function () {
            const isPassword = passwordInput.type === 'password';

            passwordInput.type = isPassword ? 'text' : 'password';

            if (isPassword) {
                eyeIcon.classList.remove('bi-eye-slash');
                eyeIcon.classList.add('bi-eye');

                togglePassword.setAttribute(
                    'title',
                    'Sembunyikan Password'
                );

                togglePassword.setAttribute(
                    'aria-label',
                    'Sembunyikan password'
                );
            } else {
                eyeIcon.classList.remove('bi-eye');
                eyeIcon.classList.add('bi-eye-slash');

                togglePassword.setAttribute(
                    'title',
                    'Tampilkan Password'
                );

                togglePassword.setAttribute(
                    'aria-label',
                    'Tampilkan password'
                );
            }
        });
    });
</script>
@endsection
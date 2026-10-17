
@extends('layouts.guest')

@section('title', 'Login - Smart Fish Feeder')

@section('content')
<div class="login-wrapper">
    <div class="login-container">

        {{-- SISI KIRI: BRANDING DAN ILUSTRASI --}}
        <div class="login-banner">
            <div class="banner-content">

                <div class="banner-icon-wrapper">
                    <img
                        src="{{ asset('aset/logo.png') }}"
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
        </div>

        {{-- SISI KANAN: FORM LOGIN --}}
        <div class="login-form-side">

            <div class="form-header">
                <h3>Selamat Datang Kembali! 👋</h3>
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
    * {
        box-sizing: border-box;
    }

    .login-wrapper {
        min-height: 100vh;
        background: #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1.5rem;
    }

    .login-container {
        width: 100%;
        max-width: 960px;
        background: #ffffff;
        border-radius: 16px;
        box-shadow:
            0 10px 25px -5px rgba(0, 0, 0, 0.05),
            0 8px 10px -6px rgba(0, 0, 0, 0.05);
        display: grid;
        grid-template-columns: 1fr 1.1fr;
        overflow: hidden;
        border: 1px solid #e2e8f0;
    }

    /* SISI KIRI: BANNER */
    .login-banner {
        background: linear-gradient(135deg, #1e40af, #2563eb);
        color: white;
        padding: 2.5rem;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .banner-content {
        width: 100%;
    }

    .banner-icon-wrapper {
        width: 60px;
        height: 60px;
        background: rgba(255, 255, 255, 0.15);
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        margin-bottom: 1.5rem;
        padding: 8px;
        overflow: hidden;
    }

    .login-logo {
        width: 100%;
        height: 100%;
        object-fit: contain;
        display: block;
    }

    .login-banner h2 {
        font-size: 1.5rem;
        font-weight: 700;
        margin: 0 0 0.75rem;
    }

    .login-banner p {
        font-size: 0.875rem;
        color: rgba(255, 255, 255, 0.8);
        line-height: 1.6;
        margin: 0 0 2rem;
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
        gap: 0.5rem;
        color: rgba(255, 255, 255, 0.9);
    }

    .feature-item i {
        color: #60a5fa;
    }

    .banner-footer {
        font-size: 0.75rem;
        color: rgba(255, 255, 255, 0.7);
        margin-top: 2rem;
        line-height: 1.6;
    }

    /* SISI KANAN: FORM LOGIN */
    .login-form-side {
        padding: 2.5rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
        min-width: 0;
    }

    .form-header {
        margin-bottom: 1.5rem;
    }

    .form-header h3 {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1e293b;
        margin: 0 0 0.5rem;
    }

    .form-header p {
        font-size: 0.8125rem;
        color: #64748b;
        margin: 0;
        line-height: 1.5;
    }

    /* NOTIFIKASI */
    .alert-custom-error,
    .alert-custom-success {
        padding: 0.75rem 1rem;
        border-radius: 8px;
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
    }

    .alert-custom-success {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        color: #15803d;
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
    }

    .input-label {
        font-size: 0.75rem;
        font-weight: 700;
        color: #475569;
        text-transform: uppercase;
        letter-spacing: 0.5px;
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
        color: #2563eb;
        text-decoration: none;
        font-weight: 500;
    }

    .forgot-link:hover {
        text-decoration: underline;
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
        left: 0.875rem;
        color: #94a3b8;
        font-size: 0.95rem;
        z-index: 2;
        pointer-events: none;
    }

    .custom-input {
        width: 100%;
        min-width: 0;
        padding: 0.75rem 2.75rem 0.75rem 2.5rem;
        font-family: inherit;
        font-size: 0.875rem;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        outline: none;
        background-color: #ffffff;
        color: #1e293b;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .custom-input::placeholder {
        color: #94a3b8;
    }

    .custom-input:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
    }

    .field-error {
        color: #dc2626;
        font-size: 0.75rem;
        line-height: 1.5;
    }

    /* TOMBOL IKON MATA PASSWORD */
    .btn-toggle-password {
        position: absolute;
        right: 0.875rem;
        background: transparent;
        border: none;
        color: #94a3b8;
        cursor: pointer;
        font-size: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 4px;
        z-index: 2;
        transition: color 0.2s;
    }

    .btn-toggle-password:hover {
        color: #2563eb;
    }

    .btn-toggle-password:focus-visible {
        outline: 2px solid #2563eb;
        outline-offset: 2px;
        border-radius: 4px;
    }

    /* TOMBOL LOGIN */
    .btn-submit-login {
        width: 100%;
        margin-top: 0.5rem;
        padding: 0.85rem;
        background: #2563eb;
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.875rem;
        cursor: pointer;
        transition: background-color 0.2s, box-shadow 0.2s;
        box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.2);
    }

    .btn-submit-login:hover {
        background: #1d4ed8;
        box-shadow: 0 4px 12px -2px rgba(37, 99, 235, 0.3);
    }

    .btn-submit-login:focus-visible {
        outline: 3px solid rgba(37, 99, 235, 0.3);
        outline-offset: 3px;
    }

    /* RESPONSIF TABLET DAN HP */
    @media (max-width: 768px) {
        .login-wrapper {
            padding: 1rem;
        }

        .login-container {
            grid-template-columns: 1fr;
            max-width: 420px;
        }

        .login-banner {
            padding: 1.5rem;
        }

        .login-banner p {
            margin-bottom: 1.5rem;
        }

        .banner-footer {
            margin-top: 1.5rem;
        }

        .login-form-side {
            padding: 1.5rem;
        }
    }

    @media (max-width: 380px) {
        .login-wrapper {
            padding: 0.5rem;
        }

        .login-banner,
        .login-form-side {
            padding: 1.25rem;
        }

        .form-header h3 {
            font-size: 1.1rem;
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

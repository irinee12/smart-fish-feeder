@extends('layouts.guest')

@section('title', 'Login - Smart Fish Feeder')

@section('content')
<div class="login-wrapper">
    <div class="login-container">
        
        {{-- SISI KIRI: BRANDING & ILUSTRASI VISUAL --}}
        <div class="login-banner">
            <div class="banner-content">
                <div class="banner-icon-wrapper">
                    <i class="bi bi-water"></i>
                </div>
                <h2>Smart Fish Feeder</h2>
                <p>Sistem monitoring dan kontrol pemberian pakan ikan otomatis berbasis IoT untuk efisiensi budidaya perikanan Anda.</p>
                
                <div class="banner-features">
                    <div class="feature-item">
                        <i class="bi bi-check-circle-fill"></i> Jadwal Pakan Otomatis
                    </div>
                    <div class="feature-item">
                        <i class="bi bi-check-circle-fill"></i> Monitoring Stok Real-time
                    </div>
                    <div class="feature-item">
                        <i class="bi bi-check-circle-fill"></i> Prediksi Refill Akurat
                    </div>
                </div>
            </div>
            <div class="banner-footer">
                &copy; {{ date('Y') }} Politeknik Negeri Jember — PSDKU Sidoarjo
            </div>
        </div>

        {{-- SISI KANAN: FORM LOGIN --}}
        <div class="login-form-side">
            <div class="form-header">
                <h3>Selamat Datang Kembali! 👋</h3>
                <p>Silakan masuk menggunakan akun yang terdaftar.</p>
            </div>

            {{-- Pesan Error / Status Session (Jika ada) --}}
            @if ($errors->any())
                <div class="alert-custom-error">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <span>Email atau password yang Anda masukkan salah.</span>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="login-form-group">
                @csrf

                <div class="input-block">
                    <label for="email" class="input-label">Email / Username</label>
                    <div class="input-with-icon">
                        <i class="bi bi-envelope"></i>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus placeholder="nama@email.com" class="custom-input">
                    </div>
                </div>

                <div class="input-block">
                    <div class="label-row">
                        <label for="password" class="input-label">Password</label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="forgot-link">Lupa Password?</a>
                        @endif
                    </div>
                    <div class="input-with-icon">
                        <i class="bi bi-lock"></i>
                        <input type="password" id="password" name="password" required placeholder="••••••••" class="custom-input password-input">
                        
                        {{-- TOMBOL IKON MATA UNTUK LIHAT PASSWORD --}}
                        <button type="button" id="togglePassword" class="btn-toggle-password" title="Tampilkan Password">
                            <i class="bi bi-eye-slash" id="eyeIcon"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn-submit-login">
                    Masuk ke Sistem
                </button>
            </form>
        </div>

    </div>
</div>

{{-- CSS KHUSUS HALAMAN LOGIN --}}
<style>
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
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
        display: grid;
        grid-template-columns: 1fr 1.1fr;
        overflow: hidden;
        border: 1px solid #e2e8f0;
    }

    /* Kiri: Banner */
    .login-banner {
        background: linear-gradient(135deg, #1e40af, #2563eb);
        color: white;
        padding: 2.5rem;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .banner-icon-wrapper {
        font-size: 2.5rem;
        background: rgba(255, 255, 255, 0.15);
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        margin-bottom: 1.5rem;
    }

    .login-banner h2 {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 0.75rem;
    }

    .login-banner p {
        font-size: 0.875rem;
        color: rgba(255, 255, 255, 0.8);
        line-height: 1.5;
        margin-bottom: 2rem;
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
        color: rgba(255, 255, 255, 0.6);
        margin-top: 2rem;
    }

    /* Kanan: Form Side */
    .login-form-side {
        padding: 2.5rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .form-header {
        margin-bottom: 1.5rem;
    }

    .form-header h3 {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 0.25rem;
    }

    .form-header p {
        font-size: 0.8125rem;
        color: #64748b;
    }

    .alert-custom-error {
        background: #fef2f2;
        border: 1px solid #fecaca;
        color: #dc2626;
        padding: 0.75rem 1rem;
        border-radius: 8px;
        font-size: 0.8125rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1.25rem;
    }

    .login-form-group {
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
    }

    .input-block {
        display: flex;
        flex-direction: column;
        gap: 0.35rem;
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

    .input-with-icon {
        position: relative;
        display: flex;
        align-items: center;
    }

    .input-with-icon > i.bi-envelope,
    .input-with-icon > i.bi-lock {
        position: absolute;
        left: 0.875rem;
        color: #94a3b8;
        font-size: 0.95rem;
        z-index: 2;
    }

    .custom-input {
        width: 100%;
        padding: 0.625rem 2.5rem 0.625rem 2.5rem;
        font-size: 0.875rem;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        outline: none;
        background-color: #ffffff;
        color: #1e293b;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .custom-input:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
    }

    /* Style untuk tombol ikon mata */
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
        padding: 0;
        z-index: 2;
        transition: color 0.2s;
    }

    .btn-toggle-password:hover {
        color: #2563eb;
    }

    .btn-submit-login {
        margin-top: 0.5rem;
        padding: 0.75rem;
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

    /* Responsif untuk layar HP / Tablet Kecil */
    @media (max-width: 768px) {
        .login-container {
            grid-template-columns: 1fr;
            max-width: 420px;
        }
        .login-banner {
            padding: 1.5rem;
        }
        .login-form-side {
            padding: 1.5rem;
        }
    }
</style>

{{-- SCRIPT JAVASCRIPT UNTUK TOGGLE MATA PASSWORD --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');

        togglePassword.addEventListener('click', function () {
            // Ubah tipe input dari password ke text atau sebaliknya
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);

            // Ubah ikon mata terbuka / tercoret
            if (type === 'text') {
                eyeIcon.classList.remove('bi-eye-slash');
                eyeIcon.classList.add('bi-eye');
                togglePassword.setAttribute('title', 'Sembunyikan Password');
            } else {
                eyeIcon.classList.remove('bi-eye');
                eyeIcon.classList.add('bi-eye-slash');
                togglePassword.setAttribute('title', 'Tampilkan Password');
            }
        });
    });
</script>
@endsection
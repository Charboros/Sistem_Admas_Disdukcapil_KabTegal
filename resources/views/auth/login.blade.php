<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login — Layanan Admas Disdukcapil Kab. Tegal</title>
    <meta name="description" content="Sistem Manajemen Aduan Layanan Dinas Kependudukan dan Pencatatan Sipil Kabupaten Tegal">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>

    {{-- ── Panel Kiri: Branding ── --}}
    <div class="brand-panel">
        <div class="deco-circle deco-c1"></div>
        <div class="deco-circle deco-c2"></div>
        <div class="deco-circle deco-c3"></div>

        <div class="brand-content">
            <div class="logos-row">
                <div class="logo-wrap">
                    <img src="{{ asset('images/kemendagri-logo.png') }}"
                         alt="Logo Kementerian Dalam Negeri" class="logo-img">
                    <span class="logo-label">Kemendagri</span>
                </div>
                <div class="logo-divider"></div>
                <div class="logo-wrap">
                    <img src="{{ asset('images/tegal-logo.png') }}"
                         alt="Logo Kabupaten Tegal" class="logo-img">
                    <span class="logo-label">Kab. Tegal</span>
                </div>
            </div>

            <div class="brand-title">Layanan Admas</div>
            <p class="brand-sub">
                Sistem Manajemen Aduan Layanan Administrasi Kependudukan<br>
                <strong style="color:rgba(255,255,255,0.9);">Disdukcapil Kabupaten Tegal</strong>
            </p>

            <div class="feature-list">
                <div class="feature-item">
                    <div class="feature-icon">
                        <svg width="14" height="14" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    Input & Pengelolaan Aduan Masyarakat
                </div>
                <div class="feature-item">
                    <div class="feature-icon">
                        <svg width="14" height="14" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                        </svg>
                    </div>
                    Respon & Tindak Lanjut Cepat
                </div>
                <div class="feature-item">
                    <div class="feature-icon">
                        <svg width="14" height="14" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    Rekap & Ekspor Laporan Excel
                </div>
            </div>
        </div>
    </div>

    {{-- ── Panel Kanan: Form Login ── --}}
    <div class="form-panel">
        <div class="form-header">
            <h2>Selamat Datang</h2>
            <p>Masuk menggunakan akun yang telah diberikan.</p>
        </div>

        <div class="form-card">
            @if (session('status'))
                <div class="alert-status">{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                    <label for="name" class="form-label">Nama Pengguna</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}"
                           class="form-input" required autofocus autocomplete="username">
                    @error('name')
                        <p class="error-text">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <div class="password-wrapper">
                        <input id="password" type="password" name="password"
                               class="form-input" required autocomplete="current-password">
                        <button type="button" class="password-toggle" id="togglePassword" tabindex="-1" title="Tampilkan Password">
                            <!-- Icon Mata Tertutup (Eye) -->
                            <svg id="eyeIcon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <!-- Icon Mata Terbuka (Eye Slash) -->
                            <svg id="eyeSlashIcon" style="display: none;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="error-text">{{ $message }}</p>
                    @enderror
                </div>

                <div class="remember-row">
                    <input id="remember_me" type="checkbox" name="remember">
                    <label for="remember_me">Ingat saya di perangkat ini</label>
                </div>

                <button type="submit" class="btn-login">
                    Masuk ke Sistem →
                </button>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            const eyeSlashIcon = document.getElementById('eyeSlashIcon');

            if(togglePassword && passwordInput) {
                togglePassword.addEventListener('click', function () {
                    const isPassword = passwordInput.getAttribute('type') === 'password';
                    passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
                    
                    if (isPassword) {
                        eyeIcon.style.display = 'none';
                        eyeSlashIcon.style.display = 'block';
                    } else {
                        eyeIcon.style.display = 'block';
                        eyeSlashIcon.style.display = 'none';
                    }
                });
            }
        });
    </script>
</body>
</html>

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

    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            min-height: 100vh;
            display: flex;
            background: #0f172a;
            overflow: hidden;
        }

        /* ── Sisi Kiri: Branding Panel ── */
        .brand-panel {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 3rem;
            position: relative;
            background: linear-gradient(145deg, #1e3a8a 0%, #1d4ed8 50%, #2563eb 100%);
            overflow: hidden;
        }
        .brand-panel::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse at 20% 20%, rgba(255,255,255,0.06) 0%, transparent 60%),
                radial-gradient(ellipse at 80% 80%, rgba(59,130,246,0.25) 0%, transparent 60%);
        }
        /* Dekorasi lingkaran */
        .deco-circle {
            position: absolute;
            border-radius: 50%;
            border: 1px solid rgba(255,255,255,0.08);
        }
        .deco-c1 { width: 400px; height: 400px; top: -100px; left: -100px; }
        .deco-c2 { width: 300px; height: 300px; bottom: -80px; right: -80px; }
        .deco-c3 { width: 200px; height: 200px; top: 50%; left: 50%; transform: translate(-50%, -50%); }

        .brand-content {
            position: relative;
            z-index: 1;
            text-align: center;
            color: white;
        }
        .logos-row {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 2rem;
            margin-bottom: 2.5rem;
        }
        .logo-wrap {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
        }
        .logo-img {
            width: 90px;
            height: 90px;
            object-fit: contain;
            filter: drop-shadow(0 4px 12px rgba(0,0,0,0.3));
            transition: transform 0.3s ease;
        }
        .logo-img:hover { transform: scale(1.05); }
        .logo-divider {
            width: 1px;
            height: 70px;
            background: rgba(255,255,255,0.25);
        }
        .logo-label {
            font-size: 0.65rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: rgba(255,255,255,0.7);
        }
        .brand-title {
            font-size: 1.75rem;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 0.75rem;
        }
        .brand-subtitle {
            font-size: 0.95rem;
            color: rgba(255,255,255,0.75);
            max-width: 320px;
            line-height: 1.6;
        }

        /* Fitur list */
        .feature-list {
            margin-top: 2.5rem;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            text-align: left;
        }
        .feature-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 0.85rem;
            color: rgba(255,255,255,0.8);
        }
        .feature-icon {
            width: 28px;
            height: 28px;
            background: rgba(255,255,255,0.12);
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        /* ── Sisi Kanan: Form Panel ── */
        .form-panel {
            width: 440px;
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 3rem 3rem;
            background: #f8fafc;
            overflow-y: auto;
        }
        .form-header {
            margin-bottom: 2rem;
        }
        .form-header h2 {
            font-size: 1.5rem;
            font-weight: 800;
            color: #0f172a;
            margin: 0 0 0.4rem;
        }
        .form-header p {
            font-size: 0.875rem;
            color: #64748b;
            margin: 0;
        }

        .form-card {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 4px 24px rgba(0,0,0,0.06), 0 1px 4px rgba(0,0,0,0.04);
            border: 1px solid #e2e8f0;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }
        .form-label {
            display: block;
            font-size: 0.8rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.4rem;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }
        .form-input {
            width: 100%;
            padding: 0.7rem 0.875rem;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            font-size: 0.9rem;
            font-family: 'Inter', sans-serif;
            color: #1e293b;
            background: #f8fafc;
            transition: all 0.2s ease;
            outline: none;
        }
        .form-input:focus {
            border-color: #3b82f6;
            background: white;
            box-shadow: 0 0 0 3px rgba(59,130,246,0.1);
        }

        .btn-login {
            width: 100%;
            padding: 0.8rem 1.5rem;
            background: linear-gradient(135deg, #1d4ed8 0%, #2563eb 100%);
            color: white;
            font-weight: 700;
            font-size: 0.9rem;
            font-family: 'Inter', sans-serif;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(29,78,216,0.3);
            margin-top: 0.5rem;
        }
        .btn-login:hover {
            background: linear-gradient(135deg, #1e40af 0%, #1d4ed8 100%);
            box-shadow: 0 6px 16px rgba(29,78,216,0.4);
            transform: translateY(-1px);
        }
        .btn-login:active { transform: translateY(0); }

        .remember-row {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1.25rem;
        }
        .remember-row input { accent-color: #3b82f6; }
        .remember-row label {
            font-size: 0.85rem;
            color: #64748b;
            cursor: pointer;
        }

        .error-text {
            color: #dc2626;
            font-size: 0.8rem;
            margin-top: 0.3rem;
        }
        .alert-status {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            color: #1d4ed8;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            font-size: 0.85rem;
            margin-bottom: 1rem;
        }

        /* Akun demo badge */
        .demo-accounts {
            margin-top: 1.5rem;
            background: #f1f5f9;
            border-radius: 10px;
            padding: 1rem;
        }
        .demo-title {
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #94a3b8;
            margin-bottom: 0.6rem;
        }
        .demo-row {
            display: flex;
            justify-content: space-between;
            font-size: 0.8rem;
            padding: 0.3rem 0;
            border-bottom: 1px solid #e2e8f0;
            color: #475569;
        }
        .demo-row:last-child { border-bottom: none; }
        .demo-role {
            font-weight: 600;
            color: #1d4ed8;
            width: 65px;
        }
        .demo-pass { color: #94a3b8; font-size: 0.75rem; }

        @media (max-width: 768px) {
            body { flex-direction: column; overflow: auto; }
            .brand-panel { padding: 2rem 1.5rem; flex: none; min-height: 260px; }
            .form-panel { width: 100%; padding: 2rem 1.5rem; }
            .logos-row { gap: 1.5rem; }
            .logo-img { width: 65px; height: 65px; }
        }
    </style>
</head>
<body>
    {{-- ── Panel Kiri: Branding ── --}}
    <div class="brand-panel">
        <div class="deco-circle deco-c1"></div>
        <div class="deco-circle deco-c2"></div>
        <div class="deco-circle deco-c3"></div>

        <div class="brand-content">
            {{-- Logo 2 Instansi --}}
            <div class="logos-row">
                <div class="logo-wrap">
                    <img src="{{ asset('images/kemendagri-logo.svg') }}" alt="Logo Kemendagri" class="logo-img">
                    <span class="logo-label">Kemendagri</span>
                </div>
                <div class="logo-divider"></div>
                <div class="logo-wrap">
                    <img src="{{ asset('images/tegal-logo.svg') }}" alt="Logo Kabupaten Tegal" class="logo-img">
                    <span class="logo-label">Kab. Tegal</span>
                </div>
            </div>

            <div class="brand-title">
                Layanan Admas<br>
                <span style="font-size: 1.1rem; font-weight: 600; opacity: 0.85;">Disdukcapil Kab. Tegal</span>
            </div>
            <p class="brand-subtitle">
                Sistem Manajemen Aduan Layanan Administrasi Kependudukan berbasis digital.
            </p>

            <div class="feature-list">
                <div class="feature-item">
                    <div class="feature-icon">
                        <svg width="14" height="14" fill="none" stroke="white" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    Input & Pengelolaan Aduan Masyarakat
                </div>
                <div class="feature-item">
                    <div class="feature-icon">
                        <svg width="14" height="14" fill="none" stroke="white" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                        </svg>
                    </div>
                    Respon & Tindak Lanjut Aduan
                </div>
                <div class="feature-item">
                    <div class="feature-icon">
                        <svg width="14" height="14" fill="none" stroke="white" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
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
            <p>Silakan masuk menggunakan akun yang telah diberikan.</p>
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
                           class="form-input" required autofocus autocomplete="username"
                           placeholder="Masukkan nama pengguna">
                    @error('name')
                        <p class="error-text">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <div x-data="{ show: false }" class="relative flex items-center">
                        <input id="password" :type="show ? 'text' : 'password'" name="password"
                               class="form-input pr-10 w-full" required autocomplete="current-password"
                               placeholder="Masukkan password">
                        <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center justify-center text-slate-400 hover:text-blue-500 focus:outline-none">
                            <svg x-show="!show" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg x-show="show" x-cloak fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="w-5 h-5" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="error-text">{{ $message }}</p>
                    @enderror
                </div>

                <div class="remember-row">
                    <input id="remember_me" type="checkbox" name="remember">
                    <label for="remember_me">Ingat saya</label>
                </div>

                <button type="submit" class="btn-login">
                    Masuk ke Sistem
                </button>
            </form>
        </div>

        {{-- Info akun demo --}}
        <div class="demo-accounts">
            <p class="demo-title">Akun Demo</p>
            <div class="demo-row">
                <span class="demo-role">Admin</span>
                <span>nama: <strong>Admin</strong></span>
                <span class="demo-pass">password123</span>
            </div>
            <div class="demo-row">
                <span class="demo-role">Petugas</span>
                <span>nama: <strong>Petugas</strong></span>
                <span class="demo-pass">password123</span>
            </div>
            <div class="demo-row">
                <span class="demo-role">Pimpinan</span>
                <span>nama: <strong>Pimpinan</strong></span>
                <span class="demo-pass">password123</span>
            </div>
        </div>
    </div>
</body>
</html>

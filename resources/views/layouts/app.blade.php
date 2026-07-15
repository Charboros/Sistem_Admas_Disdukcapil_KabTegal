<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Siladmas — Sistem Laporan Aduan Masyarakat</title>
    <link rel="icon" type="image/png" href="{{ asset('images/tegal-logo.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Inter', sans-serif; }

        /* ── Sidebar ── */
        .sidebar {
            width: 68px;
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            background: linear-gradient(160deg, #1e3a8a 0%, #1d4ed8 60%, #2563eb 100%);
            transition: width 0.25s cubic-bezier(0.4,0,0.2,1);
            overflow: hidden;
            position: relative;
            z-index: 50;
        }
        .sidebar:hover { width: 230px; }

        /* Brand */
        .sidebar-brand {
            height: 64px;
            display: flex;
            align-items: center;
            padding: 0 1rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            flex-shrink: 0;
            gap: 0.75rem;
            white-space: nowrap;
        }
        .brand-icon {
            width: 36px;
            height: 36px;
            flex-shrink: 0;
            border-radius: 10px;
            background: rgba(255,255,255,0.15);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .brand-text {
            opacity: 0;
            transition: opacity 0.15s 0.05s;
            pointer-events: none;
            width: 100%;
        }
        .sidebar:hover .brand-text { opacity: 1; pointer-events: auto; }
        .brand-text p:first-child { font-size: 1.15rem; font-weight: 800; color: white; margin: 0; line-height: 1.2; }
        .brand-text p:last-child { font-size: 0.75rem; color: rgba(255,255,255,0.75); margin: 0; line-height: 1.2; }

        /* Nav */
        .sidebar-nav { flex: 1; padding: 1rem 0.625rem; display: flex; flex-direction: column; gap: 0.25rem; overflow: hidden; }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 0.875rem;
            padding: 0.65rem 0.875rem;
            border-radius: 10px;
            font-size: 0.83rem;
            font-weight: 500;
            color: rgba(255,255,255,0.75);
            text-decoration: none;
            transition: all 0.15s ease;
            white-space: nowrap;
            position: relative;
            min-height: 44px;
        }
        .nav-item:hover {
            background: rgba(255,255,255,0.14);
            color: white;
        }
        .nav-item.active {
            background: rgba(255,255,255,0.18);
            color: white;
            font-weight: 600;
        }
        .nav-icon {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
            opacity: 0.8;
            transition: opacity 0.15s;
        }
        .nav-item:hover .nav-icon,
        .nav-item.active .nav-icon { opacity: 1; }
        .nav-label {
            opacity: 0;
            transition: opacity 0.12s 0.05s;
            pointer-events: none;
        }
        .sidebar:hover .nav-label { opacity: 1; }

        /* Tooltip saat sidebar collapsed */
        .nav-tooltip {
            position: absolute;
            left: calc(100% + 8px);
            top: 50%;
            transform: translateY(-50%);
            background: #1e293b;
            color: white;
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.4rem 0.75rem;
            border-radius: 8px;
            white-space: nowrap;
            pointer-events: none;
            opacity: 0;
            transition: opacity 0.15s;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        }
        .nav-tooltip::before {
            content: '';
            position: absolute;
            right: 100%;
            top: 50%;
            transform: translateY(-50%);
            border: 5px solid transparent;
            border-right-color: #1e293b;
        }
        .nav-item:hover .nav-tooltip { opacity: 1; }
        .sidebar:hover .nav-tooltip { display: none; }

        /* Divider label */
        .nav-section-label {
            font-size: 0.6rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: rgba(255,255,255,0.35);
            padding: 0 0.875rem;
            margin-top: 0.5rem;
            margin-bottom: 0.25rem;
            white-space: nowrap;
            opacity: 0;
            transition: opacity 0.12s;
        }
        .sidebar:hover .nav-section-label { opacity: 1; }

        /* User bottom */
        .sidebar-user {
            padding: 0.875rem 0.625rem;
            border-top: 1px solid rgba(255,255,255,0.1);
            flex-shrink: 0;
        }
        .user-row {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem 0.375rem;
            border-radius: 10px;
            margin-bottom: 0.5rem;
            white-space: nowrap;
        }
        .user-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: rgba(255,255,255,0.18);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.8rem;
            color: white;
            flex-shrink: 0;
        }
        .user-info {
            opacity: 0;
            transition: opacity 0.12s 0.05s;
            pointer-events: none;
        }
        .sidebar:hover .user-info { opacity: 1; }
        .user-info p:first-child { font-size: 0.8rem; font-weight: 600; color: white; margin: 0; }
        .user-info p:last-child {
            font-size: 0.68rem;
            color: rgba(255,255,255,0.6);
            margin: 0;
            text-transform: capitalize;
        }
        .btn-logout {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.5rem;
            border-radius: 10px;
            font-size: 0.78rem;
            font-weight: 600;
            color: rgba(255,255,255,0.7);
            background: rgba(255,255,255,0.08);
            border: none;
            cursor: pointer;
            transition: all 0.15s;
            white-space: nowrap;
        }
        .btn-logout:hover {
            background: rgba(239,68,68,0.6);
            color: white;
        }
        .btn-logout-text { opacity: 0; transition: opacity 0.1s; }
        .sidebar:hover .btn-logout-text { opacity: 1; }

        /* ── Alert Toast ── */
        .alert-toast { animation: slideDown 0.3s ease; }
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-8px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ── Custom Animations ── */
        @keyframes slideUpFade {
            from { opacity: 0; transform: translateY(15px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .animate-slide-up {
            animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            opacity: 0;
        }
        .delay-100 { animation-delay: 100ms; }
        .delay-200 { animation-delay: 200ms; }
        .delay-300 { animation-delay: 300ms; }

        /* ── Custom Scrollbar ── */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(148,163,184,0.4); border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(148,163,184,0.6); }
    </style>
</head>
<body class="antialiased bg-slate-100 flex h-screen overflow-hidden">

    {{-- ══════════════ SIDEBAR ══════════════ --}}
    <aside class="sidebar">

        {{-- Brand --}}
        <div class="sidebar-brand">
            <div class="brand-icon">
                <img src="{{ asset('images/tegal-logo.png') }}" alt="Logo Tegal" class="w-7 h-7 object-contain">
            </div>
            <div class="brand-text flex items-center ml-2">
                <div>
                    <p>Siladmas</p>
                    <p class="whitespace-nowrap overflow-hidden text-ellipsis">Sistem Laporan Aduan</p>
                </div>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="sidebar-nav">
            <span class="nav-section-label">Menu Utama</span>

            {{-- Dashboard --}}
            <a href="{{ route('dashboard') }}"
               class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span class="nav-label">Dashboard</span>
                <span class="nav-tooltip">Dashboard</span>
            </a>

            {{-- Input Aduan --}}
            <a href="{{ route('aduan.create') }}"
               class="nav-item {{ request()->routeIs('aduan.create') ? 'active' : '' }}">
                <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M12 4v16m8-8H4"/>
                </svg>
                <span class="nav-label">Input Aduan</span>
                <span class="nav-tooltip">Input Aduan</span>
            </a>

            {{-- Data Aduan --}}
            <a href="{{ route('aduan.data') }}"
               class="nav-item {{ request()->routeIs('aduan.data') || request()->routeIs('aduan.show') ? 'active' : '' }}">
                <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M4 7h16M4 12h16M4 17h10"/>
                </svg>
                <span class="nav-label">Data Aduan</span>
                <span class="nav-tooltip">Data Aduan</span>
            </a>

            {{-- Rekap --}}
            <a href="{{ route('laporan.index') }}"
               class="nav-item {{ request()->routeIs('laporan.*') ? 'active' : '' }}">
                <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span class="nav-label">Rekap</span>
                <span class="nav-tooltip">Rekap & Export</span>
            </a>


            @if(Auth::user()->isAdmin())
            {{-- Konfigurasi --}}
            <a href="{{ route('konfigurasi.index') }}"
               class="nav-item {{ request()->routeIs('konfigurasi.*') ? 'active' : '' }}">
                <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span class="nav-label">Konfigurasi</span>
                <span class="nav-tooltip">Konfigurasi</span>
            </a>
            @endif
        </nav>

        {{-- User Info + Logout --}}
        <div class="sidebar-user">
            @php
                $user     = Auth::user();
                $initials = strtoupper(substr($user->name, 0, 1));
                $roleLabel = match($user->role) {
                    'admin'   => 'Administrator',
                    'pimpinan' => 'Pimpinan',
                    'petugas' => 'Petugas',
                    default   => ucfirst($user->role),
                };
            @endphp
            <a href="{{ route('profile.edit') }}" class="user-row" style="text-decoration: none;">
                <div class="user-avatar">{{ $initials }}</div>
                <div class="user-info">
                    <p>{{ $user->name }}</p>
                    <p>{{ $roleLabel }}</p>
                </div>
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-logout">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    <span class="btn-logout-text">Keluar</span>
                </button>
            </form>
        </div>
    </aside>

    {{-- ══════════════ MAIN CONTENT ══════════════ --}}
    <div class="flex-1 flex flex-col overflow-hidden">

        {{-- Top Header --}}
        @isset($header)
            <header class="bg-blue-800/90 backdrop-blur-md border-b border-blue-900 shrink-0 sticky top-0 z-40 transition-all duration-300 shadow-md">
                <div class="px-6 py-4">
                    {{ $header }}
                </div>
            </header>
        @endisset

        {{-- Page Content --}}
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-slate-100 p-6 opacity-0 animate-fade-in">

            {{-- Flash success --}}
            @if(session('success'))
                <div class="alert-toast mb-5 flex items-start gap-3 bg-emerald-50 border border-emerald-200
                            text-emerald-800 px-4 py-3 rounded-xl shadow-sm">
                    <svg class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                              d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                              clip-rule="evenodd"/>
                    </svg>
                    <span class="text-sm font-medium">{{ session('success') }}</span>
                    <button onclick="this.parentElement.remove()"
                            class="ml-auto text-emerald-500 hover:text-emerald-700 transition">✕</button>
                </div>
            @endif

            {{-- Flash error --}}
            @if(session('error'))
                <div class="alert-toast mb-5 flex items-start gap-3 bg-red-50 border border-red-200
                            text-red-800 px-4 py-3 rounded-xl shadow-sm">
                    <svg class="w-5 h-5 text-red-500 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                              d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                              clip-rule="evenodd"/>
                    </svg>
                    <span class="text-sm font-medium">{{ session('error') }}</span>
                    <button onclick="this.parentElement.remove()"
                            class="ml-auto text-red-500 hover:text-red-700 transition">✕</button>
                </div>
            @endif

            {{ $slot }}
        </main>
    </div>

</body>
</html>

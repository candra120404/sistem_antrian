<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard Admin') — Sistem Antrian Bengkel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: { DEFAULT: '#1E3A5F', light: '#2A4F80', dark: '#152B46' },
                    }
                }
            }
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
        .sidebar-link { @apply flex items-center gap-3 px-4 py-3 rounded-lg text-blue-100 hover:bg-white/10 transition-all duration-200; }
        .sidebar-link.active { @apply bg-white/20 text-white font-semibold; }
    </style>
</head>
<body class="bg-gray-50 font-sans">

<div class="flex min-h-screen">
    {{-- ── Sidebar ── --}}
    <aside class="w-64 bg-primary text-white flex flex-col fixed h-full shadow-xl z-20">
        {{-- Logo --}}
        <div class="p-6 border-b border-white/10">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center">
                    <span class="text-primary text-xl font-black">🔧</span>
                </div>
                <div>
                    <p class="font-bold text-sm leading-tight">Sistem Antrian</p>
                    <p class="text-blue-200 text-xs">Bengkel Digital</p>
                </div>
            </div>
        </div>

        {{-- Navigasi --}}
        <nav class="flex-1 p-4 space-y-1">
            <p class="text-xs text-blue-300 uppercase tracking-wider font-semibold px-4 py-2">Menu Utama</p>

            <a href="{{ route('admin.dashboard') }}"
               class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <span class="text-lg">📊</span>
                <span>Dashboard Antrian</span>
                @if($badgeMenunggu ?? 0 > 0)
                    <span class="ml-auto bg-yellow-400 text-yellow-900 text-xs font-bold px-2 py-0.5 rounded-full">
                        {{ $badgeMenunggu ?? 0 }}
                    </span>
                @endif
            </a>

            <a href="{{ route('admin.harga.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.harga.*') ? 'active' : '' }}">
                <span class="text-lg">💰</span>
                <span>Kelola Harga</span>
            </a>

            <a href="{{ route('admin.data-harian.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.data-harian.*') ? 'active' : '' }}">
                <span class="text-lg">📋</span>
                <span>Data Harian</span>
            </a>
        </nav>

        {{-- User Info --}}
        <div class="p-4 border-t border-white/10">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-9 h-9 bg-white/20 rounded-full flex items-center justify-center">
                    <span class="text-sm font-bold">{{ substr(auth()->user()->name, 0, 1) }}</span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-blue-300">Administrator</p>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit"
                        class="w-full flex items-center gap-2 px-3 py-2 text-sm text-red-300 hover:text-white hover:bg-red-500/20 rounded-lg transition-all duration-200">
                    <span>🚪</span> Keluar
                </button>
            </form>
        </div>
    </aside>

    {{-- ── Main Content ── --}}
    <main class="flex-1 ml-64">
        {{-- Header --}}
        <header class="bg-white border-b border-gray-200 px-8 py-4 flex items-center justify-between sticky top-0 z-10 shadow-sm">
            <div>
                <h1 class="text-xl font-bold text-gray-800">@yield('page-title', 'Dashboard')</h1>
                <p class="text-sm text-gray-500">{{ now()->translatedFormat('l, d F Y') }}</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-sm text-gray-500">{{ now()->format('H:i') }} WIB</span>
                <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse" title="Online"></div>
            </div>
        </header>

        {{-- Flash Message --}}
        <div class="px-8 pt-4">
            @if(session('success'))
                <div class="flex items-center gap-3 p-4 bg-green-50 border border-green-200 text-green-800 rounded-xl mb-4">
                    <span class="text-lg">✅</span>
                    <span class="text-sm font-medium">{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="flex items-center gap-3 p-4 bg-red-50 border border-red-200 text-red-800 rounded-xl mb-4">
                    <span class="text-lg">❌</span>
                    <span class="text-sm font-medium">{{ session('error') }}</span>
                </div>
            @endif
        </div>

        {{-- Page Content --}}
        <div class="p-8">
            @yield('content')
        </div>
    </main>
</div>

@stack('scripts')
</body>
</html>

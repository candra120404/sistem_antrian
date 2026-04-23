<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — Bengkel Digital</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: { DEFAULT: '#1E3A5F', light: '#2A4F80' },
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 font-sans">

{{-- ── Header ── --}}
<header class="bg-primary text-white shadow-md sticky top-0 z-20">
    <div class="max-w-lg mx-auto px-4 py-3 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <span class="text-2xl">🔧</span>
            <div>
                <p class="font-bold text-sm leading-tight">Bengkel Digital</p>
                <p class="text-blue-200 text-xs">Sistem Antrian</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <div class="text-right">
                <p class="text-sm font-semibold">{{ auth()->user()->name }}</p>
                <p class="text-xs text-blue-300">Pelanggan</p>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit"
                        class="p-2 hover:bg-white/10 rounded-lg transition-all"
                        title="Keluar">
                    🚪
                </button>
            </form>
        </div>
    </div>
</header>

{{-- ── Flash Message ── --}}
<div class="max-w-lg mx-auto px-4 pt-4">
    @if(session('success'))
        <div class="flex items-center gap-3 p-4 bg-green-50 border border-green-200 text-green-800 rounded-xl mb-4">
            <span>✅</span>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="flex items-center gap-3 p-4 bg-red-50 border border-red-200 text-red-800 rounded-xl mb-4">
            <span>❌</span>
            <span class="text-sm font-medium">{{ session('error') }}</span>
        </div>
    @endif
    @if(session('info'))
        <div class="flex items-center gap-3 p-4 bg-blue-50 border border-blue-200 text-blue-800 rounded-xl mb-4">
            <span>ℹ️</span>
            <span class="text-sm font-medium">{{ session('info') }}</span>
        </div>
    @endif
</div>

{{-- ── Content ── --}}
<main class="max-w-lg mx-auto px-4 pb-24">
    @yield('content')
</main>

{{-- ── Bottom Navigation ── --}}
<nav class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 shadow-lg z-20">
    <div class="max-w-lg mx-auto flex">
        <a href="{{ route('pelanggan.dashboard') }}"
           class="flex-1 flex flex-col items-center py-3 gap-1 text-xs
                  {{ request()->routeIs('pelanggan.dashboard') ? 'text-primary font-semibold' : 'text-gray-500' }}">
            <span class="text-xl">🏠</span>
            <span>Beranda</span>
        </a>
        <a href="{{ route('pelanggan.antrian.status') }}"
           class="flex-1 flex flex-col items-center py-3 gap-1 text-xs
                  {{ request()->routeIs('pelanggan.antrian.status') ? 'text-primary font-semibold' : 'text-gray-500' }}">
            <span class="text-xl">🎫</span>
            <span>Status Antrian</span>
        </a>
    </div>
</nav>

@stack('scripts')
</body>
</html>

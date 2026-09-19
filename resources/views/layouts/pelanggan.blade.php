<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="theme-color" content="#ffffff">
    <meta name="mobile-web-app-capable" content="yes">
    <title>@yield('title', 'Bengkel Digital')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: { DEFAULT: '#0F172A', light: '#1E293B', dark: '#020617' },
                        brand: { DEFAULT: '#3B82F6', hover: '#2563EB', light: '#EFF6FF', soft: '#DBEAFE' },
                        surface: { DEFAULT: '#F8FAFC', elevated: '#FFFFFF' }
                    },
                    borderRadius: {
                        '2xl': '1rem',
                        '3xl': '1.5rem',
                        '4xl': '2rem',
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        * { -webkit-tap-highlight-color: transparent; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; overscroll-behavior-y: none; }
        .safe-top { padding-top: env(safe-area-inset-top); }
        .safe-bottom { padding-bottom: env(safe-area-inset-bottom); }
        .nav-item { @apply flex-1 flex flex-col items-center py-2 gap-1 text-[11px] font-bold transition-all duration-200; }
        .nav-item.active { @apply text-brand; }
        .nav-item.inactive { @apply text-slate-400; }
        .btn-press { @apply transition-all active:scale-95 active:opacity-90; }
        .card-elevated {
            @apply bg-white rounded-2xl shadow-[0_2px_16px_rgba(15,23,42,0.06)] border border-slate-100;
        }
        .gradient-warm { background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); }
        .gradient-cool { background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%); }
        .gradient-brand { background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%); }
        .shimmer {
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
            background-size: 200% 100%;
            animation: shimmer 2s infinite;
        }
        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-up { animation: fadeUp 0.4s ease-out forwards; }
        .animate-fade-up-1 { animation: fadeUp 0.4s ease-out 0.05s forwards; opacity: 0; }
        .animate-fade-up-2 { animation: fadeUp 0.4s ease-out 0.1s forwards; opacity: 0; }
        .animate-fade-up-3 { animation: fadeUp 0.4s ease-out 0.15s forwards; opacity: 0; }
    </style>
</head>
<body class="bg-surface text-slate-900 antialiased">

{{-- ── Header ── --}}
<header class="bg-white/80 backdrop-blur-xl border-b border-slate-100 sticky top-0 z-30 safe-top">
    <div class="max-w-lg mx-auto px-5 h-14 flex items-center justify-between">
        <div class="flex items-center gap-2.5">
            <div class="w-8 h-8 rounded-lg gradient-brand flex items-center justify-center shadow-md shadow-brand/20">
                <i class="fa-solid fa-bolt text-white text-sm"></i>
            </div>
            <h1 class="text-sm font-extrabold tracking-tight text-slate-800">Bengkel<span class="text-brand">Pro</span></h1>
        </div>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="p-2 -mr-2 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-xl transition-colors">
                <i class="fa-solid fa-arrow-right-from-bracket text-sm"></i>
            </button>
        </form>
    </div>
</header>

{{-- ── Main Content ── --}}
<main class="max-w-lg mx-auto px-5 pt-5 pb-28 safe-bottom">
    @if(session('success'))
        <div class="mb-5 p-4 bg-emerald-50 border border-emerald-100 rounded-2xl flex items-center gap-3 animate-fade-up">
            <div class="w-8 h-8 bg-emerald-500 rounded-lg flex items-center justify-center shrink-0">
                <i class="fa-solid fa-check text-white text-xs"></i>
            </div>
            <p class="text-sm font-semibold text-emerald-800">{{ session('success') }}</p>
        </div>
    @endif
    
    @if(session('error'))
        <div class="mb-5 p-4 bg-red-50 border border-red-100 rounded-2xl flex items-center gap-3 animate-fade-up">
            <div class="w-8 h-8 bg-red-500 rounded-lg flex items-center justify-center shrink-0">
                <i class="fa-solid fa-xmark text-white text-xs"></i>
            </div>
            <p class="text-sm font-semibold text-red-800">{{ session('error') }}</p>
        </div>
    @endif

    @yield('content')
</main>

{{-- ── Bottom Navigation ── --}}
<nav class="fixed bottom-0 left-0 right-0 bg-white/90 backdrop-blur-xl border-t border-slate-100 z-40 safe-bottom">
    <div class="max-w-lg mx-auto flex items-center justify-around h-16 px-2">
        <a href="{{ route('pelanggan.dashboard') }}"
           class="nav-item {{ request()->routeIs('pelanggan.dashboard') ? 'active' : 'inactive' }}">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center {{ request()->routeIs('pelanggan.dashboard') ? 'bg-brand/10' : '' }}">
                <i class="fa-solid fa-house text-lg"></i>
            </div>
            <span>Beranda</span>
        </a>
        <a href="{{ route('pelanggan.antrian.status') }}"
           class="nav-item {{ request()->routeIs('pelanggan.antrian.*') ? 'active' : 'inactive' }}">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center {{ request()->routeIs('pelanggan.antrian.*') ? 'bg-brand/10' : '' }}">
                <i class="fa-solid fa-ticket text-lg"></i>
            </div>
            <span>Antrian</span>
        </a>
    </div>
</nav>

@stack('scripts')
</body>
</html>

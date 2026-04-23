<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Bengkel Digital')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: { DEFAULT: '#0F172A', light: '#1E293B' },
                        brand: { DEFAULT: '#3B82F6', light: '#EFF6FF' }
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .nav-item { @apply flex-1 flex flex-col items-center py-4 gap-1 text-[10px] font-black uppercase tracking-widest transition-all; }
        .nav-item.active { @apply text-brand; }
        .nav-item.inactive { @apply text-slate-400; }
    </style>
</head>
<body class="bg-[#fcfdfe] text-slate-900 pb-28">

{{-- ── Mobile Header ── --}}
<header class="bg-white border-b border-slate-50 sticky top-0 z-30 px-6 py-4 flex items-center justify-between">
    <div class="flex items-center gap-3">
        <div class="w-8 h-8 bg-brand rounded-lg flex items-center justify-center">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path></svg>
        </div>
        <h1 class="text-sm font-black tracking-tight uppercase">Bengkel<span class="text-brand">PRO</span></h1>
    </div>
    <div class="flex items-center gap-4">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="p-2 bg-slate-50 rounded-xl text-slate-400">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
            </button>
        </form>
    </div>
</header>

{{-- ── Main Content Area ── --}}
<main class="max-w-lg mx-auto p-6">
    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 rounded-3xl flex items-center gap-3 animate-fade-in">
             <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
             <p class="text-xs font-bold text-emerald-800">{{ session('success') }}</p>
        </div>
    @endif

    @yield('content')
</main>

{{-- ── Bottom Navigation ── --}}
<nav class="fixed bottom-0 left-0 right-0 bg-white border-t border-slate-50 shadow-[0_-8px_40px_rgba(0,0,0,0.04)] z-30">
    <div class="max-w-lg mx-auto flex items-center justify-around h-20 px-4">
        <a href="{{ route('pelanggan.dashboard') }}"
           class="nav-item {{ request()->routeIs('pelanggan.dashboard') ? 'active' : 'inactive' }}">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="{{ request()->routeIs('pelanggan.dashboard') ? '2.5' : '2' }}" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
            <span>Home</span>
        </a>
        <a href="{{ route('pelanggan.antrian.status') }}"
           class="nav-item {{ request()->routeIs('pelanggan.antrian.*') ? 'active' : 'inactive' }}">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="{{ request()->routeIs('pelanggan.antrian.*') ? '2.5' : '2' }}" stroke-linecap="round" stroke-linejoin="round"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path><rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect></svg>
            <span>Antrian</span>
        </a>
    </div>
</nav>

@stack('scripts')
</body>
</html>

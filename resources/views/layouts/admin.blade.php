<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') — Bengkel Digital</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: { DEFAULT: '#0F172A', light: '#1E293B', dark: '#020617' },
                        brand: { DEFAULT: '#3B82F6', hover: '#2563EB' }
                    },
                    borderRadius: {
                        '3xl': '1.5rem',
                        '4xl': '2rem',
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .sidebar-item { @apply flex items-center gap-3 px-4 py-3.5 rounded-2xl text-slate-400 hover:text-white hover:bg-white/5 transition-all duration-300; }
        .sidebar-item.active { @apply bg-brand text-white shadow-lg shadow-brand/20; }
        .glass-card { @apply bg-white border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] backdrop-blur-sm; }
    </style>
</head>
<body class="bg-[#fafbfc] text-slate-900 overflow-x-hidden">

<div class="flex min-h-screen">
    {{-- ── Sidebar ── --}}
    <aside class="w-72 bg-primary text-white flex flex-col fixed h-full z-30 transition-all">
        {{-- Logo Area --}}
        <div class="p-8">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-brand rounded-xl flex items-center justify-center shadow-lg shadow-brand/30">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 21.35V17M12 7V3M21.35 12H17M7 12H3M18.8 18.8L15.9 15.9M8.1 8.1L5.2 5.2M18.8 5.2L15.9 8.1M8.1 18.8L5.2 15.9" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <span class="text-xl font-bold tracking-tight">Bengkel<span class="text-brand">PRO</span></span>
            </div>
        </div>

        {{-- Navigasi --}}
        <nav class="flex-1 px-6 space-y-2 mt-4">
            <p class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] px-4 mb-4">Main Menu</p>

            <a href="{{ route('admin.dashboard') }}"
               class="sidebar-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect>
                </svg>
                <span class="text-sm font-semibold">Dashboard</span>
            </a>

            <a href="{{ route('admin.harga.index') }}"
               class="sidebar-item {{ request()->routeIs('admin.harga.*') ? 'active' : '' }}">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 1v22m5-18H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                </svg>
                <span class="text-sm font-semibold">Kelola Harga</span>
            </a>

            <a href="{{ route('admin.data-harian.index') }}"
               class="sidebar-item {{ request()->routeIs('admin.data-harian.*') ? 'active' : '' }}">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline>
                </svg>
                <span class="text-sm font-semibold">Laporan Harian</span>
            </a>
        </nav>

        {{-- Logout --}}
        <div class="p-6">
            <div class="bg-slate-800/50 rounded-3xl p-4 mb-4">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 bg-slate-700 rounded-2xl flex items-center justify-center text-xs font-bold text-white border border-slate-600">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold truncate">{{ auth()->user()->name }}</p>
                        <p class="text-[10px] text-slate-500 font-medium">Administrator</p>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 py-2.5 text-xs font-bold text-red-400 hover:text-white hover:bg-red-500/20 rounded-xl transition-all border border-red-500/10">
                        Keluar Akun
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- ── Main Content ── --}}
    <main class="flex-1 ml-72 p-8 lg:p-12">
        {{-- Header Desktop --}}
        <header class="flex flex-col md:flex-row md:items-center justify-between mb-12 gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">@yield('page-title', 'Overview')</h1>
                <p class="text-slate-400 text-sm mt-1 font-medium">{{ now()->translatedFormat('l, d F Y') }}</p>
            </div>
            <div class="flex items-center gap-4">
                <div class="bg-white border border-slate-100 rounded-2xl px-5 py-2.5 flex items-center gap-3 shadow-sm">
                    <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                    <span class="text-xs font-bold text-slate-600 uppercase tracking-wider">System Live</span>
                </div>
            </div>
        </header>

        {{-- Flash --}}
        @if(session('success'))
            <div class="mb-8 flex items-center gap-4 p-5 bg-emerald-50 border border-emerald-100 rounded-3xl animate-fade-in">
                <div class="w-10 h-10 bg-emerald-500 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-emerald-500/20">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-emerald-900">Success</h4>
                    <p class="text-xs text-emerald-700 font-medium">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        {{-- Content Area --}}
        <div class="relative z-10">
            @yield('content')
        </div>
    </main>
</div>

@stack('scripts')
</body>
</html>

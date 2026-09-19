<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sales Report') — DealDeck Antrian</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: { DEFAULT: '#3B82F6', hover: '#2563EB', light: '#EFF6FF', soft: '#DBEAFE' },
                        dealblue: { DEFAULT: '#3B82F6', dark: '#1D4ED8', light: '#60A5FA' },
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
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap');
        body { font-family: 'Plus Jakarta Sans', 'Inter', sans-serif; background-color: #f3f4f8; }
        [x-cloak] { display: none !important; }

        /* DealDeck custom scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 12px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        .deal-card {
            background: #ffffff;
            border-radius: 1.5rem;
            border: 1px solid rgba(226, 232, 240, 0.7);
            box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.03);
            transition: all 0.2s ease;
        }

        .search-kbd {
            font-family: 'Inter', monospace;
            font-size: 10px;
            font-weight: 600;
            padding: 2px 6px;
            border-radius: 4px;
            background: #fff;
            border: 1px solid #e2e8f0;
            color: #64748b;
        }
    </style>
</head>
<body class="bg-[#f3f4f8] text-slate-800 antialiased min-h-screen" x-data="{ sidebarOpen: false }">

    <div class="min-h-screen flex p-3 sm:p-5 lg:p-6 gap-6">

        {{-- ── Mobile Overlay ── --}}
        <div x-show="sidebarOpen" 
             x-cloak
             @click="sidebarOpen = false"
             class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-40 lg:hidden">
        </div>

        {{-- ── Sidebar DealDeck White Style ── --}}
        <aside class="fixed lg:static inset-y-0 left-0 z-50 w-64 bg-white rounded-3xl p-5 border border-slate-200/60 shadow-xl lg:shadow-none flex flex-col justify-between shrink-0 transition-transform duration-300 ease-in-out"
               :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'">
            
            <div class="space-y-6">
                {{-- Logo DealDeck --}}
                <div class="flex items-center gap-3 px-2 py-2">
                    <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-blue-700 via-brand to-indigo-500 flex items-center justify-center text-white shadow-lg shadow-brand/30">
                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z"/>
                            <path d="M8 12h8"/>
                            <path d="M12 8v8"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-slate-900 font-extrabold text-lg tracking-tight leading-none">Deal<span class="text-brand">Deck</span></h1>
                        <p class="text-[10px] text-slate-400 font-semibold mt-0.5 uppercase tracking-wider">Antrian Bengkel</p>
                    </div>
                </div>

                {{-- Navigation Groups --}}
                <nav class="space-y-6">
                    {{-- MENU --}}
                    <div>
                        <p class="px-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2.5">MENU</p>
                        <div class="space-y-1.5">
                            <a href="{{ route('admin.dashboard') }}" 
                               class="flex items-center gap-3 px-3.5 py-2.5 rounded-2xl text-xs font-bold transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-brand text-white shadow-lg shadow-brand/25' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-100/70' }}">
                                <i class="fa-solid fa-square-poll-vertical text-sm w-4 text-center"></i>
                                <span>Dashboard</span>
                            </a>
                            
                            <a href="{{ route('admin.laporan.index') }}" 
                               class="flex items-center gap-3 px-3.5 py-2.5 rounded-2xl text-xs font-bold transition-all {{ request()->routeIs('admin.laporan.*') ? 'bg-brand text-white shadow-lg shadow-brand/25' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-100/70' }}">
                                <i class="fa-solid fa-chart-pie text-sm w-4 text-center"></i>
                                <span>Report</span>
                            </a>
                            
                            <a href="{{ route('admin.harga.index') }}" 
                               class="flex items-center gap-3 px-3.5 py-2.5 rounded-2xl text-xs font-bold transition-all {{ request()->routeIs('admin.harga.*') ? 'bg-brand text-white shadow-lg shadow-brand/25' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-100/70' }}">
                                <i class="fa-solid fa-box text-sm w-4 text-center"></i>
                                <span>Products & Harga</span>
                            </a>
                        </div>
                    </div>

                    {{-- FINANCIAL --}}
                    <div>
                        <p class="px-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2.5">FINANCIAL</p>
                        <div class="space-y-1.5">
                            <a href="{{ route('admin.data-harian.index') }}" 
                               class="flex items-center gap-3 px-3.5 py-2.5 rounded-2xl text-xs font-bold transition-all {{ request()->routeIs('admin.data-harian.*') ? 'bg-brand text-white shadow-lg shadow-brand/25' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-100/70' }}">
                                <i class="fa-solid fa-receipt text-sm w-4 text-center"></i>
                                <span>Transactions</span>
                            </a>
                        </div>
                    </div>

                    {{-- TOOLS --}}
                    <div>
                        <p class="px-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2.5">TOOLS</p>
                        <div class="space-y-1.5">
                            <a href="{{ route('admin.pengaturan.index') }}" 
                               class="flex items-center gap-3 px-3.5 py-2.5 rounded-2xl text-xs font-bold transition-all {{ request()->routeIs('admin.pengaturan.*') ? 'bg-brand text-white shadow-lg shadow-brand/25' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-100/70' }}">
                                <i class="fa-solid fa-gear text-sm w-4 text-center"></i>
                                <span>Settings</span>
                            </a>
                        </div>
                    </div>
                </nav>
            </div>

            {{-- Bottom Upgrade Card (DealDeck Promo Box) --}}
            <div class="mt-6">
                <div class="bg-slate-900 rounded-2xl p-4 text-white relative overflow-hidden shadow-lg shadow-slate-900/10">
                    <div class="w-8 h-8 rounded-xl bg-white/10 flex items-center justify-center mb-3 text-white">
                        <i class="fa-solid fa-rocket text-xs"></i>
                    </div>
                    <h4 class="text-xs font-bold tracking-tight">Upgrade Pro</h4>
                    <p class="text-[10px] text-slate-400 mt-1 leading-relaxed">Kelola batas kuota & performa sistem antrian.</p>
                    <a href="{{ route('admin.pengaturan.index') }}" class="mt-3 w-full py-2 bg-brand hover:bg-brand-hover text-white text-[11px] font-bold rounded-xl flex items-center justify-center transition-all shadow-md shadow-brand/20">
                        Atur Kuota Rp0
                    </a>
                </div>
            </div>
        </aside>

        {{-- ── Main Content Area ── --}}
        <div class="flex-1 min-w-0 flex flex-col space-y-6">

            {{-- Header Top Bar --}}
            <header class="flex items-center justify-between gap-4">
                {{-- Mobile Toggle & Page Title --}}
                <div class="flex items-center gap-3">
                    <button @click="sidebarOpen = !sidebarOpen" 
                            class="lg:hidden p-2.5 bg-white border border-slate-200/60 rounded-2xl text-slate-600 hover:text-slate-900 shadow-sm">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">@yield('page-title', 'Sales Report')</h1>
                        <p class="text-xs font-semibold text-slate-400 mt-0.5 flex items-center gap-1.5">
                            <i class="fa-regular fa-calendar text-[11px]"></i>
                            {{ now()->translatedFormat('l, F jS Y') }}
                        </p>
                    </div>
                </div>

                {{-- Right Top Profile & Tools --}}
                <div class="flex items-center gap-3">
                    {{-- Search Icon Button --}}
                    <button class="w-10 h-10 rounded-full bg-white border border-slate-200/60 flex items-center justify-center text-slate-600 hover:text-slate-900 shadow-sm hover:shadow transition-all"
                            title="Search">
                        <i class="fa-solid fa-magnifying-glass text-sm"></i>
                    </button>

                    {{-- Notification Bell --}}
                    <button class="w-10 h-10 rounded-full bg-white border border-slate-200/60 flex items-center justify-center text-slate-600 hover:text-slate-900 shadow-sm hover:shadow transition-all relative"
                            title="Notifications">
                        <i class="fa-regular fa-bell text-sm"></i>
                        <span class="absolute top-2.5 right-2.5 w-2 h-2 bg-red-500 rounded-full ring-2 ring-white"></span>
                    </button>

                    {{-- User Profile Card --}}
                    <div class="flex items-center gap-3 pl-2">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-brand to-indigo-600 flex items-center justify-center text-white font-bold text-sm shadow-md ring-2 ring-white">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <div class="hidden sm:block text-left">
                            <p class="text-xs font-bold text-slate-900 leading-tight">{{ auth()->user()->name }}</p>
                            <p class="text-[10px] font-semibold text-slate-400">Admin store</p>
                        </div>
                        
                        <form action="{{ route('logout') }}" method="POST" class="ml-1">
                            @csrf
                            <button type="submit" class="p-2 text-slate-400 hover:text-red-500 transition-colors" title="Logout">
                                <i class="fa-solid fa-arrow-right-from-bracket text-xs"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            {{-- Flash Notification Alerts --}}
            @if(session('success'))
                <div class="deal-card p-4 border-l-4 border-l-emerald-500 flex items-center gap-3 animate-fade-in">
                    <div class="w-7 h-7 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center font-bold text-xs">
                        <i class="fa-solid fa-check"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-900">Success</p>
                        <p class="text-xs text-slate-500">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="deal-card p-4 border-l-4 border-l-red-500 flex items-center gap-3 animate-fade-in">
                    <div class="w-7 h-7 rounded-full bg-red-100 text-red-600 flex items-center justify-center font-bold text-xs">
                        <i class="fa-solid fa-xmark"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-900">Error</p>
                        <p class="text-xs text-slate-500">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            {{-- Page View Content --}}
            <div class="flex-1">
                @yield('content')
            </div>

        </div>

    </div>

@stack('scripts')
</body>
</html>

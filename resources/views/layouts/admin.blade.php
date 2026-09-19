<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') — Bengkel Digital</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: { DEFAULT: '#0F172A', light: '#1E293B', dark: '#020617', softer: '#1e293b' },
                        brand: { DEFAULT: '#3B82F6', hover: '#2563EB', light: '#EFF6FF', soft: '#DBEAFE' }
                    },
                    borderRadius: {
                        '3xl': '1.5rem',
                        '4xl': '2rem',
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.3s ease-out forwards',
                        'slide-up': 'slideUp 0.4s ease-out forwards',
                        'scale-up': 'scaleUp 0.2s ease-out forwards',
                    },
                    keyframes: {
                        fadeIn: { '0%': { opacity: '0' }, '100%': { opacity: '1' } },
                        slideUp: { '0%': { transform: 'translateY(10px)', opacity: '0' }, '100%': { transform: 'translateY(0)', opacity: '1' } },
                        scaleUp: { '0%': { transform: 'scale(0.95)', opacity: '0' }, '100%': { transform: 'scale(1)', opacity: '1' } },
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
        body { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        
        .glass-card { 
            background: rgba(255,255,255,0.7); 
            backdrop-filter: blur(12px); 
            border: 1px solid rgba(255,255,255,0.5); 
            box-shadow: 0 4px 24px rgba(0,0,0,0.03); 
        }
        
        .nav-item {
            position: relative;
            transition: all 0.2s ease;
        }
        .nav-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 3px;
            height: 0;
            background: #3B82F6;
            border-radius: 0 4px 4px 0;
            transition: height 0.2s ease;
        }
        .nav-item:hover::before {
            height: 20px;
        }
        .nav-item.active::before {
            height: 32px;
        }
        .nav-item.active {
            background: linear-gradient(90deg, rgba(59,130,246,0.1) 0%, rgba(59,130,246,0.02) 100%);
            color: #3B82F6;
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
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }
        
        .gradient-sidebar {
            background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%);
        }
        
        .mobile-menu-enter { animation: slideInLeft 0.3s ease forwards; }
        .mobile-menu-exit { animation: slideOutLeft 0.3s ease forwards; }
        @keyframes slideInLeft {
            from { transform: translateX(-100%); }
            to { transform: translateX(0); }
        }
        @keyframes slideOutLeft {
            from { transform: translateX(0); }
            to { transform: translateX(-100%); }
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased" x-data="{ sidebarOpen: false }">

    {{-- ── Overlay Mobile ── --}}
    <div x-show="sidebarOpen" 
         x-cloak
         x-transition:enter="transition-opacity ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="sidebarOpen = false"
         class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-40 lg:hidden"
         aria-hidden="true">
    </div>

    {{-- ── Sidebar ── --}}
    <aside class="fixed inset-y-0 left-0 z-50 w-64 gradient-sidebar text-slate-300 flex flex-col border-r border-slate-700/50 shadow-2xl shadow-primary/20"
           :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
           class="transition-transform duration-300 ease-in-out">
        
        {{-- Logo --}}
        <div class="flex items-center gap-3 px-6 py-5 border-b border-slate-700/30">
            <div class="w-9 h-9 rounded-lg bg-brand flex items-center justify-center shadow-lg shadow-brand/20">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 2L2 7l10 5 10-5-10-5z"/>
                    <path d="M2 17l10 5 10-5"/>
                    <path d="M2 12l10 5 10-5"/>
                </svg>
            </div>
            <div>
                <h1 class="text-white font-bold text-base tracking-tight leading-none">Bengkel<span class="text-brand">Pro</span></h1>
                <p class="text-[10px] text-slate-500 font-medium mt-0.5">Sistem Antrian Digital</p>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
            <p class="px-3 text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-3">Menu Utama</p>
            
            <a href="{{ route('admin.dashboard') }}" 
               class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.dashboard') ? 'active text-brand bg-brand/5' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                <i class="fa-solid fa-chart-pie w-5 text-center {{ request()->routeIs('admin.dashboard') ? 'text-brand' : '' }}"></i>
                <span>Dashboard</span>
            </a>
            
            <a href="{{ route('admin.harga.index') }}" 
               class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.harga.*') ? 'active text-brand bg-brand/5' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                <i class="fa-solid fa-tags w-5 text-center {{ request()->routeIs('admin.harga.*') ? 'text-brand' : '' }}"></i>
                <span>Kelola Harga</span>
            </a>
            
            <a href="{{ route('admin.data-harian.index') }}" 
               class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.data-harian.*') ? 'active text-brand bg-brand/5' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                <i class="fa-solid fa-file-invoice-dollar w-5 text-center {{ request()->routeIs('admin.data-harian.*') ? 'text-brand' : '' }}"></i>
                <span>Laporan Harian</span>
            </a>

            <a href="{{ route('admin.laporan.index') }}" 
               class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.laporan.*') ? 'active text-brand bg-brand/5' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                <i class="fa-solid fa-chart-column w-5 text-center {{ request()->routeIs('admin.laporan.*') ? 'text-brand' : '' }}"></i>
                <span>Laporan Keseluruhan</span>
            </a>

            <a href="{{ route('admin.pengaturan.index') }}" 
               class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.pengaturan.*') ? 'active text-brand bg-brand/5' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                <i class="fa-solid fa-sliders w-5 text-center {{ request()->routeIs('admin.pengaturan.*') ? 'text-brand' : '' }}"></i>
                <span>Pengaturan Kuota</span>
            </a>
        </nav>

        {{-- User Profile --}}
        <div class="p-4 border-t border-slate-700/30">
            <div class="bg-slate-800/50 rounded-xl p-3">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-brand to-blue-600 flex items-center justify-center text-white font-bold text-xs shadow-md">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-white truncate">{{ auth()->user()->name }}</p>
                        <p class="text-[10px] text-slate-500 font-medium">Administrator</p>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 py-2 text-xs font-semibold text-slate-400 hover:text-red-400 hover:bg-red-500/10 rounded-lg transition-colors">
                        <i class="fa-solid fa-arrow-right-from-bracket text-xs"></i>
                        Keluar
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- ── Main Content ── --}}
    <main class="lg:ml-64 min-h-screen flex flex-col"
          x-data="{ 
              search: '', 
              openSearch: false,
              menuItems: [
                  { name: 'Dashboard', url: '{{ route('admin.dashboard') }}', icon: 'chart-pie', desc: 'Overview antrian & statistik' },
                  { name: 'Kelola Harga', url: '{{ route('admin.harga.index') }}', icon: 'tags', desc: 'Konfigurasi harga & jenis cuci' },
                  { name: 'Laporan Harian', url: '{{ route('admin.data-harian.index') }}', icon: 'file-invoice-dollar', desc: 'Data finansial harian' },
                  { name: 'Laporan Keseluruhan', url: '{{ route('admin.laporan.index') }}', icon: 'chart-column', desc: 'Rekapitulasi laporan periodik' },
                  { name: 'Pengaturan Kuota', url: '{{ route('admin.pengaturan.index') }}', icon: 'sliders', desc: 'Batas maksimal harian & limit booking' }
              ],
              filteredItems() {
                  if (this.search === '') return this.menuItems;
                  return this.menuItems.filter(item => 
                      item.name.toLowerCase().includes(this.search.toLowerCase()) ||
                      item.desc.toLowerCase().includes(this.search.toLowerCase())
                  );
              }
          }" 
          @keydown.window.ctrl.k.prevent="openSearch = true"
          @keydown.window.meta.k.prevent="openSearch = true">
        
        {{-- Top Header --}}
        <header class="sticky top-0 z-30 bg-white/80 backdrop-blur-xl border-b border-slate-200/60 px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                {{-- Left: Hamburger + Breadcrumb --}}
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = !sidebarOpen" 
                            class="lg:hidden p-2 -ml-2 text-slate-500 hover:text-slate-700 hover:bg-slate-100 rounded-lg transition-colors">
                        <i class="fa-solid fa-bars text-lg"></i>
                    </button>
                    
                    <div class="hidden sm:flex items-center gap-2 text-sm">
                        <span class="text-slate-400 font-medium">Admin</span>
                        <i class="fa-solid fa-chevron-right text-[10px] text-slate-300"></i>
                        <span class="text-slate-700 font-semibold">@yield('page-title', 'Overview')</span>
                    </div>
                </div>

                {{-- Center: Search Trigger --}}
                <button @click="openSearch = true" 
                        class="flex-1 max-w-md mx-4 hidden sm:flex items-center gap-3 px-4 py-2 bg-slate-100/80 hover:bg-slate-100 border border-slate-200/60 rounded-lg text-slate-400 hover:text-slate-600 transition-all group">
                    <i class="fa-solid fa-magnifying-glass text-sm"></i>
                    <span class="text-sm font-medium flex-1 text-left">Cari menu atau fitur...</span>
                    <div class="flex items-center gap-1">
                        <kbd class="search-kbd">CTRL</kbd>
                        <kbd class="search-kbd">K</kbd>
                    </div>
                </button>
                
                {{-- Mobile Search Icon --}}
                <button @click="openSearch = true" class="sm:hidden p-2 text-slate-400 hover:text-slate-600">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>

                {{-- Right: Actions + Profile --}}
                <div class="flex items-center gap-2 sm:gap-4">
                    {{-- Notifications --}}
                    <button class="relative p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg transition-colors">
                        <i class="fa-regular fa-bell text-lg"></i>
                        <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full ring-2 ring-white"></span>
                    </button>
                    
                    {{-- Divider --}}
                    <div class="hidden sm:block w-px h-6 bg-slate-200"></div>
                    
                    {{-- Profile --}}
                    <div class="flex items-center gap-3 pl-1">
                        <div class="text-right hidden md:block">
                            <p class="text-sm font-semibold text-slate-800 leading-none">{{ auth()->user()->name }}</p>
                            <p class="text-[11px] text-slate-400 font-medium mt-0.5">Administrator</p>
                        </div>
                        <div class="w-9 h-9 rounded-lg bg-brand/10 text-brand flex items-center justify-center font-bold text-sm border border-brand/20 cursor-pointer hover:bg-brand/20 transition-colors">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                    </div>
                </div>
            </div>
        </header>

        {{-- Page Content --}}
        <div class="flex-1 px-4 sm:px-6 lg:px-8 py-6 lg:py-8">
            {{-- Page Title Section --}}
            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">@yield('page-title', 'Overview')</h1>
                    <p class="text-sm text-slate-500 mt-1 flex items-center gap-2">
                        <i class="fa-regular fa-calendar text-xs"></i>
                        {{ now()->translatedFormat('l, d F Y') }}
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-2 px-3 py-1.5 bg-emerald-50 border border-emerald-100 rounded-lg">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                        </span>
                        <span class="text-xs font-semibold text-emerald-700">System Online</span>
                    </div>
                </div>
            </div>

            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="mb-6 flex items-center gap-3 p-4 bg-emerald-50 border border-emerald-100 rounded-xl animate-fade-in">
                    <div class="w-8 h-8 bg-emerald-500 rounded-lg flex items-center justify-center text-white shadow-sm">
                        <i class="fa-solid fa-check text-sm"></i>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-emerald-900">Berhasil</p>
                        <p class="text-xs text-emerald-600">{{ session('success') }}</p>
                    </div>
                </div>
            @endif
            
            @if(session('error'))
                <div class="mb-6 flex items-center gap-3 p-4 bg-red-50 border border-red-100 rounded-xl animate-fade-in">
                    <div class="w-8 h-8 bg-red-500 rounded-lg flex items-center justify-center text-white shadow-sm">
                        <i class="fa-solid fa-xmark text-sm"></i>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-red-900">Gagal</p>
                        <p class="text-xs text-red-600">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            {{-- Content Yield --}}
            <div class="relative">
                @yield('content')
            </div>
        </div>

        {{-- ── Command Palette (Ctrl+K) ── --}}
        <div x-show="openSearch" 
             x-cloak
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-[100] flex items-start justify-center pt-[15vh] sm:pt-[20vh] px-4 bg-slate-900/40 backdrop-blur-sm"
             @click.away="openSearch = false"
             role="dialog" aria-modal="true">
            
            <div x-show="openSearch"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                 x-transition:leave-end="opacity-0 scale-95 -translate-y-2"
                 class="w-full max-w-lg bg-white rounded-2xl shadow-2xl shadow-slate-900/20 ring-1 ring-slate-900/5 overflow-hidden"
                 @click.stop>
                
                {{-- Search Input --}}
                <div class="flex items-center gap-3 px-4 py-4 border-b border-slate-100">
                    <i class="fa-solid fa-magnifying-glass text-slate-400 text-lg"></i>
                    <input 
                        type="text" 
                        class="flex-1 bg-transparent border-0 text-slate-900 placeholder:text-slate-400 focus:ring-0 text-base font-medium p-0"
                        placeholder="Cari menu, fitur, atau halaman..."
                        x-model="search"
                        @keydown.escape="openSearch = false"
                        x-init="$watch('openSearch', value => { if(value) { setTimeout(() => $el.focus(), 50) } })">
                    <kbd class="search-kbd hidden sm:inline-block">ESC</kbd>
                </div>

                {{-- Results --}}
                <div class="max-h-[320px] overflow-y-auto py-2">
                    <template x-if="filteredItems().length > 0">
                        <div>
                            <p class="px-4 py-2 text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Menu Tersedia</p>
                            <template x-for="item in filteredItems()" :key="item.name">
                                <a :href="item.url" 
                                   @click="openSearch = false"
                                   class="flex items-center gap-3 mx-2 px-3 py-2.5 rounded-lg hover:bg-slate-50 transition-colors group">
                                    <div class="w-8 h-8 rounded-md bg-slate-100 text-slate-500 flex items-center justify-center group-hover:bg-brand/10 group-hover:text-brand transition-colors">
                                        <i :class="'fa-solid fa-' + item.icon" class="text-sm"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-slate-800" x-text="item.name"></p>
                                        <p class="text-xs text-slate-400 truncate" x-text="item.desc"></p>
                                    </div>
                                    <i class="fa-solid fa-arrow-right text-xs text-slate-300 group-hover:text-brand transition-colors"></i>
                                </a>
                            </template>
                        </div>
                    </template>
                    
                    <template x-if="search !== '' && filteredItems().length === 0">
                        <div class="px-4 py-8 text-center">
                            <div class="w-12 h-12 bg-slate-50 rounded-xl flex items-center justify-center mx-auto mb-3">
                                <i class="fa-solid fa-magnifying-glass text-slate-300 text-lg"></i>
                            </div>
                            <p class="text-sm font-semibold text-slate-700">Tidak ada hasil</p>
                            <p class="text-xs text-slate-400 mt-0.5">Coba kata kunci lain</p>
                        </div>
                    </template>
                    
                    <template x-if="search === ''">
                        <div class="px-4 py-6 text-center">
                            <div class="w-12 h-12 bg-slate-50 rounded-xl flex items-center justify-center mx-auto mb-3">
                                <i class="fa-solid fa-keyboard text-slate-300 text-lg"></i>
                            </div>
                            <p class="text-sm font-semibold text-slate-700">Pencarian Cepat</p>
                            <p class="text-xs text-slate-400 mt-0.5">Ketik untuk mencari menu atau tekan ESC untuk tutup</p>
                        </div>
                    </template>
                </div>

                {{-- Footer Shortcuts --}}
                <div class="bg-slate-50 px-4 py-2.5 border-t border-slate-100 flex items-center justify-between">
                    <div class="flex items-center gap-4 text-[11px] text-slate-400 font-medium">
                        <span class="flex items-center gap-1">
                            <kbd class="search-kbd">↑↓</kbd> Navigasi
                        </span>
                        <span class="flex items-center gap-1">
                            <kbd class="search-kbd">↵</kbd> Pilih
                        </span>
                    </div>
                    <span class="text-[11px] text-slate-400 font-medium">Bengkel Digital Pro</span>
                </div>
            </div>
        </div>
    </main>

@stack('scripts')
</body>
</html>

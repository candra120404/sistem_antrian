<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'DealDeck Antrian Pelanggan')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: { DEFAULT: '#3B82F6', hover: '#2563EB', light: '#EFF6FF', soft: '#DBEAFE' },
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

        .deal-card {
            background: #ffffff;
            border-radius: 1.5rem;
            border: 1px solid rgba(226, 232, 240, 0.7);
            box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.03);
            transition: all 0.2s ease;
        }

        .btn-press { transition: all 0.15s ease; }
        .btn-press:active { transform: scale(0.97); }
    </style>
</head>
<body class="bg-[#f3f4f8] text-slate-800 antialiased min-h-screen" x-data="{ modalOpen: false, modalTitle: '', modalDesc: '', modalConfirmText: 'Ya, Lanjutkan', modalFormId: '' }">

    {{-- ── Top Navigation Header DealDeck Style ── --}}
    <header class="bg-white border-b border-slate-200/60 sticky top-0 z-30 shadow-sm">
        <div class="max-w-2xl mx-auto px-4 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-2xl bg-gradient-to-tr from-blue-700 via-brand to-indigo-500 flex items-center justify-center text-white shadow-md shadow-brand/20">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z"/>
                        <path d="M8 12h8"/>
                        <path d="M12 8v8"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-slate-900 font-extrabold text-base tracking-tight leading-none">Deal<span class="text-brand">Deck</span></h1>
                    <p class="text-[10px] text-slate-400 font-bold mt-0.5">Portal Pelanggan</p>
                </div>
            </div>

            {{-- Right Top Profile --}}
            <div class="flex items-center gap-2.5">
                <div class="flex items-center gap-2 px-3 py-1 bg-slate-50 border border-slate-200/60 rounded-full">
                    <div class="w-6 h-6 rounded-full bg-brand text-white flex items-center justify-center font-bold text-[10px]">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <span class="text-xs font-bold text-slate-700 max-w-[100px] truncate">{{ auth()->user()->name }}</span>
                </div>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-9 h-9 rounded-full bg-slate-50 border border-slate-200/60 flex items-center justify-center text-slate-400 hover:text-red-500 hover:bg-red-50 transition-colors" title="Keluar">
                        <i class="fa-solid fa-arrow-right-from-bracket text-xs"></i>
                    </button>
                </form>
            </div>
        </div>
    </header>

    {{-- ── Main Container ── --}}
    <main class="max-w-2xl mx-auto px-4 pt-6 pb-28">

        {{-- Flash Success Alert --}}
        @if(session('success'))
            <div class="deal-card p-4 mb-5 border-l-4 border-l-emerald-500 flex items-center gap-3 animate-fade-in">
                <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0 font-bold text-xs">
                    <i class="fa-solid fa-check"></i>
                </div>
                <p class="text-xs font-bold text-slate-800 flex-1">{{ session('success') }}</p>
            </div>
        @endif

        {{-- Flash Info Alert --}}
        @if(session('info'))
            <div class="deal-card p-4 mb-5 border-l-4 border-l-blue-500 flex items-center gap-3 animate-fade-in">
                <div class="w-8 h-8 rounded-full bg-blue-100 text-brand flex items-center justify-center shrink-0 font-bold text-xs">
                    <i class="fa-solid fa-circle-info"></i>
                </div>
                <p class="text-xs font-bold text-slate-800 flex-1">{{ session('info') }}</p>
            </div>
        @endif

        {{-- Flash Error Alert --}}
        @if(session('error'))
            <div class="deal-card p-4 mb-5 border-l-4 border-l-red-500 flex items-center gap-3 animate-fade-in">
                <div class="w-8 h-8 rounded-full bg-red-100 text-red-600 flex items-center justify-center shrink-0 font-bold text-xs">
                    <i class="fa-solid fa-xmark"></i>
                </div>
                <p class="text-xs font-bold text-slate-800 flex-1">{{ session('error') }}</p>
            </div>
        @endif

        @yield('content')
    </main>

    {{-- ── Bottom Navigation Pill Bar (DealDeck Style) ── --}}
    <nav class="fixed bottom-4 left-4 right-4 z-40 max-w-md mx-auto">
        <div class="bg-white/95 backdrop-blur-md rounded-3xl border border-slate-200/80 shadow-2xl p-2 flex items-center justify-around">
            <a href="{{ route('pelanggan.dashboard') }}"
               class="flex-1 flex flex-col items-center py-2 rounded-2xl text-xs font-extrabold transition-all {{ request()->routeIs('pelanggan.dashboard') ? 'bg-brand text-white shadow-md shadow-brand/20' : 'text-slate-400 hover:text-slate-700' }}">
                <i class="fa-solid fa-square-poll-vertical text-sm mb-1"></i>
                <span>Beranda</span>
            </a>

            <a href="{{ route('pelanggan.antrian.create') }}"
               class="flex-1 flex flex-col items-center py-2 rounded-2xl text-xs font-extrabold transition-all {{ request()->routeIs('pelanggan.antrian.create') ? 'bg-brand text-white shadow-md shadow-brand/20' : 'text-slate-400 hover:text-slate-700' }}">
                <i class="fa-solid fa-circle-plus text-sm mb-1"></i>
                <span>Buat Antrian</span>
            </a>

            <a href="{{ route('pelanggan.antrian.status') }}"
               class="flex-1 flex flex-col items-center py-2 rounded-2xl text-xs font-extrabold transition-all {{ request()->routeIs('pelanggan.antrian.status') ? 'bg-brand text-white shadow-md shadow-brand/20' : 'text-slate-400 hover:text-slate-700' }}">
                <i class="fa-solid fa-ticket text-sm mb-1"></i>
                <span>Status Live</span>
            </a>
        </div>
    </nav>

    {{-- ── Reusable DealDeck Custom Modal Popup Component ── --}}
    <div x-show="modalOpen" 
         x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm">
        
        <div x-show="modalOpen"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95 translate-y-2"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-2"
             class="bg-white rounded-3xl max-w-sm w-full p-6 shadow-2xl border border-slate-100 text-center space-y-4">
            
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-brand flex items-center justify-center mx-auto text-xl font-bold">
                <i class="fa-solid fa-circle-question"></i>
            </div>

            <div>
                <h3 class="text-base font-extrabold text-slate-900 tracking-tight" x-text="modalTitle"></h3>
                <p class="text-xs font-medium text-slate-500 mt-1 leading-relaxed" x-text="modalDesc"></p>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="button" @click="modalOpen = false"
                        class="flex-1 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-2xl transition-all">
                    Batal
                </button>
                <button type="button" @click="if (modalFormId) { document.getElementById(modalFormId).submit() } modalOpen = false"
                        class="flex-1 py-2.5 bg-brand hover:bg-brand-hover text-white text-xs font-extrabold rounded-2xl shadow-lg shadow-brand/20 transition-all"
                        x-text="modalConfirmText">
                </button>
            </div>
        </div>
    </div>

@stack('scripts')
</body>
</html>

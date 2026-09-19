@extends('layouts.pelanggan')

@section('title', 'Status Live Antrian')

@section('content')
<div class="space-y-6" x-data="{ cancelModal: false }">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">Status Live Antrian</h2>
            <p class="text-xs font-semibold text-slate-400 mt-0.5">Monitoring posisi & waktu tunggu kendaraan Anda</p>
        </div>
        <div class="flex items-center gap-2 px-3 py-1.5 bg-emerald-50 border border-emerald-100 rounded-full">
            <span class="relative flex h-2 w-2">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
            </span>
            <span class="text-xs font-bold text-emerald-700">Live</span>
        </div>
    </div>

    @if($antrian)
        {{-- ── 1. DealDeck Primary Status Card (Royal Blue Solid) ── --}}
        <div class="bg-gradient-to-br from-blue-600 to-brand text-white rounded-3xl p-6 shadow-xl shadow-brand/20 relative overflow-hidden space-y-5">
            <div class="flex items-center justify-between">
                <span class="text-xs font-extrabold text-blue-100 uppercase tracking-wider">Tiket Antrian Anda</span>
                <span class="px-3 py-1 bg-white/20 backdrop-blur-md text-white text-xs font-bold rounded-full capitalize">
                    {{ $antrian->status }}
                </span>
            </div>

            <div class="flex items-center justify-between pt-2">
                <div>
                    <span class="text-xs text-blue-100 font-semibold block">Nomor Antrian</span>
                    <h3 class="text-5xl font-extrabold tracking-tight">{{ $antrian->nomor_antrian }}</h3>
                </div>
                <div class="text-right">
                    <span class="text-xs text-blue-100 font-semibold block">Posisi Urutan</span>
                    <h4 class="text-3xl font-extrabold text-white">#{{ $posisi }}</h4>
                    <span class="text-[10px] text-blue-100/90 font-medium">Dari {{ $totalMenunggu }} antrian</span>
                </div>
            </div>

            {{-- Info Kendaraan & Tanggal --}}
            <div class="pt-3 border-t border-white/15 flex items-center justify-between text-xs">
                <div>
                    <span class="text-blue-100 block">Plat & Kendaraan:</span>
                    <span class="font-extrabold text-white uppercase font-mono text-sm">{{ $antrian->no_plat }}</span>
                    <span class="text-blue-100 capitalize">({{ $antrian->jenis_kendaraan }})</span>
                </div>
                <div class="text-right">
                    <span class="text-blue-100 block">Tanggal Booking:</span>
                    <span class="font-extrabold text-white">
                        {{ $antrian->tanggal_booking ? $antrian->tanggal_booking->translatedFormat('d M Y') : 'Hari Ini' }}
                    </span>
                </div>
            </div>
        </div>

        {{-- ── 2. Progress Stepper (Menunggu -> Diproses -> Selesai) ── --}}
        <div class="deal-card p-6 space-y-4">
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Progres Pengerjaan</h3>

            <div class="grid grid-cols-3 gap-2 text-center relative">
                {{-- Step 1: Menunggu --}}
                <div class="flex flex-col items-center space-y-2">
                    <div class="w-10 h-10 rounded-2xl flex items-center justify-center text-sm font-bold shadow-sm transition-all
                                {{ in_array($antrian->status, ['menunggu', 'diproses', 'selesai']) ? 'bg-brand text-white' : 'bg-slate-100 text-slate-400' }}">
                        <i class="fa-solid fa-clock"></i>
                    </div>
                    <span class="text-xs font-extrabold text-slate-800">Menunggu</span>
                </div>

                {{-- Step 2: Diproses --}}
                <div class="flex flex-col items-center space-y-2">
                    <div class="w-10 h-10 rounded-2xl flex items-center justify-center text-sm font-bold shadow-sm transition-all
                                {{ in_array($antrian->status, ['diproses', 'selesai']) ? 'bg-brand text-white ring-4 ring-blue-100' : 'bg-slate-100 text-slate-400' }}">
                        <i class="fa-solid fa-soap"></i>
                    </div>
                    <span class="text-xs font-extrabold {{ $antrian->status === 'diproses' ? 'text-brand' : 'text-slate-800' }}">Diproses</span>
                </div>

                {{-- Step 3: Selesai --}}
                <div class="flex flex-col items-center space-y-2">
                    <div class="w-10 h-10 rounded-2xl flex items-center justify-center text-sm font-bold shadow-sm transition-all
                                {{ $antrian->status === 'selesai' ? 'bg-emerald-500 text-white' : 'bg-slate-100 text-slate-400' }}">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                    <span class="text-xs font-extrabold {{ $antrian->status === 'selesai' ? 'text-emerald-600' : 'text-slate-800' }}">Selesai</span>
                </div>
            </div>
        </div>

        {{-- ── 3. Detail Layanan & Estimasi Waktu ── --}}
        <div class="deal-card p-5 space-y-3">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 text-xs">
                <span class="font-bold text-slate-400">Paket Layanan:</span>
                <span class="font-extrabold text-slate-900">{{ $antrian->jenisLayanan->nama_layanan ?? '-' }}</span>
            </div>
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 text-xs">
                <span class="font-bold text-slate-400">Jenis Cuci:</span>
                <span class="font-bold px-2 py-0.5 bg-slate-100 rounded text-slate-800">{{ $antrian->jenisLayanan->jenis_cuci ?? 'Cuci Reguler' }}</span>
            </div>
            <div class="flex items-center justify-between text-xs">
                <span class="font-bold text-slate-400">Estimasi Menunggu:</span>
                <span class="font-extrabold text-brand flex items-center gap-1">
                    <i class="fa-regular fa-clock"></i> ± {{ max(5, $posisi * ($antrian->jenisLayanan->est_durasi_menit ?? 20)) }} Menit
                </span>
            </div>
        </div>

        {{-- ── 4. Form Action Pembatalan dengan Custom DealDeck Modal ── --}}
        @if(in_array($antrian->status, ['menunggu', 'diproses']))
            <form action="{{ route('pelanggan.antrian.batalkan', $antrian) }}" method="POST" id="form-batal-antrian">
                @csrf
                @method('DELETE')
                
                <button type="button" 
                        @click="cancelModal = true"
                        class="w-full py-3.5 bg-red-50 hover:bg-red-500 text-red-600 hover:text-white text-xs font-extrabold rounded-2xl transition-all border border-red-100 flex items-center justify-center gap-2 btn-press">
                    <i class="fa-solid fa-ban text-xs"></i>
                    <span>Batalkan Antrian Ini</span>
                </button>
            </form>

            {{-- Custom DealDeck Popup Modal Batal Antrian --}}
            <div x-show="cancelModal" 
                 x-cloak
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm">
                
                <div x-show="cancelModal"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                     x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                     x-transition:leave-end="opacity-0 scale-95 translate-y-2"
                     class="bg-white rounded-3xl max-w-sm w-full p-6 shadow-2xl border border-slate-100 text-center space-y-4"
                     @click.away="cancelModal = false">
                    
                    <div class="w-12 h-12 rounded-2xl bg-red-50 text-red-500 flex items-center justify-center mx-auto text-xl font-bold">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>

                    <div>
                        <h3 class="text-base font-extrabold text-slate-900 tracking-tight">Batalkan Antrian?</h3>
                        <p class="text-xs font-medium text-slate-500 mt-1 leading-relaxed">
                            Apakah Anda yakin ingin membatalkan antrian <strong class="text-slate-900">{{ $antrian->nomor_antrian }}</strong>? Nomor antrian yang dibatalkan tidak dapat dikembalikan.
                        </p>
                    </div>

                    <div class="flex gap-3 pt-2">
                        <button type="button" @click="cancelModal = false"
                                class="flex-1 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-2xl transition-all">
                            Kembali
                        </button>
                        <button type="button" @click="document.getElementById('form-batal-antrian').submit()"
                                class="flex-1 py-3 bg-red-500 hover:bg-red-600 text-white text-xs font-extrabold rounded-2xl shadow-lg shadow-red-500/20 transition-all">
                            Ya, Batalkan
                        </button>
                    </div>
                </div>
            </div>
        @endif

    @else
        {{-- Empty Status Card --}}
        <div class="deal-card p-8 text-center space-y-4">
            <div class="w-16 h-16 bg-blue-50 text-brand rounded-3xl flex items-center justify-center mx-auto text-2xl font-bold">
                <i class="fa-solid fa-ticket"></i>
            </div>
            <div>
                <h3 class="text-base font-extrabold text-slate-900">Belum Ada Antrian Aktif</h3>
                <p class="text-xs font-medium text-slate-400 mt-1 max-w-xs mx-auto">
                    Anda belum mendaftar antrian untuk Hari Ini maupun Besok. Silakan buat antrian baru.
                </p>
            </div>
            <a href="{{ route('pelanggan.antrian.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-brand hover:bg-brand-hover text-white text-xs font-extrabold rounded-2xl shadow-lg shadow-brand/20 transition-all">
                <span>Buat Antrian Baru</span>
                <i class="fa-solid fa-arrow-right text-xs"></i>
            </a>
        </div>
    @endif

</div>
@endsection

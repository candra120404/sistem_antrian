@extends('layouts.pelanggan')

@section('title', 'Beranda')

@section('content')
{{-- ── Welcome Section ── --}}
<div class="mb-6 animate-fade-up">
    <div class="flex items-center gap-3">
        <div class="w-11 h-11 rounded-xl gradient-brand flex items-center justify-center text-white font-bold text-sm shadow-md shadow-brand/20">
            {{ substr(auth()->user()->name, 0, 1) }}
        </div>
        <div>
            <p class="text-xs text-slate-400 font-medium">Selamat datang,</p>
            <h2 class="text-lg font-extrabold text-slate-800 tracking-tight">{{ explode(' ', auth()->user()->name)[0] }}</h2>
        </div>
    </div>
</div>

{{-- ── Antrian Aktif ── --}}
@if($antrianAktif)
    <div class="card-elevated p-5 mb-6 animate-fade-up-1 relative overflow-hidden" x-data="{ pulse: true }">
        {{-- Status Badge --}}
        <div class="flex items-center gap-2 mb-4">
            <span class="relative flex h-2.5 w-2.5">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
            </span>
            <span class="text-xs font-semibold text-emerald-600">Antrian Aktif</span>
        </div>

        {{-- Nomor Antrian --}}
        <div class="text-center mb-5">
            <p class="text-xs font-medium text-slate-400 mb-1">Nomor Anda</p>
            <h3 class="text-5xl font-black text-slate-900 tracking-tighter">{{ $antrianAktif->nomor_antrian }}</h3>
        </div>

        {{-- Detail Grid --}}
        <div class="grid grid-cols-2 gap-3 mb-5">
            <div class="bg-slate-50 rounded-xl p-3 text-center">
                <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider mb-0.5">Status</p>
                <span class="text-sm font-bold text-slate-700 capitalize">{{ $antrianAktif->status }}</span>
            </div>
            <div class="bg-slate-50 rounded-xl p-3 text-center">
                <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider mb-0.5">Posisi</p>
                <span class="text-sm font-bold text-brand">Ke-{{ \App\Models\Antrian::hitungPosisi($antrianAktif->id) }}</span>
            </div>
        </div>

        {{-- CTA --}}
        <a href="{{ route('pelanggan.antrian.status') }}"
           class="btn-press block w-full text-center py-3.5 bg-brand hover:bg-brand-hover text-white font-bold rounded-xl text-sm shadow-lg shadow-brand/20">
            Pantau Antrian
            <i class="fa-solid fa-arrow-right ml-1 text-xs"></i>
        </a>
    </div>
@else
    {{-- ── Pilih Layanan ── --}}
    <div class="mb-6 animate-fade-up-1">
        <h3 class="text-base font-bold text-slate-800 mb-3">Pilih Layanan</h3>
        
        <div class="grid grid-cols-2 gap-3">
            {{-- Motor Card --}}
            <a href="{{ route('pelanggan.antrian.create', ['jenis' => 'motor']) }}"
               class="btn-press card-elevated p-4 flex flex-col items-center text-center group">
                <div class="w-14 h-14 rounded-2xl gradient-warm flex items-center justify-center mb-3 text-amber-700 group-hover:scale-105 transition-transform">
                    <i class="fa-solid fa-motorcycle text-2xl"></i>
                </div>
                <h4 class="text-sm font-bold text-slate-800">Cuci Motor</h4>
                <p class="text-[10px] text-slate-400 font-medium mt-0.5">Mulai Rp 15rb</p>
            </a>

            {{-- Mobil Card --}}
            <a href="{{ route('pelanggan.antrian.create', ['jenis' => 'mobil']) }}"
               class="btn-press card-elevated p-4 flex flex-col items-center text-center group">
                <div class="w-14 h-14 rounded-2xl gradient-cool flex items-center justify-center mb-3 text-indigo-700 group-hover:scale-105 transition-transform">
                    <i class="fa-solid fa-car text-2xl"></i>
                </div>
                <h4 class="text-sm font-bold text-slate-800">Cuci Mobil</h4>
                <p class="text-[10px] text-slate-400 font-medium mt-0.5">Mulai Rp 35rb</p>
            </a>
        </div>
    </div>
@endif

{{-- ── Info Section ── --}}
<div class="animate-fade-up-2">
    <h3 class="text-base font-bold text-slate-800 mb-3">Informasi</h3>
    <div class="card-elevated p-4 flex items-start gap-3">
        <div class="w-9 h-9 rounded-lg bg-slate-100 flex items-center justify-center shrink-0 text-slate-500">
            <i class="fa-regular fa-clock text-sm"></i>
        </div>
        <div>
            <h4 class="text-sm font-semibold text-slate-800">Jam Operasional</h4>
            <p class="text-xs text-slate-400 mt-0.5 leading-relaxed">Buka setiap hari <span class="font-semibold text-slate-600">08:00 - 17:00 WIB</span>.</p>
        </div>
    </div>
</div>
@endsection

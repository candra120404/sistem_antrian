@extends('layouts.pelanggan')

@section('title', 'Beranda')

@section('content')
{{-- ── Welcome Section ── --}}
<div class="mt-8 mb-10">
    <div class="flex items-center gap-4">
        <div class="w-12 h-12 bg-slate-900 rounded-2xl flex items-center justify-center text-white font-black">
            {{ substr(auth()->user()->name, 0, 1) }}
        </div>
        <div>
            <h2 class="text-xl font-black text-slate-900 tracking-tight">Halo, {{ explode(' ', auth()->user()->name)[0] }}! 👋</h2>
            <p class="text-slate-400 text-xs font-bold uppercase tracking-widest mt-0.5">Sudah cuci kendaraan hari ini?</p>
        </div>
    </div>
</div>

{{-- ── Kondisi: Jika Sudah Memiliki Antrian Aktif ── --}}
@if($antrianAktif)
    <div class="bg-primary rounded-[2.5rem] p-10 text-white shadow-2xl shadow-primary/30 mb-10 relative overflow-hidden group">
        <div class="relative z-10">
            <div class="flex items-center gap-2 mb-6 uppercase tracking-[0.2em] text-[10px] font-black text-blue-300">
                <div class="w-1.5 h-1.5 bg-brand rounded-full animate-ping"></div>
                Antrian Aktif
            </div>
            
            <h3 class="text-6xl font-black mb-4 tracking-tighter">{{ $antrianAktif->nomor_antrian }}</h3>

            <div class="space-y-4 mb-10">
                <div class="flex items-center gap-3">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-brand"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                    <p class="text-sm font-bold text-slate-300">Status: <span class="text-white uppercase">{{ $antrianAktif->status }}</span></p>
                </div>
                <div class="flex items-center gap-3">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-brand"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                    <p class="text-sm font-bold text-slate-300">Posisi: <span class="text-white">Ke-{{ \App\Models\Antrian::hitungPosisi($antrianAktif->id) }}</span></p>
                </div>
            </div>

            <a href="{{ route('pelanggan.antrian.status') }}"
               class="flex items-center justify-center gap-2 bg-brand hover:bg-brand-hover text-white font-black py-4 px-8 rounded-2xl text-sm transition-all active:scale-95 shadow-xl shadow-brand/20">
                <span>Pantau Antrian</span>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
            </a>
        </div>
        {{-- Ornament --}}
        <div class="absolute -right-8 -bottom-8 opacity-5 text-white transform rotate-12 transition-transform group-hover:scale-110">
            <svg width="200" height="200" viewBox="0 0 24 24" fill="currentColor"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path></svg>
        </div>
    </div>
@else
    {{-- ── Kondisi: Belum Memiliki Antrian ── --}}
    <div class="space-y-6">
        <div class="p-6 bg-slate-50 border border-slate-100 rounded-3xl">
            <h4 class="font-black text-slate-900 text-sm tracking-tight mb-1">Gunakan Antrian Digital</h4>
            <p class="text-slate-400 text-[10px] font-bold uppercase tracking-widest leading-relaxed">Pilih kendaraan untuk mendapatkan nomor antrian sekarang.</p>
        </div>

        <div class="grid grid-cols-1 gap-4">
            <a href="{{ route('pelanggan.antrian.create', ['jenis' => 'motor']) }}"
               class="group bg-white p-6 rounded-[2rem] border border-slate-100 flex items-center justify-between hover:border-brand/40 hover:shadow-xl hover:shadow-brand/5 transition-all active:scale-[0.98]">
                <div class="flex items-center gap-5">
                    <div class="w-16 h-16 bg-amber-50 rounded-2xl flex items-center justify-center text-amber-600 transition-transform group-hover:scale-110">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="18.5" cy="17.5" r="3.5"></circle><circle cx="5.5" cy="17.5" r="3.5"></circle><circle cx="15" cy="7" r="1"></circle><path d="M10 10L12 14H18"></path><path d="M7 15L9 9H11L13 17"></path></svg>
                    </div>
                    <div>
                        <h5 class="font-black text-slate-900 text-lg tracking-tight">Cuci Motor</h5>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-0.5">Mulai dari Rp 15rb</p>
                    </div>
                </div>
                <div class="p-2.5 bg-slate-50 rounded-xl text-slate-300 group-hover:bg-brand group-hover:text-white transition-all">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                </div>
            </a>

            <a href="{{ route('pelanggan.antrian.create', ['jenis' => 'mobil']) }}"
               class="group bg-white p-6 rounded-[2rem] border border-slate-100 flex items-center justify-between hover:border-brand/40 hover:shadow-xl hover:shadow-brand/5 transition-all active:scale-[0.98]">
                <div class="flex items-center gap-5">
                    <div class="w-16 h-16 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-600 transition-transform group-hover:scale-110">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 10H17L19 14V17H5V14L7 10Z"></path><path d="M7 10L9 6H15L17 10"></path><circle cx="7.5" cy="17" r="1.5"></circle><circle cx="16.5" cy="17" r="1.5"></circle></svg>
                    </div>
                    <div>
                        <h5 class="font-black text-slate-900 text-lg tracking-tight">Cuci Mobil</h5>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-0.5">Mulai dari Rp 35rb</p>
                    </div>
                </div>
                <div class="p-2.5 bg-slate-50 rounded-xl text-slate-300 group-hover:bg-brand group-hover:text-white transition-all">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                </div>
            </a>
        </div>
    </div>
@endif

{{-- ── Info Card ── --}}
<div class="mt-12 p-8 bg-slate-900 rounded-[2rem] text-white relative overflow-hidden">
    <h5 class="text-sm font-black uppercase tracking-widest text-brand mb-2">Jam Operasional</h5>
    <p class="text-xs font-medium text-slate-400 leading-relaxed">Bengkel kami buka setiap hari mulai pukul <span class="text-white font-bold">08:00 sampai 17:00 WIB</span>.</p>
    <div class="absolute -right-4 -bottom-4 opacity-10">
        <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
    </div>
</div>
@endsection

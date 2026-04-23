@extends('layouts.pelanggan')

@section('title', 'Status Antrian')

@section('content')
<div class="mt-8 mb-10">
    <h2 class="text-3xl font-black text-slate-900 tracking-tighter">Status Antrian</h2>
    <p class="text-slate-400 text-xs font-bold uppercase tracking-widest mt-1">Pantau progres kendaraan Anda</p>
</div>

@if($antrian)
    {{-- ── Kartu Antrian Utama ── --}}
    <div class="space-y-8">
        <div class="bg-white rounded-[3rem] p-12 text-center shadow-[0_20px_60px_rgba(15,23,42,0.05)] border border-slate-50 relative overflow-hidden group">
            {{-- Pulse Decoration --}}
            <div class="absolute inset-0 flex items-center justify-center opacity-[0.03]">
                <div class="w-80 h-80 bg-brand rounded-full animate-ping"></div>
            </div>

            <div class="relative z-10">
                <p class="text-slate-400 text-[10px] font-black uppercase tracking-[0.3em] mb-6">Nomor Pendaftaran</p>
                <h1 class="text-8xl font-black text-slate-900 tracking-tighter mb-8" id="nomor-antrian-display">
                    {{ $antrian->nomor_antrian }}
                </h1>

                @php
                    $sc = [
                        'menunggu' => 'bg-amber-50 text-amber-600 border-amber-100',
                        'diproses' => 'bg-brand/5 text-brand border-brand/10',
                        'selesai' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                    ];
                @endphp
                <span class="inline-flex items-center gap-2 px-6 py-2.5 rounded-2xl text-xs font-black uppercase tracking-widest border {{ $sc[$antrian->status] }}" id="status-badge">
                    <div class="w-2 h-2 bg-current rounded-full animate-pulse"></div>
                    {{ $antrian->status }}
                </span>
            </div>
        </div>

        {{-- Detail Posisi --}}
        <div class="grid grid-cols-2 gap-4">
            <div class="bg-slate-900 p-8 rounded-[2rem] text-center shadow-xl shadow-slate-900/10">
                <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest mb-2">Posisi Ke</p>
                <h4 class="text-3xl font-black text-white" id="posisi-display">
                    {{ \App\Models\Antrian::hitungPosisi($antrian->id) }}
                </h4>
            </div>
            <div class="bg-white p-8 rounded-[2rem] border border-slate-100 text-center shadow-sm">
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2">Sisa Antrian</p>
                <h4 class="text-3xl font-black text-slate-800" id="total-menunggu-display">
                    {{ $totalMenunggu }}
                </h4>
            </div>
        </div>

        {{-- Log Detail --}}
        <div class="bg-slate-50 rounded-[2rem] p-8 space-y-6">
            <div class="flex justify-between items-center bg-white p-4 rounded-2xl shadow-sm border border-slate-100/50">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-slate-50 rounded-lg text-slate-400">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path></svg>
                    </div>
                    <span class="text-xs font-bold text-slate-500">Jenis Layanan</span>
                </div>
                <span class="text-sm font-black text-slate-900">{{ $antrian->jenisLayanan->nama_layanan }}</span>
            </div>

            <div class="flex justify-between items-center bg-white p-4 rounded-2xl shadow-sm border border-slate-100/50">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-slate-50 rounded-lg text-slate-400">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="10" rx="2" ry="2"></rect><circle cx="7" cy="16" r="1"></circle><circle cx="17" cy="16" r="1"></circle><path d="M5 11l1.5-4.5h11L19 11"></path></svg>
                    </div>
                    <span class="text-xs font-bold text-slate-500">ID Kendaraan</span>
                </div>
                <span class="text-sm font-black text-slate-900 font-mono tracking-widest">{{ $antrian->no_plat }}</span>
            </div>

            <div class="flex justify-between items-center bg-white p-4 rounded-2xl shadow-sm border border-slate-100/50">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-slate-50 rounded-lg text-slate-400">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                    </div>
                    <span class="text-xs font-bold text-slate-500">Terdaftar Pada</span>
                </div>
                <span class="text-sm font-black text-slate-900">{{ $antrian->created_at->format('H:i') }} WIB</span>
            </div>
        </div>

        {{-- Pulse Indicator --}}
        <div class="flex flex-col items-center gap-3 py-6">
            <div class="flex gap-1.5">
                <div class="w-1.5 h-1.5 bg-brand rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
                <div class="w-1.5 h-1.5 bg-brand rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                <div class="w-1.5 h-1.5 bg-brand rounded-full animate-bounce" style="animation-delay: 0.3s"></div>
            </div>
            <span class="text-[9px] font-black text-slate-300 uppercase tracking-[0.3em]">Syncing live status...</span>
        </div>
    </div>
@else
    {{-- ── Empty State ── --}}
    <div class="py-24 text-center">
        <div class="w-24 h-24 bg-slate-50 rounded-[2rem] flex items-center justify-center mx-auto mb-8 text-slate-200">
             <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path><rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect></svg>
        </div>
        <h3 class="text-2xl font-black text-slate-900 tracking-tight mb-3">Tidak Ada Antrian</h3>
        <p class="text-slate-400 text-sm mb-10 px-10 leading-relaxed font-medium">Anda belum memiliki nomor antrian aktif hari ini.</p>

        <a href="{{ route('pelanggan.antrian.create') }}"
           class="inline-flex items-center gap-2 bg-brand hover:bg-brand-hover text-white font-black py-4 px-10 rounded-[2rem] shadow-2xl shadow-brand/20 transition-all active:scale-[0.98]">
            <span>Ambil Antrian Baru</span>
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
        </a>
    </div>
@endif
@endsection

@push('scripts')
@if($antrian)
<script>
    const antrianId = {{ $antrian->id }};

    function refreshStatus() {
        fetch(`/api/antrian/${antrianId}/posisi`, {
            headers: { 'Accept': 'application/json' }
        })
        .then(res => res.json())
        .then(response => {
            if (response.status && response.data) {
                const data = response.data;
                document.getElementById('posisi-display').textContent = data.posisi;
                document.getElementById('total-menunggu-display').textContent = data.total_menunggu;
                document.getElementById('status-badge').textContent = data.status;

                if (data.status === 'selesai' || data.status === 'batal') {
                    setTimeout(() => window.location.reload(), 2000);
                }
            }
        })
        .catch(err => console.error('Error:', err));
    }

    setInterval(refreshStatus, 10000);
</script>
@endif
@endpush

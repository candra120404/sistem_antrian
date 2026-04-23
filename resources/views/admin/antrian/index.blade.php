@extends('layouts.admin')

@section('title', 'Dashboard Antrian')
@section('page-title', 'Overview Antrian')

@section('content')
{{-- ── Statistik Hari Ini ── --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
    <div class="glass-card p-8 rounded-[2.5rem] relative overflow-hidden group hover:border-brand/30 transition-all duration-500">
        <div class="relative z-10">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Total Antrian</p>
            <h3 class="text-3xl font-black text-slate-800">{{ $stats['total'] }}</h3>
        </div>
        <div class="absolute right-6 top-1/2 -translate-y-1/2 opacity-10 group-hover:opacity-20 transition-opacity">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-slate-900"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path><rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect></svg>
        </div>
    </div>

    <div class="glass-card p-8 rounded-[2.5rem] relative overflow-hidden group hover:border-amber-500/30 transition-all duration-500">
        <div class="relative z-10">
            <p class="text-[10px] font-black text-amber-500/60 uppercase tracking-widest mb-2">Menunggu</p>
            <h3 class="text-3xl font-black text-amber-600">{{ $stats['menunggu'] }}</h3>
        </div>
        <div class="absolute right-6 top-1/2 -translate-y-1/2 opacity-10 group-hover:opacity-20 transition-opacity">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-amber-600"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
        </div>
    </div>

    <div class="glass-card p-8 rounded-[2.5rem] relative overflow-hidden group hover:border-brand/30 transition-all duration-500">
        <div class="relative z-10">
            <p class="text-[10px] font-black text-brand/60 uppercase tracking-widest mb-2">Diproses</p>
            <h3 class="text-3xl font-black text-brand">{{ $stats['diproses'] }}</h3>
        </div>
        <div class="absolute right-6 top-1/2 -translate-y-1/2 opacity-10 group-hover:opacity-20 transition-opacity">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-brand"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path></svg>
        </div>
    </div>

    <div class="glass-card p-8 rounded-[2.5rem] relative overflow-hidden group hover:border-emerald-500/30 transition-all duration-500">
        <div class="relative z-10">
            <p class="text-[10px] font-black text-emerald-500/60 uppercase tracking-widest mb-2">Selesai</p>
            <h3 class="text-3xl font-black text-emerald-600">{{ $stats['selesai'] }}</h3>
        </div>
        <div class="absolute right-6 top-1/2 -translate-y-1/2 opacity-10 group-hover:opacity-20 transition-opacity">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-emerald-600"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
        </div>
    </div>
</div>

{{-- ── Tabel Antrian ── --}}
<div class="bg-white rounded-[2.5rem] shadow-[0_8px_40px_rgba(0,0,0,0.02)] border border-slate-100 overflow-hidden">
    <div class="p-8 border-b border-slate-50 flex items-center justify-between">
        <div>
            <h3 class="text-lg font-black text-slate-800 tracking-tight">Antrian Hari Ini</h3>
            <p class="text-xs text-slate-400 font-medium mt-1">Status pengerjaan unit kendaraan pelanggan</p>
        </div>
        <div class="flex items-center gap-2 px-4 py-2 bg-slate-50 rounded-xl">
             <div class="w-1.5 h-1.5 bg-brand rounded-full animate-ping"></div>
             <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Live Sync</span>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse" id="antrian-table">
            <thead>
                <tr class="bg-slate-50/50 text-slate-400 text-[10px] uppercase font-black tracking-[0.15em]">
                    <th class="px-8 py-5">No. Antrian</th>
                    <th class="px-8 py-5">Pelanggan</th>
                    <th class="px-8 py-5">Kendaraan</th>
                     <th class="px-8 py-5">Layanan</th>
                    <th class="px-8 py-5">Status</th>
                    <th class="px-8 py-5 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse(['menunggu', 'diproses', 'selesai', 'batal'] as $status)
                    @if(isset($antrians[$status]))
                        @foreach($antrians[$status] as $a)
                        <tr class="hover:bg-slate-50/30 transition-colors group">
                            <td class="px-8 py-6">
                                <span class="text-xl font-black text-slate-900 tracking-tighter">{{ $a->nomor_antrian }}</span>
                            </td>
                            <td class="px-8 py-6">
                                <p class="font-bold text-slate-800 text-sm tracking-tight">{{ $a->nama_pelanggan }}</p>
                                <p class="text-xs text-slate-400 font-medium">{{ $a->user->email }}</p>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-2 mb-1">
                                     <span class="px-2 py-0.5 rounded-md text-[9px] font-black uppercase tracking-wider {{ $a->jenis_kendaraan == 'motor' ? 'bg-amber-100 text-amber-700' : 'bg-indigo-100 text-indigo-700' }}">
                                        {{ $a->jenis_kendaraan }}
                                    </span>
                                </div>
                                <p class="text-xs font-black text-slate-600 font-mono tracking-widest uppercase">{{ $a->no_plat }}</p>
                            </td>
                            <td class="px-8 py-6">
                                <p class="text-sm font-semibold text-slate-600 tracking-tight">{{ $a->jenisLayanan->nama_layanan }}</p>
                            </td>
                            <td class="px-8 py-6">
                                @php
                                    $statusClasses = [
                                        'menunggu' => 'bg-amber-50 text-amber-600 border-amber-100',
                                        'diproses' => 'bg-brand/5 text-brand border-brand/10',
                                        'selesai' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                        'batal' => 'bg-slate-100 text-slate-500 border-slate-200',
                                    ];
                                @endphp
                                <span class="px-3 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-widest border {{ $statusClasses[$a->status] }}">
                                    {{ $a->status }}
                                </span>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    @if($a->status == 'menunggu')
                                        <form action="{{ route('admin.antrian.proses', $a) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="p-2.5 bg-brand text-white rounded-xl hover:bg-brand-hover shadow-lg shadow-brand/20 transition-all" title="Mulai Proses">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 4 23 10 17 10"></polyline><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"></path></svg>
                                            </button>
                                        </form>
                                    @endif

                                    @if($a->status == 'diproses')
                                        <form action="{{ route('admin.antrian.selesai', $a) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="p-2.5 bg-emerald-500 text-white rounded-xl hover:bg-emerald-600 shadow-lg shadow-emerald-500/20 transition-all" title="Selesaikan">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                                            </button>
                                        </form>
                                    @endif

                                    @if(in_array($a->status, ['menunggu', 'diproses']))
                                        <form action="{{ route('admin.antrian.destroy', $a) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan antrian ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-2.5 bg-red-50 text-red-500 rounded-xl hover:bg-red-500 hover:text-white transition-all" title="Batalkan">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    @endif
                @empty
                    <tr>
                        <td colspan="6" class="px-8 py-20 text-center">
                            <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
                                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="9" y1="9" x2="15" y2="15"></line><line x1="15" y1="9" x2="9" y2="15"></line></svg>
                            </div>
                            <p class="text-slate-400 font-bold text-sm">Belum ada antrian hari ini</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/antrian.js') }}"></script>
@endpush

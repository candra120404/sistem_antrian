@extends('layouts.admin')

@section('title', 'Data Harian')
@section('page-title', 'Laporan Finansial')

@section('content')
{{-- ── Filter & Statistik ── --}}
<div class="flex flex-col xl:flex-row gap-8 mb-12">
    {{-- Form Filter --}}
    <div class="w-full xl:w-1/3">
        <div class="glass-card p-10 rounded-[2.5rem] h-full flex flex-col justify-between">
            <div>
                <h3 class="text-xl font-black text-slate-800 tracking-tight mb-2">Periode Data</h3>
                <p class="text-xs text-slate-400 font-medium mb-8">Pilih tanggal untuk melihat rincian transaksi</p>

                <form action="{{ route('admin.data-harian.index') }}" method="GET" class="space-y-4">
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Pilih Tanggal</label>
                        <div class="relative">
                            <span class="absolute left-5 top-1/2 -translate-y-1/2 text-slate-400">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                            </span>
                            <input type="date" name="tanggal" value="{{ $tanggal }}"
                                   class="w-full pl-14 pr-6 py-4 bg-slate-50 border-2 border-slate-50 rounded-2xl focus:bg-white focus:border-brand transition-all focus:outline-none font-bold text-slate-700"
                                   onchange="this.form.submit()">
                        </div>
                    </div>
                </form>
            </div>

            <div class="mt-8 p-6 bg-slate-50 rounded-3xl border border-slate-100 italic text-[11px] text-slate-400 leading-relaxed font-medium">
                Sistem secara otomatis mengumpulkan data transaksi dan antrian berdasarkan WIB (Asia/Jakarta).
            </div>
        </div>
    </div>

    {{-- Kartu Statistik --}}
    <div class="w-full xl:w-2/3 grid grid-cols-1 sm:grid-cols-2 gap-6">
        <div class="bg-brand p-10 rounded-[2.5rem] shadow-xl shadow-brand/20 relative overflow-hidden flex flex-col justify-center min-h-[220px]">
            <div class="relative z-10">
                <p class="text-blue-100 text-[10px] font-black uppercase tracking-widest mb-2 opacity-80">Pendapatan Bersih</p>
                <h3 class="text-4xl font-black text-white tracking-tighter">Rp {{ number_format($stats['total_pendapatan'], 0, ',', '.') }}</h3>
                <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-white/10 rounded-full mt-4">
                    <div class="w-1.5 h-1.5 bg-emerald-400 rounded-full"></div>
                    <span class="text-[10px] font-black text-white uppercase tracking-widest">+100% Lunas</span>
                </div>
            </div>
            <div class="absolute -right-6 top-1/2 -translate-y-1/2 opacity-10 text-white">
                <svg width="180" height="180" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
            </div>
        </div>

        <div class="glass-card p-10 rounded-[2.5rem] flex flex-col justify-center relative overflow-hidden group">
            <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest mb-2">Ringkasan Unit</p>
            <h3 class="text-4xl font-black text-slate-800 tracking-tighter">{{ $stats['total_kendaraan'] }} <span class="text-xl text-slate-300 font-bold ml-1 tracking-normal">Unit</span></h3>
            <p class="text-[10px] text-slate-400 mt-4 font-bold uppercase tracking-widest">{{ $stats['selesai'] }} Selesai &bull; {{ $stats['total_kendaraan'] - $stats['selesai'] }} Lainnya</p>

            <div class="mt-8 flex gap-3">
                <div class="flex-1 bg-amber-50 rounded-2xl p-4 border border-amber-100/50">
                    <p class="text-[9px] font-black text-amber-500 uppercase mb-1">Motor</p>
                    <p class="text-xl font-black text-amber-700">{{ $stats['total_motor'] }}</p>
                </div>
                <div class="flex-1 bg-indigo-50 rounded-2xl p-4 border border-indigo-100/50">
                    <p class="text-[9px] font-black text-indigo-500 uppercase mb-1">Mobil</p>
                    <p class="text-xl font-black text-indigo-700">{{ $stats['total_mobil'] }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── Riwayat Antrian ── --}}
<div class="bg-white rounded-[2.5rem] shadow-[0_8px_40px_rgba(0,0,0,0.02)] border border-slate-100 overflow-hidden">
    <div class="p-8 border-b border-slate-50 flex items-center justify-between">
        <div>
            <h3 class="text-lg font-black text-slate-800 tracking-tight">Log Transaksi</h3>
            <p class="text-xs text-slate-400 font-medium mt-1">{{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}</p>
        </div>
        <div class="p-2.5 bg-slate-50 rounded-2xl text-slate-400">
             <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/50 text-slate-400 text-[10px] uppercase font-black tracking-[0.15em]">
                    <th class="px-8 py-5">No. Antrian</th>
                    <th class="px-8 py-5">Identitas Pelanggan</th>
                    <th class="px-8 py-5">Jenis Layanan</th>
                    <th class="px-8 py-5">Status Akhir</th>
                    <th class="px-8 py-5 text-right">Penyelesaian</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($antrians as $a)
                <tr class="hover:bg-slate-50/30 transition-colors">
                    <td class="px-8 py-6">
                        <span class="text-xl font-black text-slate-900 tracking-tighter">{{ $a->nomor_antrian }}</span>
                    </td>
                    <td class="px-8 py-6">
                        <p class="font-bold text-slate-800 text-sm tracking-tight">{{ $a->nama_pelanggan }}</p>
                        <p class="text-[10px] font-black text-slate-400 font-mono tracking-widest uppercase mt-0.5">{{ $a->no_plat }}</p>
                    </td>
                    <td class="px-8 py-6">
                        <p class="text-sm font-semibold text-slate-600">{{ $a->jenisLayanan->nama_layanan }}</p>
                    </td>
                    <td class="px-8 py-6">
                        @php
                            $st = [
                                'selesai' => 'bg-emerald-50 text-emerald-600',
                                'batal' => 'bg-red-50 text-red-500',
                                'menunggu' => 'bg-amber-50 text-amber-600',
                                'diproses' => 'bg-brand/5 text-brand',
                            ];
                        @endphp
                        <span class="px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest {{ $st[$a->status] }}">
                            {{ $a->status }}
                        </span>
                    </td>
                    <td class="px-8 py-6 text-right">
                        @if($a->transaksi)
                            <p class="font-black text-slate-800 tracking-tight">Rp {{ number_format($a->transaksi->total_bayar, 0, ',', '.') }}</p>
                            <span class="text-[9px] font-black text-emerald-500 uppercase tracking-widest">Paid</span>
                        @else
                            <span class="text-slate-300 font-medium text-xs italic">N/A</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-8 py-20 text-center">
                        <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-200">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path><polyline points="13 2 13 9 20 9"></polyline></svg>
                        </div>
                        <p class="text-slate-400 font-bold text-sm tracking-tight">Tidak ada riwayat transaksi pada tanggal ini.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

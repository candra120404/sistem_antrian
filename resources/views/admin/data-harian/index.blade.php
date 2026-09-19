@extends('layouts.admin')

@section('title', 'Transactions')
@section('page-title', 'Transactions')

@section('content')
<div class="space-y-6">

    {{-- ── Filter Tanggal & Stat Cards (DealDeck Style) ── --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        {{-- Left 4 cols: Date Selection Card --}}
        <div class="lg:col-span-4 deal-card p-6 flex flex-col justify-between">
            <div>
                <h3 class="text-base font-extrabold text-slate-900 tracking-tight mb-1">Periode Transaksi</h3>
                <p class="text-xs text-slate-400 font-medium mb-6">Pilih tanggal untuk melihat rincian finansial harian</p>

                <form action="{{ route('admin.data-harian.index') }}" method="GET" class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Pilih Tanggal</label>
                        <div class="relative">
                            <input type="date" name="tanggal" value="{{ $tanggal }}"
                                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200/80 rounded-2xl text-xs font-extrabold text-slate-800 focus:bg-white focus:border-brand focus:outline-none transition-all"
                                   onchange="this.form.submit()">
                        </div>
                    </div>
                </form>
            </div>

            <div class="mt-6 p-4 bg-slate-50 rounded-2xl border border-slate-200/60 text-[11px] text-slate-400 font-semibold leading-relaxed">
                <i class="fa-solid fa-circle-info text-brand mr-1"></i> Data diperbarui otomatis berdasarkan zona waktu WIB.
            </div>
        </div>

        {{-- Right 8 cols: Financial Cards --}}
        <div class="lg:col-span-8 grid grid-cols-1 sm:grid-cols-2 gap-5">
            {{-- Pendapatan Card (Vibrant Blue Card) --}}
            <div class="bg-gradient-to-br from-blue-600 to-brand text-white rounded-3xl p-6 shadow-xl shadow-brand/20 flex flex-col justify-between">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-10 h-10 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center text-white">
                        <i class="fa-solid fa-money-bill-trend-up text-base"></i>
                    </div>
                    <span class="px-2.5 py-1 rounded-full bg-emerald-400 text-slate-900 text-[11px] font-extrabold flex items-center gap-1">
                        <i class="fa-solid fa-arrow-trend-up text-[10px]"></i> +100% Lunas
                    </span>
                </div>
                <div>
                    <p class="text-xs font-semibold text-blue-100 uppercase tracking-wider">Pendapatan Bersih</p>
                    <h3 class="text-3xl font-extrabold tracking-tight mt-1">Rp {{ number_format($stats['total_pendapatan'], 0, ',', '.') }}</h3>
                    <p class="text-[11px] text-blue-100/80 mt-1 font-medium">{{ \Carbon\Carbon::parse($tanggal)->format('d F Y') }}</p>
                </div>
            </div>

            {{-- Ringkasan Unit Card (White Card) --}}
            <div class="deal-card p-6 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Kendaraan</p>
                        <div class="w-9 h-9 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-sm">
                            <i class="fa-solid fa-car"></i>
                        </div>
                    </div>
                    <h3 class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ $stats['total_kendaraan'] }} <span class="text-sm text-slate-400 font-bold">Unit</span></h3>
                    <p class="text-[11px] text-slate-400 font-semibold mt-1">{{ $stats['selesai'] }} Selesai dikerjakan</p>
                </div>

                <div class="grid grid-cols-2 gap-3 pt-3 border-t border-slate-100 mt-3">
                    <div class="bg-amber-50 p-3 rounded-xl border border-amber-100">
                        <p class="text-[10px] font-extrabold text-amber-600 uppercase">Motor</p>
                        <p class="text-lg font-extrabold text-amber-900">{{ $stats['total_motor'] }} unit</p>
                    </div>
                    <div class="bg-indigo-50 p-3 rounded-xl border border-indigo-100">
                        <p class="text-[10px] font-extrabold text-indigo-600 uppercase">Mobil</p>
                        <p class="text-lg font-extrabold text-indigo-900">{{ $stats['total_mobil'] }} unit</p>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- ── Log Transaksi Table ── --}}
    <div class="deal-card overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-base font-extrabold text-slate-900 tracking-tight">Log Transaksi & Antrian</h3>
                <p class="text-xs text-slate-400 font-medium">Laporan transaksi finansial tanggal {{ \Carbon\Carbon::parse($tanggal)->format('d F Y') }}</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-400 text-[11px] font-bold uppercase tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4">No Antrian</th>
                        <th class="px-6 py-4">Identitas Pelanggan</th>
                        <th class="px-6 py-4">Jenis Layanan</th>
                        <th class="px-6 py-4">Status Akhir</th>
                        <th class="px-6 py-4 text-right">Pembayaran</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($antrians as $a)
                    <tr class="hover:bg-slate-50/70 transition-colors">
                        <td class="px-6 py-4 font-extrabold text-brand text-lg">
                            {{ $a->nomor_antrian }}
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-bold text-slate-900 text-sm leading-tight">{{ $a->nama_pelanggan }}</p>
                            <p class="text-xs text-slate-400 font-medium font-mono uppercase mt-0.5">{{ $a->no_plat }}</p>
                        </td>
                        <td class="px-6 py-4 font-semibold text-slate-800">
                            {{ $a->jenisLayanan->nama_layanan }}
                        </td>
                        <td class="px-6 py-4">
                            @if($a->status === 'selesai')
                                <span class="px-3 py-1 bg-emerald-50 text-emerald-700 border border-emerald-100 rounded-full text-xs font-bold">Selesai</span>
                            @elseif($a->status === 'diproses')
                                <span class="px-3 py-1 bg-blue-50 text-brand border border-blue-100 rounded-full text-xs font-bold">Diproses</span>
                            @elseif($a->status === 'menunggu')
                                <span class="px-3 py-1 bg-amber-50 text-amber-700 border border-amber-100 rounded-full text-xs font-bold">Menunggu</span>
                            @else
                                <span class="px-3 py-1 bg-slate-100 text-slate-500 rounded-full text-xs font-bold">Batal</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            @if($a->transaksi)
                                <p class="font-extrabold text-slate-900 text-base">Rp {{ number_format($a->transaksi->total_bayar, 0, ',', '.') }}</p>
                                <span class="text-[10px] font-extrabold text-emerald-600 uppercase tracking-wider">Paid Lunas</span>
                            @else
                                <span class="text-slate-400 font-semibold text-xs italic">Belum Dibayar</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-400 font-medium">
                            Tidak ada riwayat transaksi pada tanggal ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

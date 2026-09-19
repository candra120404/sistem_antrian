@extends('layouts.admin')

@section('title', 'Laporan Keseluruhan')
@section('page-title', 'Laporan Keseluruhan Sistem')

@section('content')
<div class="space-y-6">

    {{-- ── Filter Rentang Tanggal Bar (DealDeck Card) ── --}}
    <div class="deal-card p-6">
        <form method="GET" action="{{ route('admin.laporan.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Dari Tanggal</label>
                <input type="date" name="dari_tanggal" value="{{ $dariTanggal }}" 
                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200/80 rounded-2xl text-xs font-bold text-slate-800 focus:bg-white focus:border-brand focus:outline-none transition-all">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Sampai Tanggal</label>
                <input type="date" name="sampai_tanggal" value="{{ $sampaiTanggal }}" 
                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200/80 rounded-2xl text-xs font-bold text-slate-800 focus:bg-white focus:border-brand focus:outline-none transition-all">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Jenis Kendaraan</label>
                <select name="jenis_kendaraan" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200/80 rounded-2xl text-xs font-bold text-slate-800 focus:bg-white focus:border-brand focus:outline-none transition-all">
                    <option value="semua" {{ $jenisKendaraan == 'semua' ? 'selected' : '' }}>Semua Kendaraan</option>
                    <option value="motor" {{ $jenisKendaraan == 'motor' ? 'selected' : '' }}>Motor</option>
                    <option value="mobil" {{ $jenisKendaraan == 'mobil' ? 'selected' : '' }}>Mobil</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Status Antrian</label>
                <select name="status" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200/80 rounded-2xl text-xs font-bold text-slate-800 focus:bg-white focus:border-brand focus:outline-none transition-all">
                    <option value="semua" {{ $statusFilter == 'semua' ? 'selected' : '' }}>Semua Status</option>
                    <option value="selesai" {{ $statusFilter == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="menunggu" {{ $statusFilter == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                    <option value="diproses" {{ $statusFilter == 'diproses' ? 'selected' : '' }}>Diproses</option>
                    <option value="batal" {{ $statusFilter == 'batal' ? 'selected' : '' }}>Batal</option>
                </select>
            </div>
            <div class="flex items-center gap-2">
                <button type="submit" class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-brand hover:bg-brand-hover text-white text-xs font-extrabold rounded-2xl shadow-lg shadow-brand/20 transition-all">
                    <i class="fa-solid fa-filter text-xs"></i>
                    Filter
                </button>
                <button type="button" onclick="window.print()" class="p-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-2xl transition-all" title="Cetak Laporan">
                    <i class="fa-solid fa-print"></i>
                </button>
            </div>
        </form>
    </div>

    {{-- ── 4 Stat Cards DealDeck Style ── --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        {{-- Total Sales / Pendapatan --}}
        <div class="bg-gradient-to-br from-blue-600 to-brand text-white rounded-3xl p-6 shadow-xl shadow-brand/20 flex flex-col justify-between">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center text-white">
                    <i class="fa-solid fa-wallet text-base"></i>
                </div>
                <span class="px-2.5 py-1 rounded-full bg-emerald-400 text-slate-900 text-[11px] font-extrabold flex items-center gap-1">
                    <i class="fa-solid fa-arrow-trend-up text-[10px]"></i> +2.08%
                </span>
            </div>
            <div>
                <p class="text-xs font-semibold text-blue-100 uppercase tracking-wider">Total Sales</p>
                <h3 class="text-2xl sm:text-3xl font-extrabold tracking-tight mt-1">Rp {{ number_format($stats['total_pendapatan'], 0, ',', '.') }}</h3>
                <p class="text-[11px] text-blue-100/80 mt-1">
                    {{ \Carbon\Carbon::parse($dariTanggal)->format('d M Y') }} - {{ \Carbon\Carbon::parse($sampaiTanggal)->format('d M Y') }}
                </p>
            </div>
        </div>

        {{-- Total Orders / Pelanggan --}}
        <div class="deal-card p-6 flex flex-col justify-between">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-2xl bg-blue-50 text-brand flex items-center justify-center">
                    <i class="fa-solid fa-users text-base"></i>
                </div>
                <span class="px-2.5 py-1 rounded-full bg-emerald-500 text-white text-[11px] font-extrabold flex items-center gap-1">
                    <i class="fa-solid fa-arrow-trend-up text-[10px]"></i> +12.4%
                </span>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Orders</p>
                <h3 class="text-3xl font-extrabold text-slate-900 tracking-tight mt-1">{{ $stats['total_pelanggan'] }}</h3>
                <p class="text-[11px] text-slate-400 mt-1">Selesai: {{ $stats['total_selesai'] }} | Batal: {{ $stats['total_batal'] }}</p>
            </div>
        </div>

        {{-- Motor vs Mobil --}}
        <div class="deal-card p-6 flex flex-col justify-between">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                    <i class="fa-solid fa-car text-base"></i>
                </div>
                <span class="px-2.5 py-1 rounded-full bg-red-500 text-white text-[11px] font-extrabold flex items-center gap-1">
                    <i class="fa-solid fa-arrow-trend-down text-[10px]"></i> -2.08%
                </span>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Tipe Kendaraan</p>
                <div class="flex items-center justify-between mt-1">
                    <div>
                        <span class="text-[10px] text-slate-400 font-bold uppercase">Motor</span>
                        <p class="text-xl font-extrabold text-slate-900">{{ $stats['total_motor'] }}</p>
                    </div>
                    <div class="h-6 w-px bg-slate-200"></div>
                    <div>
                        <span class="text-[10px] text-slate-400 font-bold uppercase">Mobil</span>
                        <p class="text-xl font-extrabold text-slate-900">{{ $stats['total_mobil'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tingkat Penyelesaian --}}
        <div class="deal-card p-6 flex flex-col justify-between">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <i class="fa-solid fa-chart-line text-base"></i>
                </div>
                <span class="px-2.5 py-1 rounded-full bg-emerald-500 text-white text-[11px] font-extrabold flex items-center gap-1">
                    <i class="fa-solid fa-arrow-trend-up text-[10px]"></i> +12.1%
                </span>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Conversion Rate</p>
                @php
                    $rate = $stats['total_pelanggan'] > 0 ? round(($stats['total_selesai'] / $stats['total_pelanggan']) * 100) : 0;
                @endphp
                <h3 class="text-3xl font-extrabold text-slate-900 tracking-tight mt-1">{{ $rate }}%</h3>
                <p class="text-[11px] text-slate-400 mt-1">Penyelesaian antrian</p>
            </div>
        </div>
    </div>

    {{-- ── Breakdown Rekap Per Layanan / Jenis Cuci ── --}}
    <div class="deal-card overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-base font-extrabold text-slate-900 tracking-tight">Rekapitulasi Per Jenis Layanan</h3>
                <p class="text-xs text-slate-400 font-medium">Rincian pendapatan berdasarkan paket cuci yang dipilih pelanggan</p>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-400 text-[11px] font-bold uppercase tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4">Nama Layanan</th>
                        <th class="px-6 py-4">Jenis Cuci</th>
                        <th class="px-6 py-4">Kendaraan</th>
                        <th class="px-6 py-4 text-center">Jumlah Transaksi</th>
                        <th class="px-6 py-4 text-right">Total Omset</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($breakdownLayanan as $row)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="px-6 py-4 font-bold text-slate-900">{{ $row['nama_layanan'] }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 bg-slate-100 text-slate-800 rounded-lg text-xs font-semibold">
                                    {{ $row['jenis_cuci'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 capitalize font-semibold">{{ $row['jenis_kendaraan'] }}</td>
                            <td class="px-6 py-4 text-center font-bold">{{ $row['jumlah'] }} unit</td>
                            <td class="px-6 py-4 text-right font-extrabold text-slate-900">Rp {{ number_format($row['total_omset'], 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-slate-400">Belum ada data transaksi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ── Histori Transaksi Detail ── --}}
    <div class="deal-card overflow-hidden">
        <div class="p-6 border-b border-slate-100">
            <h3 class="text-base font-extrabold text-slate-900 tracking-tight">Histori Transaksi & Antrian Detail</h3>
            <p class="text-xs text-slate-400 font-medium">Daftar lengkap pendaftaran antrian pada periode terpilih</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-400 text-[11px] font-bold uppercase tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4">Tanggal</th>
                        <th class="px-6 py-4">No Antrian</th>
                        <th class="px-6 py-4">Pelanggan</th>
                        <th class="px-6 py-4">No Plat / Tipe</th>
                        <th class="px-6 py-4">Paket Cuci</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Biaya</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($antrians as $antrian)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="px-6 py-4 text-xs font-semibold text-slate-500">
                                {{ $antrian->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 font-extrabold text-brand">{{ $antrian->nomor_antrian }}</td>
                            <td class="px-6 py-4 font-bold text-slate-900">{{ $antrian->nama_pelanggan }}</td>
                            <td class="px-6 py-4">
                                <span class="font-mono bg-slate-100 px-2 py-0.5 rounded text-xs text-slate-800 font-extrabold uppercase">{{ $antrian->no_plat }}</span>
                                <span class="text-xs text-slate-400 block capitalize mt-0.5">{{ $antrian->jenis_kendaraan }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-bold text-slate-900 block">{{ $antrian->jenisLayanan->nama_layanan ?? '-' }}</span>
                                <span class="text-xs text-slate-400 block">{{ $antrian->jenisLayanan->jenis_cuci ?? '' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @if($antrian->status === 'selesai')
                                    <span class="px-3 py-1 bg-emerald-50 text-emerald-700 border border-emerald-100 rounded-full text-xs font-bold">Selesai</span>
                                @elseif($antrian->status === 'diproses')
                                    <span class="px-3 py-1 bg-blue-50 text-brand border border-blue-100 rounded-full text-xs font-bold">Diproses</span>
                                @elseif($antrian->status === 'menunggu')
                                    <span class="px-3 py-1 bg-amber-50 text-amber-700 border border-amber-100 rounded-full text-xs font-bold">Menunggu</span>
                                @else
                                    <span class="px-3 py-1 bg-slate-100 text-slate-500 rounded-full text-xs font-bold">Batal</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right font-extrabold text-slate-900">
                                Rp {{ number_format($antrian->transaksi?->total_bayar ?? $antrian->jenisLayanan?->harga ?? 0, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-slate-400 font-medium">Tidak ada histori antrian untuk filter yang dipilih.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

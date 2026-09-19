@extends('layouts.admin')

@section('title', 'Laporan Keseluruhan')
@section('page-title', 'Laporan Keseluruhan Sistem')

@section('content')
<div class="space-y-6">

    {{-- ── Filter Rentang Tanggal & Parameter ── --}}
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200/60">
        <form method="GET" action="{{ route('admin.laporan.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wider">Dari Tanggal</label>
                <input type="date" name="dari_tanggal" value="{{ $dariTanggal }}" 
                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:bg-white focus:border-brand focus:ring-2 focus:ring-brand/20 transition-all">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wider">Sampai Tanggal</label>
                <input type="date" name="sampai_tanggal" value="{{ $sampaiTanggal }}" 
                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:bg-white focus:border-brand focus:ring-2 focus:ring-brand/20 transition-all">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wider">Jenis Kendaraan</label>
                <select name="jenis_kendaraan" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:bg-white focus:border-brand focus:ring-2 focus:ring-brand/20 transition-all">
                    <option value="semua" {{ $jenisKendaraan == 'semua' ? 'selected' : '' }}>Semua Kendaraan</option>
                    <option value="motor" {{ $jenisKendaraan == 'motor' ? 'selected' : '' }}>Motor</option>
                    <option value="mobil" {{ $jenisKendaraan == 'mobil' ? 'selected' : '' }}>Mobil</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wider">Status Antrian</label>
                <select name="status" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:bg-white focus:border-brand focus:ring-2 focus:ring-brand/20 transition-all">
                    <option value="semua" {{ $statusFilter == 'semua' ? 'selected' : '' }}>Semua Status</option>
                    <option value="selesai" {{ $statusFilter == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="menunggu" {{ $statusFilter == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                    <option value="diproses" {{ $statusFilter == 'diproses' ? 'selected' : '' }}>Diproses</option>
                    <option value="batal" {{ $statusFilter == 'batal' ? 'selected' : '' }}>Batal</option>
                </select>
            </div>
            <div class="flex items-center gap-2">
                <button type="submit" class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-brand hover:bg-brand-hover text-white text-sm font-semibold rounded-xl shadow-md shadow-brand/20 transition-all">
                    <i class="fa-solid fa-filter text-xs"></i>
                    Terapkan Filter
                </button>
                <button type="button" onclick="window.print()" class="p-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl transition-all" title="Cetak Laporan">
                    <i class="fa-solid fa-print"></i>
                </button>
            </div>
        </form>
    </div>

    {{-- ── Cards Ringkasan Statistik ── --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        {{-- Total Pendapatan --}}
        <div class="bg-gradient-to-br from-emerald-600 to-teal-700 rounded-2xl p-5 text-white shadow-lg shadow-emerald-600/20">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-medium text-emerald-100 uppercase tracking-wider">Total Pendapatan</span>
                <div class="w-9 h-9 rounded-xl bg-white/15 flex items-center justify-center backdrop-blur-sm">
                    <i class="fa-solid fa-money-bill-wave text-white"></i>
                </div>
            </div>
            <h3 class="text-2xl font-bold tracking-tight">Rp {{ number_format($stats['total_pendapatan'], 0, ',', '.') }}</h3>
            <p class="text-xs text-emerald-100/80 mt-1">
                {{ \Carbon\Carbon::parse($dariTanggal)->translatedFormat('d M Y') }} - {{ \Carbon\Carbon::parse($sampaiTanggal)->translatedFormat('d M Y') }}
            </p>
        </div>

        {{-- Total Antrian --}}
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200/60">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Pelanggan</span>
                <div class="w-9 h-9 rounded-xl bg-blue-50 text-brand flex items-center justify-center">
                    <i class="fa-solid fa-users"></i>
                </div>
            </div>
            <h3 class="text-2xl font-bold text-slate-900 tracking-tight">{{ $stats['total_pelanggan'] }}</h3>
            <p class="text-xs text-slate-400 mt-1">Selesai: {{ $stats['total_selesai'] }} | Batal: {{ $stats['total_batal'] }}</p>
        </div>

        {{-- Motor vs Mobil --}}
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200/60">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Tipe Kendaraan</span>
                <div class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                    <i class="fa-solid fa-car-side"></i>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs text-slate-400">Motor</span>
                    <p class="text-lg font-bold text-slate-800">{{ $stats['total_motor'] }}</p>
                </div>
                <div class="h-8 w-px bg-slate-100"></div>
                <div>
                    <span class="text-xs text-slate-400">Mobil</span>
                    <p class="text-lg font-bold text-slate-800">{{ $stats['total_mobil'] }}</p>
                </div>
            </div>
        </div>

        {{-- Persentase Keberhasilan --}}
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200/60">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Tingkat Penyelesaian</span>
                <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                    <i class="fa-solid fa-chart-line"></i>
                </div>
            </div>
            @php
                $rate = $stats['total_pelanggan'] > 0 ? round(($stats['total_selesai'] / $stats['total_pelanggan']) * 100) : 0;
            @endphp
            <h3 class="text-2xl font-bold text-slate-900 tracking-tight">{{ $rate }}%</h3>
            <p class="text-xs text-slate-400 mt-1">Dari total pendaftaran</p>
        </div>
    </div>

    {{-- ── Breakdown Rekap Per Layanan / Jenis Cuci ── --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h2 class="text-base font-bold text-slate-900">Rekapitulasi Per Jenis Layanan & Cuci</h2>
                <p class="text-xs text-slate-400 mt-0.5">Rincian pendapatan berdasarkan paket cuci yang dipilih pelanggan</p>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-500 font-semibold text-xs uppercase tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-3.5">Nama Layanan</th>
                        <th class="px-6 py-3.5">Jenis Cuci</th>
                        <th class="px-6 py-3.5">Kendaraan</th>
                        <th class="px-6 py-3.5 text-center">Jumlah Transaksi</th>
                        <th class="px-6 py-3.5 text-right">Total Omset</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($breakdownLayanan as $row)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-3.5 font-semibold text-slate-800">{{ $row['nama_layanan'] }}</td>
                            <td class="px-6 py-3.5">
                                <span class="px-2.5 py-1 bg-slate-100 text-slate-700 rounded-md text-xs font-medium">
                                    {{ $row['jenis_cuci'] }}
                                </span>
                            </td>
                            <td class="px-6 py-3.5 capitalize">{{ $row['jenis_kendaraan'] }}</td>
                            <td class="px-6 py-3.5 text-center font-medium">{{ $row['jumlah'] }} unit</td>
                            <td class="px-6 py-3.5 text-right font-bold text-slate-900">Rp {{ number_format($row['total_omset'], 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-6 text-center text-slate-400">Belum ada data transaksi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ── Tabel Detail Transaksi & Antrian ── --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100">
            <h2 class="text-base font-bold text-slate-900">Histori Transaksi & Antrian Detail</h2>
            <p class="text-xs text-slate-400 mt-0.5">Daftar lengkap pendaftaran antrian pada periode terpilih</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-500 font-semibold text-xs uppercase tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-3.5">Waktu / Tanggal</th>
                        <th class="px-6 py-3.5">No Antrian</th>
                        <th class="px-6 py-3.5">Pelanggan</th>
                        <th class="px-6 py-3.5">No Plat / Tipe</th>
                        <th class="px-6 py-3.5">Paket Cuci</th>
                        <th class="px-6 py-3.5">Status</th>
                        <th class="px-6 py-3.5 text-right">Biaya</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($antrians as $antrian)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-3.5 text-xs text-slate-500">
                                {{ $antrian->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-3.5 font-bold text-brand">{{ $antrian->nomor_antrian }}</td>
                            <td class="px-6 py-3.5 font-medium text-slate-800">{{ $antrian->nama_pelanggan }}</td>
                            <td class="px-6 py-3.5">
                                <span class="font-mono bg-slate-100 px-2 py-0.5 rounded text-xs text-slate-700 font-bold uppercase">{{ $antrian->no_plat }}</span>
                                <span class="text-xs text-slate-400 block capitalize mt-0.5">{{ $antrian->jenis_kendaraan }}</span>
                            </td>
                            <td class="px-6 py-3.5">
                                <span class="font-medium text-slate-800 block">{{ $antrian->jenisLayanan->nama_layanan ?? '-' }}</span>
                                <span class="text-xs text-slate-400 block">{{ $antrian->jenisLayanan->jenis_cuci ?? '' }}</span>
                            </td>
                            <td class="px-6 py-3.5">
                                @if($antrian->status === 'selesai')
                                    <span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-100 rounded-full text-xs font-semibold">Selesai</span>
                                @elseif($antrian->status === 'diproses')
                                    <span class="px-2.5 py-1 bg-blue-50 text-blue-700 border border-blue-100 rounded-full text-xs font-semibold">Diproses</span>
                                @elseif($antrian->status === 'menunggu')
                                    <span class="px-2.5 py-1 bg-amber-50 text-amber-700 border border-amber-100 rounded-full text-xs font-semibold">Menunggu</span>
                                @else
                                    <span class="px-2.5 py-1 bg-red-50 text-red-700 border border-red-100 rounded-full text-xs font-semibold">Batal</span>
                                @endif
                            </td>
                            <td class="px-6 py-3.5 text-right font-semibold text-slate-900">
                                Rp {{ number_format($antrian->transaksi?->total_bayar ?? $antrian->jenisLayanan?->harga ?? 0, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-slate-400">Tidak ada histori antrian untuk filter yang dipilih.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

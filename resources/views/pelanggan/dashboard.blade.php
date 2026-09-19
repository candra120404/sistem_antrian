@extends('layouts.pelanggan')

@section('title', 'Dashboard Pelanggan')

@section('content')
<div class="space-y-6">

    {{-- Header Greeting --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">Halo, {{ auth()->user()->name }}! 👋</h2>
            <p class="text-xs font-semibold text-slate-400 mt-0.5">Pesan antrian cuci kendaraan tanpa antri di lokasi</p>
        </div>
    </div>

    {{-- ── 1. Kartu Antrian Aktif (DealDeck Royal Blue Highlight Card) ── --}}
    @if($antrianAktif)
        <div class="bg-gradient-to-br from-blue-600 to-brand text-white rounded-3xl p-6 shadow-xl shadow-brand/20 relative overflow-hidden space-y-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-ping"></span>
                    <span class="text-xs font-extrabold tracking-wider uppercase text-blue-100">Antrian Aktif Anda</span>
                </div>
                <span class="px-3 py-1 bg-white/20 backdrop-blur-md rounded-full text-xs font-bold text-white capitalize">
                    {{ $antrianAktif->status }}
                </span>
            </div>

            <div class="flex items-end justify-between pt-1">
                <div>
                    <span class="text-xs text-blue-100 font-semibold block">Nomor Antrian</span>
                    <h3 class="text-4xl font-extrabold tracking-tight">{{ $antrianAktif->nomor_antrian }}</h3>
                    <p class="text-xs text-blue-100/90 mt-1 font-medium">
                        Plat: <span class="font-mono uppercase font-bold">{{ $antrianAktif->no_plat }}</span> ({{ $antrianAktif->jenis_kendaraan }})
                    </p>
                </div>
                <div class="text-right">
                    <span class="text-[10px] text-blue-100 font-bold block uppercase tracking-wider">Tanggal Booking</span>
                    <span class="text-xs font-extrabold text-white bg-white/10 px-2.5 py-1 rounded-xl inline-block mt-0.5">
                        {{ $antrianAktif->tanggal_booking ? $antrianAktif->tanggal_booking->translatedFormat('d M Y') : 'Hari Ini' }}
                    </span>
                </div>
            </div>

            <div class="pt-2 border-t border-white/15 flex items-center justify-between">
                <div class="flex items-center gap-2 text-xs text-blue-100 font-medium">
                    <i class="fa-solid fa-soap"></i>
                    <span>{{ $antrianAktif->jenisLayanan->nama_layanan ?? '-' }}</span>
                </div>
                <a href="{{ route('pelanggan.antrian.status') }}" 
                   class="px-4 py-2 bg-white text-brand hover:bg-blue-50 text-xs font-extrabold rounded-2xl shadow-md transition-all inline-flex items-center gap-1.5">
                    <span>Cek Status Live</span>
                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>
        </div>
    @else
        {{-- Card No Active Queue --}}
        <div class="deal-card p-6 border-l-4 border-l-brand flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-blue-50 text-brand flex items-center justify-center font-bold text-base shrink-0">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <div>
                    <h4 class="text-sm font-extrabold text-slate-900">Belum Ada Antrian Aktif</h4>
                    <p class="text-xs text-slate-400 font-medium mt-0.5">Pilih pendaftaran untuk Hari Ini atau Besok.</p>
                </div>
            </div>
        </div>
    @endif

    {{-- ── 2. Pilihan Booking: Hari Ini vs Besok ── --}}
    <div>
        <h3 class="text-xs font-extrabold text-slate-400 uppercase tracking-wider mb-3">Pilihan Pendaftaran Antrian</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            
            {{-- Option 1: Hari Ini --}}
            <a href="{{ route('pelanggan.antrian.create', ['tanggal' => 'hari_ini']) }}" 
               class="deal-card p-5 hover:border-brand/50 hover:shadow-lg transition-all group flex flex-col justify-between btn-press">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 rounded-2xl bg-blue-50 text-brand flex items-center justify-center group-hover:bg-brand group-hover:text-white transition-colors">
                        <i class="fa-solid fa-calendar-day text-base"></i>
                    </div>
                    <span class="px-2.5 py-1 rounded-full bg-slate-100 text-slate-600 text-[10px] font-extrabold">Hari Ini</span>
                </div>
                <div>
                    <h4 class="text-sm font-extrabold text-slate-900 group-hover:text-brand transition-colors">Cuci Hari Ini</h4>
                    <p class="text-xs text-slate-400 font-medium mt-0.5">{{ today()->translatedFormat('l, d F Y') }}</p>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-brand">
                    <span>Daftar Sekarang</span>
                    <i class="fa-solid fa-arrow-right text-[10px] group-hover:translate-x-1 transition-transform"></i>
                </div>
            </a>

            {{-- Option 2: Besok --}}
            <a href="{{ route('pelanggan.antrian.create', ['tanggal' => 'besok']) }}" 
               class="deal-card p-5 hover:border-indigo-500/50 hover:shadow-lg transition-all group flex flex-col justify-between btn-press">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                        <i class="fa-solid fa-calendar-plus text-base"></i>
                    </div>
                    <span class="px-2.5 py-1 rounded-full bg-indigo-50 text-indigo-700 text-[10px] font-extrabold">Booking Besok</span>
                </div>
                <div>
                    <h4 class="text-sm font-extrabold text-slate-900 group-hover:text-indigo-600 transition-colors">Cuci Besok Hari</h4>
                    <p class="text-xs text-slate-400 font-medium mt-0.5">{{ today()->addDay()->translatedFormat('l, d F Y') }}</p>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-indigo-600">
                    <span>Pesan Tempat</span>
                    <i class="fa-solid fa-arrow-right text-[10px] group-hover:translate-x-1 transition-transform"></i>
                </div>
            </a>

        </div>
    </div>

    {{-- ── 3. Shortcut Kategori Kendaraan ── --}}
    <div>
        <h3 class="text-xs font-extrabold text-slate-400 uppercase tracking-wider mb-3">Kategori Kendaraan</h3>
        <div class="grid grid-cols-2 gap-4">
            <a href="{{ route('pelanggan.antrian.create', ['jenis' => 'motor']) }}" 
               class="deal-card p-4 flex items-center gap-3 hover:border-amber-400 hover:shadow-md transition-all btn-press">
                <div class="w-10 h-10 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-base">
                    <i class="fa-solid fa-motorcycle"></i>
                </div>
                <div>
                    <h4 class="text-xs font-extrabold text-slate-900">Cuci Motor</h4>
                    <p class="text-[10px] text-slate-400 font-semibold">Reguler & Salju</p>
                </div>
            </a>

            <a href="{{ route('pelanggan.antrian.create', ['jenis' => 'mobil']) }}" 
               class="deal-card p-4 flex items-center gap-3 hover:border-indigo-400 hover:shadow-md transition-all btn-press">
                <div class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-base">
                    <i class="fa-solid fa-car"></i>
                </div>
                <div>
                    <h4 class="text-xs font-extrabold text-slate-900">Cuci Mobil</h4>
                    <p class="text-[10px] text-slate-400 font-semibold">Hidrolik & Wax</p>
                </div>
            </a>
        </div>
    </div>

    {{-- ── 4. Histori Pendaftaran Terbaru ── --}}
    <div class="deal-card overflow-hidden">
        <div class="p-5 border-b border-slate-100">
            <h3 class="text-sm font-extrabold text-slate-900">Histori Antrian Anda</h3>
            <p class="text-xs text-slate-400 font-medium">Riwayat pendaftaran antrian cuci kendaraan</p>
        </div>

        <div class="divide-y divide-slate-100">
            @forelse($riwayatAntrians as $riwayat)
                <div class="p-4 flex items-center justify-between hover:bg-slate-50/70 transition-colors">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-2xl bg-slate-100 text-slate-700 font-extrabold text-xs flex items-center justify-center">
                            {{ $riwayat->nomor_antrian }}
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-900">{{ $riwayat->jenisLayanan->nama_layanan ?? '-' }}</p>
                            <p class="text-[10px] text-slate-400 font-medium">
                                {{ $riwayat->tanggal_booking ? $riwayat->tanggal_booking->format('d/m/Y') : $riwayat->created_at->format('d/m/Y') }} &bull; <span class="font-mono uppercase font-bold text-slate-600">{{ $riwayat->no_plat }}</span>
                            </p>
                        </div>
                    </div>

                    <div>
                        @if($riwayat->status === 'selesai')
                            <span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 text-[10px] font-bold rounded-full border border-emerald-100">Selesai</span>
                        @elseif($riwayat->status === 'diproses')
                            <span class="px-2.5 py-1 bg-blue-50 text-brand text-[10px] font-bold rounded-full border border-blue-100">Diproses</span>
                        @elseif($riwayat->status === 'menunggu')
                            <span class="px-2.5 py-1 bg-amber-50 text-amber-700 text-[10px] font-bold rounded-full border border-amber-100">Menunggu</span>
                        @else
                            <span class="px-2.5 py-1 bg-slate-100 text-slate-500 text-[10px] font-bold rounded-full">Batal</span>
                        @endif
                    </div>
                </div>
            @empty
                <div class="p-6 text-center text-xs text-slate-400 font-medium">
                    Belum ada riwayat antrian.
                </div>
            @endforelse
        </div>
    </div>

</div>
@endsection

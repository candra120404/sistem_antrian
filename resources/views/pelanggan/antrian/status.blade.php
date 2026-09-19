@extends('layouts.pelanggan')

@section('title', 'Status Antrian')

@section('content')
{{-- ── Header ── --}}
<div class="mb-6 animate-fade-up">
    <h2 class="text-lg font-extrabold text-slate-800 tracking-tight">Status Antrian</h2>
    <p class="text-xs text-slate-400 font-medium">Pantau progres kendaraan Anda</p>
</div>

@if($antrian)
    @php
        $statusColors = [
            'menunggu' => ['bg' => 'bg-amber-50', 'text' => 'text-amber-600', 'border' => 'border-amber-100', 'dot' => 'bg-amber-500', 'label' => 'Menunggu'],
            'diproses' => ['bg' => 'bg-brand/5', 'text' => 'text-brand', 'border' => 'border-brand/10', 'dot' => 'bg-brand', 'label' => 'Diproses'],
            'selesai'  => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-600', 'border' => 'border-emerald-100', 'dot' => 'bg-emerald-500', 'label' => 'Selesai'],
        ];
        $sc = $statusColors[$antrian->status] ?? $statusColors['menunggu'];
    @endphp

    {{-- ── Main Queue Card ── --}}
    <div class="card-elevated p-6 mb-5 text-center animate-fade-up-1 relative overflow-hidden">
        {{-- Subtle background pulse --}}
        <div class="absolute inset-0 flex items-center justify-center opacity-[0.03]">
            <div class="w-64 h-64 bg-brand rounded-full animate-ping"></div>
        </div>

        <div class="relative z-10">
            <div class="flex items-center justify-center gap-2 mb-4">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full {{ $sc['dot'] }} opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 {{ $sc['dot'] }}"></span>
                </span>
                <span class="text-xs font-semibold {{ $sc['text'] }} uppercase tracking-wider">{{ $sc['label'] }}</span>
            </div>

            <p class="text-xs text-slate-400 font-medium mb-2">Nomor Pendaftaran</p>
            <h1 class="text-6xl font-black text-slate-900 tracking-tighter mb-4" id="nomor-antrian-display">
                {{ $antrian->nomor_antrian }}
            </h1>

            {{-- Progress Steps --}}
            <div class="flex items-center justify-center gap-2 mt-4">
                @php
                    $steps = ['menunggu' => 1, 'diproses' => 2, 'selesai' => 3];
                    $currentStep = $steps[$antrian->status] ?? 1;
                @endphp
                <div class="flex items-center gap-1.5">
                    <div class="w-2 h-2 rounded-full {{ $currentStep >= 1 ? 'bg-brand' : 'bg-slate-200' }}"></div>
                    <div class="w-6 h-0.5 rounded-full {{ $currentStep >= 2 ? 'bg-brand' : 'bg-slate-100' }}"></div>
                    <div class="w-2 h-2 rounded-full {{ $currentStep >= 2 ? 'bg-brand' : 'bg-slate-200' }}"></div>
                    <div class="w-6 h-0.5 rounded-full {{ $currentStep >= 3 ? 'bg-brand' : 'bg-slate-100' }}"></div>
                    <div class="w-2 h-2 rounded-full {{ $currentStep >= 3 ? 'bg-brand' : 'bg-slate-200' }}"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Stats Grid ── --}}
    <div class="grid grid-cols-2 gap-3 mb-5 animate-fade-up-2">
        <div class="card-elevated p-4 text-center">
            <div class="w-8 h-8 rounded-lg bg-brand/10 flex items-center justify-center mx-auto mb-2 text-brand">
                <i class="fa-solid fa-arrow-up-9-1 text-sm"></i>
            </div>
            <p class="text-[10px] text-slate-400 font-medium uppercase tracking-wider mb-0.5">Posisi</p>
            <h4 class="text-2xl font-black text-slate-800" id="posisi-display">
                {{ \App\Models\Antrian::hitungPosisi($antrian->id) }}
            </h4>
        </div>
        <div class="card-elevated p-4 text-center">
            <div class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center mx-auto mb-2 text-amber-600">
                <i class="fa-solid fa-users text-sm"></i>
            </div>
            <p class="text-[10px] text-slate-400 font-medium uppercase tracking-wider mb-0.5">Sisa Antrian</p>
            <h4 class="text-2xl font-black text-slate-800" id="total-menunggu-display">
                {{ $totalMenunggu }}
            </h4>
        </div>
    </div>

    {{-- ── Detail Cards ── --}}
    <div class="space-y-2.5 mb-6 animate-fade-up-3">
        <div class="card-elevated p-3.5 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-slate-50 flex items-center justify-center text-slate-400">
                    <i class="fa-solid fa-sparkles text-sm"></i>
                </div>
                <span class="text-sm font-medium text-slate-600">Layanan</span>
            </div>
            <span class="text-sm font-bold text-slate-800">{{ $antrian->jenisLayanan->nama_layanan }}</span>
        </div>

        <div class="card-elevated p-3.5 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-slate-50 flex items-center justify-center text-slate-400">
                    <i class="fa-solid fa-id-card text-sm"></i>
                </div>
                <span class="text-sm font-medium text-slate-600">Plat Nomor</span>
            </div>
            <span class="text-sm font-bold text-slate-800 font-mono tracking-wider">{{ $antrian->no_plat }}</span>
        </div>

        <div class="card-elevated p-3.5 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-slate-50 flex items-center justify-center text-slate-400">
                    <i class="fa-regular fa-clock text-sm"></i>
                </div>
                <span class="text-sm font-medium text-slate-600">Terdaftar</span>
            </div>
            <span class="text-sm font-bold text-slate-800">{{ $antrian->created_at->format('H:i') }} WIB</span>
        </div>
    </div>

    {{-- ── Live Indicator ── --}}
    <div class="flex flex-col items-center gap-2 py-3 animate-fade-up-3">
        <div class="flex items-center gap-2 px-3 py-1.5 bg-slate-50 rounded-full">
            <div class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></div>
            <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Live Update</span>
        </div>
    </div>
@else
    {{-- ── Empty State ── --}}
    <div class="text-center py-16 animate-fade-up-1">
        <div class="w-20 h-20 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-5 text-slate-300">
            <i class="fa-solid fa-ticket text-3xl"></i>
        </div>
        <h3 class="text-lg font-bold text-slate-800 mb-1.5">Tidak Ada Antrian</h3>
        <p class="text-sm text-slate-400 mb-8 px-6">Anda belum memiliki nomor antrian aktif hari ini.</p>

        <a href="{{ route('pelanggan.antrian.create') }}"
           class="btn-press inline-flex items-center gap-2 bg-brand text-white font-bold py-3.5 px-8 rounded-2xl shadow-lg shadow-brand/25">
            <i class="fa-solid fa-plus text-sm"></i>
            <span>Ambil Antrian</span>
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

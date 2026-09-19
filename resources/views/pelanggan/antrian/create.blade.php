@extends('layouts.pelanggan')

@section('title', 'Buat Antrian')

@section('content')
{{-- ── Header ── --}}
<div class="flex items-center gap-3 mb-6 animate-fade-up">
    <a href="{{ route('pelanggan.dashboard') }}" class="btn-press p-2.5 -ml-2.5 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-xl">
        <i class="fa-solid fa-arrow-left"></i>
    </a>
    <div>
        <h2 class="text-lg font-extrabold text-slate-800 tracking-tight">Pilih Layanan</h2>
        <p class="text-xs text-slate-400 font-medium">Lengkapi data pendaftaran</p>
    </div>
</div>

<form action="{{ route('pelanggan.antrian.store') }}" method="POST" id="form-antrian">
    @csrf

    {{-- Vehicle Type Indicator --}}
    @php
        $vehicleType = request('jenis', 'motor');
        $vehicleIcon = $vehicleType === 'motor' ? 'fa-motorcycle' : 'fa-car';
        $vehicleColor = $vehicleType === 'motor' ? 'text-amber-600 bg-amber-50' : 'text-indigo-600 bg-indigo-50';
    @endphp
    <div class="flex items-center gap-3 mb-5 animate-fade-up-1">
        <div class="w-10 h-10 rounded-xl {{ $vehicleColor }} flex items-center justify-center">
            <i class="fa-solid {{ $vehicleIcon }}"></i>
        </div>
        <div>
            <p class="text-sm font-semibold text-slate-700 capitalize">{{ $vehicleType }}</p>
            <p class="text-[10px] text-slate-400 font-medium">Pilih paket layanan</p>
        </div>
    </div>

    {{-- Layanan Cards --}}
    <div class="space-y-3 mb-6 animate-fade-up-1" x-data="{ selected: {{ $layanans->first()->id ?? 'null' }} }">
        @foreach($layanans as $l)
            <label 
                class="block relative cursor-pointer btn-press"
                @click="selected = {{ $l->id }}">
                <input type="radio" name="jenis_layanan_id" value="{{ $l->id }}"
                       class="peer sr-only"
                       data-harga="{{ $l->harga }}"
                       {{ $loop->first ? 'checked' : '' }}>
                
                <div class="p-4 rounded-2xl border-2 border-slate-100 bg-white transition-all peer-checked:border-brand peer-checked:bg-brand/5 peer-checked:shadow-md peer-checked:shadow-brand/10">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-5 h-5 rounded-full border-2 border-slate-200 flex items-center justify-center peer-checked:border-brand transition-all shrink-0">
                                <div class="w-2.5 h-2.5 rounded-full bg-brand opacity-0 peer-checked:opacity-100 transition-opacity"></div>
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-bold text-slate-800 truncate">{{ $l->nama_layanan }}</p>
                                <div class="flex items-center gap-2 mt-0.5">
                                    <span class="px-2 py-0.5 bg-slate-100 text-slate-700 rounded text-[10px] font-semibold">{{ $l->jenis_cuci ?? 'Cuci Reguler' }}</span>
                                    <span class="text-[10px] text-slate-400 font-medium flex items-center gap-1">
                                        <i class="fa-regular fa-clock"></i> {{ $l->est_durasi_menit ?? 20 }}m
                                    </span>
                                </div>
                                <p class="text-[11px] text-slate-500 mt-1 line-clamp-1">{{ $l->deskripsi }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 shrink-0 ml-2">
                            <p class="text-base font-extrabold text-brand">Rp{{ number_format($l->harga, 0, ',', '.') }}</p>
                            <i class="fa-solid fa-circle-check text-brand opacity-0 peer-checked:opacity-100 transition-opacity text-xs"></i>
                        </div>
                    </div>
                </div>
            </label>
        @endforeach
    </div>

    {{-- No Plat --}}
    <div class="mb-6 animate-fade-up-2">
        <label class="block text-sm font-semibold text-slate-700 mb-2">Nomor Plat Kendaraan</label>
        <div class="relative">
            <input type="text" name="no_plat" placeholder="B 1234 ABC"
                   class="w-full text-2xl font-black tracking-[0.2em] text-center uppercase bg-white border-2 border-slate-100 rounded-2xl py-5 px-4 focus:border-brand focus:outline-none focus:ring-4 focus:ring-brand/10 transition-all placeholder:text-slate-200 placeholder:tracking-normal"
                   required oninput="this.value = this.value.toUpperCase()">
        </div>
        @error('no_plat')
            <p class="text-red-500 text-xs font-medium mt-2 ml-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Summary Card --}}
    <div class="card-elevated p-4 mb-6 animate-fade-up-3">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-xs text-slate-400 font-medium">Total Estimasi</p>
                <p class="text-xs text-slate-400">Bayar saat selesai</p>
            </div>
            <span class="text-xl font-black text-brand tracking-tight" id="total-display">Rp 0</span>
        </div>
    </div>

    {{-- Submit --}}
    <button type="submit"
            class="btn-press w-full bg-brand hover:bg-brand-hover text-white font-bold py-4 rounded-2xl shadow-lg shadow-brand/25 text-base flex items-center justify-center gap-2 mb-4">
        <span>Konfirmasi Antrian</span>
        <i class="fa-solid fa-arrow-right text-sm"></i>
    </button>
    
    <p class="text-center text-[10px] text-slate-400 font-medium mb-4">
        Dengan mengkonfirmasi, Anda menyetujui syarat & ketentuan layanan.
    </p>
</form>
@endsection

@push('scripts')
<script>
    const radios = document.querySelectorAll('input[name="jenis_layanan_id"]');
    const display = document.getElementById('total-display');

    function updateTotal() {
        radios.forEach(r => {
            if (r.checked) {
                const harga = parseInt(r.dataset.harga);
                display.textContent = 'Rp' + harga.toLocaleString('id-ID');
            }
        });
    }

    radios.forEach(r => r.addEventListener('change', updateTotal));
    updateTotal();

    document.getElementById('form-antrian').onsubmit = function() {
        return confirm('Daftar antrian dengan data ini?');
    };
</script>
@endpush

@extends('layouts.pelanggan')

@section('title', 'Buat Antrian')

@section('content')
<div class="mt-8 mb-10">
    <a href="{{ route('pelanggan.dashboard') }}" class="inline-flex items-center gap-2 text-[10px] font-black text-slate-400 uppercase tracking-widest bg-slate-50 px-4 py-2 rounded-xl mb-6 hover:bg-slate-100 transition-colors">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
        Kembali
    </a>
    <h2 class="text-3xl font-black text-slate-900 tracking-tighter">Pilih Layanan</h2>
    <p class="text-slate-400 text-xs font-bold uppercase tracking-widest mt-1">Lengkapi data pendaftaran Anda</p>
</div>

<form action="{{ route('pelanggan.antrian.store') }}" method="POST" id="form-antrian" class="space-y-8">
    @csrf

    {{-- Pilih Layanan --}}
    <div class="space-y-4">
        <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Layanan Tersedia</p>
        <div class="grid grid-cols-1 gap-4">
            @foreach($layanans as $l)
                <label class="relative flex items-center p-6 rounded-3xl border-2 border-slate-50 cursor-pointer transition-all hover:bg-slate-50/50 has-[:checked]:border-brand has-[:checked]:bg-brand/5 has-[:checked]:shadow-xl has-[:checked]:shadow-brand/5 group">
                    <input type="radio" name="jenis_layanan_id" value="{{ $l->id }}"
                           class="w-5 h-5 text-brand border-slate-300 focus:ring-brand"
                           data-harga="{{ $l->harga }}"
                           required {{ $loop->first ? 'checked' : '' }}>
                    <div class="ml-5 flex-1">
                        <p class="font-black text-slate-800 tracking-tight">{{ $l->nama_layanan }}</p>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">{{ $l->jenis_kendaraan }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-lg font-black text-brand tracking-tighter">Rp{{ number_format($l->harga, 0, ',', '.') }}</p>
                    </div>
                    {{-- Active Indicator Icon --}}
                    <div class="absolute right-6 top-1/2 -translate-y-1/2 opacity-0 group-has-[:checked]:opacity-100 transition-opacity">
                         <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#2563EB" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    </div>
                </label>
            @endforeach
        </div>
    </div>

    {{-- No Plat --}}
    <div class="space-y-4 pt-4">
        <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Nomor Plat Kendaraan</p>
        <div class="relative">
            <input type="text" name="no_plat" placeholder="Contoh: B 1234 ABC"
                   class="w-full text-3xl font-black tracking-widest text-center border-2 border-slate-100 bg-white rounded-3xl py-8 focus:border-brand focus:outline-none transition-all placeholder:text-slate-100 uppercase shadow-sm"
                   required oninput="this.value = this.value.toUpperCase()">
            <div class="absolute left-6 top-1/2 -translate-y-1/2 text-slate-200">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="10" rx="2" ry="2"></rect><circle cx="7" cy="16" r="1"></circle><circle cx="17" cy="16" r="1"></circle><path d="M5 11l1.5-4.5h11L19 11"></path></svg>
            </div>
        </div>
        @error('no_plat')
            <p class="text-red-500 text-[10px] font-black uppercase tracking-widest mt-2 ml-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Summary --}}
    <div class="p-8 bg-slate-900 rounded-[2.5rem] text-white">
        <div class="flex justify-between items-center mb-1">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Total Estimasi:</span>
            <span class="text-2xl font-black text-brand tracking-tighter" id="total-display">Rp 0</span>
        </div>
        <p class="text-[10px] text-slate-500 font-medium">
            *Silakan melakukan pembayaran saat unit selesai dikerjakan.
        </p>
    </div>

    <button type="submit"
            class="group w-full bg-brand hover:bg-brand-hover text-white font-black py-5 rounded-[2rem] shadow-2xl shadow-brand/20 transition-all active:scale-[0.98] text-lg flex items-center justify-center gap-3">
        <span>Konfirmasi Antrian</span>
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="group-hover:translate-x-1 transition-transform"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
    </button>
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

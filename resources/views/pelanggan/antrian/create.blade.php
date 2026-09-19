@extends('layouts.pelanggan')

@section('title', 'Buat Antrian')

@section('content')
<div class="space-y-6" x-data="{ 
    pilihanTanggal: '{{ $pilihanTanggal ?? 'hari_ini' }}',
    selectedLayanan: {{ $layanans->first()->id ?? 0 }},
    selectedHarga: {{ $layanans->first()->harga ?? 0 }},
    confirmModal: false
}">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">Form Pendaftaran Antrian</h2>
            <p class="text-xs font-semibold text-slate-400 mt-0.5">Pilih tanggal booking, paket cuci, dan plat kendaraan</p>
        </div>
        <a href="{{ route('pelanggan.dashboard') }}" class="w-9 h-9 rounded-2xl bg-white border border-slate-200/60 flex items-center justify-center text-slate-500 hover:text-slate-900 shadow-sm">
            <i class="fa-solid fa-xmark text-sm"></i>
        </a>
    </div>

    <form action="{{ route('pelanggan.antrian.store') }}" method="POST" id="form-buat-antrian">
        @csrf
        <input type="hidden" name="pilihan_tanggal" x-model="pilihanTanggal">

        {{-- ── 1. Tab Pemilihan Tanggal Booking (Hari Ini vs Besok) ── --}}
        <div class="deal-card p-2 grid grid-cols-2 gap-2">
            <button type="button" 
                    @click="pilihanTanggal = 'hari_ini'"
                    :class="pilihanTanggal === 'hari_ini' ? 'bg-brand text-white shadow-md shadow-brand/20' : 'text-slate-600 hover:bg-slate-50'"
                    class="py-3 px-4 rounded-2xl text-xs font-extrabold flex flex-col items-center justify-center transition-all btn-press">
                <span class="flex items-center gap-1.5">
                    <i class="fa-solid fa-calendar-day"></i> Hari Ini
                </span>
                <span class="text-[10px] font-semibold opacity-90 mt-0.5">{{ today()->translatedFormat('d F Y') }}</span>
            </button>

            <button type="button" 
                    @click="pilihanTanggal = 'besok'"
                    :class="pilihanTanggal === 'besok' ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20' : 'text-slate-600 hover:bg-slate-50'"
                    class="py-3 px-4 rounded-2xl text-xs font-extrabold flex flex-col items-center justify-center transition-all btn-press">
                <span class="flex items-center gap-1.5">
                    <i class="fa-solid fa-calendar-plus"></i> Booking Besok
                </span>
                <span class="text-[10px] font-semibold opacity-90 mt-0.5">{{ today()->addDay()->translatedFormat('d F Y') }}</span>
            </button>
        </div>

        {{-- Indikator Tanggal Dipilih --}}
        <div class="deal-card p-4 border-l-4" :class="pilihanTanggal === 'besok' ? 'border-l-indigo-600 bg-indigo-50/30' : 'border-l-brand bg-blue-50/30'">
            <div class="flex items-center justify-between text-xs">
                <span class="font-bold text-slate-700">Tanggal Booking Terpilih:</span>
                <span class="font-extrabold" :class="pilihanTanggal === 'besok' ? 'text-indigo-700' : 'text-brand'">
                    <template x-if="pilihanTanggal === 'hari_ini'">
                        <span>Hari Ini ({{ today()->translatedFormat('d F Y') }})</span>
                    </template>
                    <template x-if="pilihanTanggal === 'besok'">
                        <span>Besok ({{ today()->addDay()->translatedFormat('d F Y') }})</span>
                    </template>
                </span>
            </div>
        </div>

        {{-- ── 2. Layanan Cards ── --}}
        <div class="space-y-3 my-5">
            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Pilih Paket Layanan Cuci</label>
            
            @foreach($layanans as $l)
                <div class="deal-card p-4 cursor-pointer transition-all border-2 btn-press"
                     :class="selectedLayanan === {{ $l->id }} ? 'border-brand bg-blue-50/20 shadow-md shadow-brand/10' : 'border-slate-100 hover:border-slate-200'"
                     @click="selectedLayanan = {{ $l->id }}; selectedHarga = {{ $l->harga }}">
                    
                    <input type="radio" name="jenis_layanan_id" value="{{ $l->id }}" class="sr-only" x-model="selectedLayanan">

                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-5 h-5 rounded-full border-2 border-slate-300 flex items-center justify-center transition-all"
                                 :class="selectedLayanan === {{ $l->id }} ? 'border-brand bg-brand' : ''">
                                <div class="w-2 h-2 rounded-full bg-white"></div>
                            </div>
                            <div>
                                <h4 class="text-sm font-extrabold text-slate-900 leading-tight">{{ $l->nama_layanan }}</h4>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="px-2 py-0.5 bg-slate-100 text-slate-700 rounded text-[10px] font-bold">{{ $l->jenis_cuci ?? 'Cuci Reguler' }}</span>
                                    <span class="text-[10px] font-semibold text-slate-400 flex items-center gap-1">
                                        <i class="fa-regular fa-clock"></i> {{ $l->est_durasi_menit ?? 20 }} Menit
                                    </span>
                                </div>
                                <p class="text-[11px] text-slate-400 font-medium mt-1">{{ $l->deskripsi }}</p>
                            </div>
                        </div>

                        <div class="text-right shrink-0 ml-3">
                            <span class="text-base font-extrabold text-slate-900 block">Rp {{ number_format($l->harga, 0, ',', '.') }}</span>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">{{ $l->jenis_kendaraan }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- ── 3. Plat Nomor Kendaraan ── --}}
        <div class="deal-card p-5 space-y-2 mb-6">
            <label for="no_plat" class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Nomor Plat Kendaraan</label>
            <input type="text" 
                   id="no_plat" 
                   name="no_plat" 
                   placeholder="misal: B 1234 ABC"
                   value="{{ old('no_plat') }}"
                   required
                   oninput="this.value = this.value.toUpperCase()"
                   class="w-full px-4 py-3 bg-slate-50 border border-slate-200/80 rounded-2xl text-lg font-extrabold text-center uppercase tracking-widest text-slate-900 focus:bg-white focus:border-brand focus:outline-none transition-all">
            <p class="text-[10px] font-semibold text-slate-400 text-center">Pastikan nomor plat diisi dengan benar untuk verifikasi kedatangan.</p>
            @error('no_plat')
                <p class="text-xs text-red-500 font-bold text-center mt-1">{{ $message }}</p>
            @errorEnd
        </div>

        {{-- ── 4. Ringkasan & Submit Button ── --}}
        <div class="space-y-3">
            <div class="deal-card p-4 flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500">Total Biaya Layanan:</span>
                <span class="text-xl font-extrabold text-brand" x-text="'Rp ' + selectedHarga.toLocaleString('id-ID')"></span>
            </div>

            <button type="button" 
                    @click="confirmModal = true"
                    class="w-full py-4 bg-brand hover:bg-brand-hover text-white text-sm font-extrabold rounded-2xl shadow-xl shadow-brand/25 transition-all flex items-center justify-center gap-2 btn-press">
                <span>Konfirmasi Pendaftaran</span>
                <i class="fa-solid fa-arrow-right text-xs"></i>
            </button>
        </div>

        {{-- ── Custom DealDeck Popup Modal Konfirmasi Booking (Alpine.js) ── --}}
        <div x-show="confirmModal" 
             x-cloak
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm">
            
            <div x-show="confirmModal"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                 x-transition:leave-end="opacity-0 scale-95 translate-y-2"
                 class="bg-white rounded-3xl max-w-sm w-full p-6 shadow-2xl border border-slate-100 text-center space-y-4"
                 @click.away="confirmModal = false">
                
                <div class="w-12 h-12 rounded-2xl bg-blue-50 text-brand flex items-center justify-center mx-auto text-xl font-bold">
                    <i class="fa-solid fa-ticket"></i>
                </div>

                <div>
                    <h3 class="text-base font-extrabold text-slate-900 tracking-tight">Konfirmasi Booking Antrian</h3>
                    <p class="text-xs font-medium text-slate-500 mt-1 leading-relaxed">
                        Anda akan mendaftar antrian cuci untuk <strong class="text-slate-900" x-text="pilihanTanggal === 'besok' ? 'BESOK' : 'HARI INI'"></strong>. Apakah data yang Anda masukkan sudah benar?
                    </p>
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="button" @click="confirmModal = false"
                            class="flex-1 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-2xl transition-all">
                        Periksa Lagi
                    </button>
                    <button type="button" @click="document.getElementById('form-buat-antrian').submit()"
                            class="flex-1 py-3 bg-brand hover:bg-brand-hover text-white text-xs font-extrabold rounded-2xl shadow-lg shadow-brand/20 transition-all">
                        Ya, Daftarkan
                    </button>
                </div>
            </div>
        </div>

    </form>
</div>
@endsection

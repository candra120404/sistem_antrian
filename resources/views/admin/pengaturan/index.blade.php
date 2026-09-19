@extends('layouts.admin')

@section('title', 'Settings')
@section('page-title', 'Settings')

@section('content')
<div class="max-w-3xl space-y-6">

    {{-- Card Header Info --}}
    <div class="bg-gradient-to-r from-slate-900 to-slate-800 rounded-3xl p-6 text-white shadow-xl shadow-slate-900/10 flex items-center gap-4">
        <div class="w-12 h-12 rounded-2xl bg-brand/20 text-brand flex items-center justify-center text-xl font-extrabold border border-brand/30 shrink-0">
            <i class="fa-solid fa-sliders"></i>
        </div>
        <div>
            <h3 class="text-base font-extrabold">Kelola Batas Kuota & Aturan Booking</h3>
            <p class="text-xs text-slate-300 mt-1 leading-relaxed">
                Tentukan kapasitas maksimal pelanggan harian dan batas jumlah booking aktif untuk menjaga kualitas performa antrian.
            </p>
        </div>
    </div>

    {{-- Form Pengaturan DealDeck Style --}}
    <div class="deal-card p-6">
        <form method="POST" action="{{ route('admin.pengaturan.update') }}" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Fitur 1: Batas Maksimal Pelanggan Harian --}}
            <div class="p-5 bg-slate-50 rounded-2xl border border-slate-200/60 space-y-2">
                <div class="flex items-center justify-between">
                    <label for="batas_maksimal_pelanggan_harian" class="text-sm font-bold text-slate-900 flex items-center gap-2">
                        <i class="fa-solid fa-users-line text-brand"></i>
                        Batas Maksimal Pelanggan Per Hari
                    </label>
                    <span class="text-[10px] font-extrabold px-2.5 py-0.5 bg-brand/10 text-brand rounded-full">Fitur #1</span>
                </div>
                <p class="text-xs text-slate-400 font-medium">
                    Jumlah total pelanggan/antrian maksimal yang dapat ditampung bengkel dalam kurun waktu satu hari.
                </p>
                <div class="relative mt-2">
                    <input type="number" 
                           id="batas_maksimal_pelanggan_harian" 
                           name="batas_maksimal_pelanggan_harian" 
                           value="{{ old('batas_maksimal_pelanggan_harian', $pengaturan->batas_maksimal_pelanggan_harian) }}" 
                           min="1" 
                           required 
                           class="w-full px-4 py-2.5 bg-white border border-slate-200/80 rounded-2xl text-xs font-extrabold text-slate-900 focus:border-brand focus:outline-none transition-all">
                    <span class="absolute right-4 top-2.5 text-xs text-slate-400 font-bold">pelanggan / hari</span>
                </div>
                @error('batas_maksimal_pelanggan_harian')
                    <p class="text-xs text-red-500 mt-1 font-semibold">{{ $message }}</p>
                @enderror
            </div>

            {{-- Fitur 2: Minimum & Maksimum Booking --}}
            <div class="p-5 bg-slate-50 rounded-2xl border border-slate-200/60 space-y-4">
                <div class="flex items-center justify-between">
                    <label class="text-sm font-bold text-slate-900 flex items-center gap-2">
                        <i class="fa-solid fa-bookmark text-indigo-600"></i>
                        Batas Minimum & Maksimum Booking Per Pelanggan
                    </label>
                    <span class="text-[10px] font-extrabold px-2.5 py-0.5 bg-indigo-50 text-indigo-600 rounded-full">Fitur #2</span>
                </div>
                <p class="text-xs text-slate-400 font-medium">
                    Batas jumlah antrian aktif yang boleh dibuat oleh satu akun pelanggan dalam satu hari.
                </p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-1">
                    <div>
                        <label for="min_booking_per_user" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Minimum Booking</label>
                        <input type="number" 
                               id="min_booking_per_user" 
                               name="min_booking_per_user" 
                               value="{{ old('min_booking_per_user', $pengaturan->min_booking_per_user) }}" 
                               min="1" 
                               required 
                               class="w-full px-4 py-2.5 bg-white border border-slate-200/80 rounded-2xl text-xs font-extrabold text-slate-900 focus:border-brand focus:outline-none transition-all">
                        <p class="text-[10px] text-slate-400 font-semibold mt-1">Default pendaftaran minimal (1 booking)</p>
                    </div>

                    <div>
                        <label for="max_booking_per_user" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Maksimum Booking Aktif</label>
                        <input type="number" 
                               id="max_booking_per_user" 
                               name="max_booking_per_user" 
                               value="{{ old('max_booking_per_user', $pengaturan->max_booking_per_user) }}" 
                               min="1" 
                               required 
                               class="w-full px-4 py-2.5 bg-white border border-slate-200/80 rounded-2xl text-xs font-extrabold text-slate-900 focus:border-brand focus:outline-none transition-all">
                        <p class="text-[10px] text-slate-400 font-semibold mt-1">Maksimal antrian menunggu per user</p>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
                <button type="submit" class="px-6 py-3 bg-brand hover:bg-brand-hover text-white text-xs font-extrabold rounded-2xl shadow-lg shadow-brand/20 transition-all flex items-center gap-2">
                    <i class="fa-solid fa-floppy-disk"></i>
                    Simpan Settings
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

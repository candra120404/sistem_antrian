@extends('layouts.admin')

@section('title', 'Pengaturan Kuota & Booking')
@section('page-title', 'Pengaturan Kuota & Limit Booking')

@section('content')
<div class="max-w-3xl space-y-6">

    {{-- Card Header Info --}}
    <div class="bg-gradient-to-r from-slate-900 to-slate-800 rounded-2xl p-6 text-white shadow-xl shadow-slate-900/10">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-brand/20 text-brand flex items-center justify-center text-xl font-bold border border-brand/30">
                <i class="fa-solid fa-sliders"></i>
            </div>
            <div>
                <h2 class="text-lg font-bold">Kelola Batas Kuota & Aturan Booking</h2>
                <p class="text-xs text-slate-300 mt-1">
                    Tentukan kapasitas maksimal pelanggan harian dan batas jumlah booking aktif untuk mencegah kelebihan kapasitas antrian.
                </p>
            </div>
        </div>
    </div>

    {{-- Form Pengaturan --}}
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200/60">
        <form method="POST" action="{{ route('admin.pengaturan.update') }}" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Fitur 1: Batas Maksimal Pelanggan Harian --}}
            <div class="p-4 bg-slate-50 rounded-xl border border-slate-200/60 space-y-2">
                <div class="flex items-center justify-between">
                    <label for="batas_maksimal_pelanggan_harian" class="text-sm font-bold text-slate-800 flex items-center gap-2">
                        <i class="fa-solid fa-users-line text-brand"></i>
                        Batas Maksimal Pelanggan Per Hari
                    </label>
                    <span class="text-xs font-semibold px-2.5 py-0.5 bg-brand/10 text-brand rounded-full">Fitur #1</span>
                </div>
                <p class="text-xs text-slate-500">
                    Jumlah total pelanggan/antrian maksimal yang dapat ditampung bengkel dalam kurun waktu satu hari.
                </p>
                <div class="relative mt-2">
                    <input type="number" 
                           id="batas_maksimal_pelanggan_harian" 
                           name="batas_maksimal_pelanggan_harian" 
                           value="{{ old('batas_maksimal_pelanggan_harian', $pengaturan->batas_maksimal_pelanggan_harian) }}" 
                           min="1" 
                           required 
                           class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm font-semibold text-slate-900 focus:border-brand focus:ring-2 focus:ring-brand/20 transition-all">
                    <span class="absolute right-3.5 top-2.5 text-xs text-slate-400 font-medium">pelanggan / hari</span>
                </div>
                @error('batas_maksimal_pelanggan_harian')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Fitur 2: Minimum & Maksimum Booking --}}
            <div class="p-4 bg-slate-50 rounded-xl border border-slate-200/60 space-y-4">
                <div class="flex items-center justify-between">
                    <label class="text-sm font-bold text-slate-800 flex items-center gap-2">
                        <i class="fa-solid fa-bookmark text-indigo-600"></i>
                        Batas Minimum & Maksimum Booking Per Pelanggan
                    </label>
                    <span class="text-xs font-semibold px-2.5 py-0.5 bg-indigo-50 text-indigo-600 rounded-full">Fitur #2</span>
                </div>
                <p class="text-xs text-slate-500">
                    Batas jumlah antrian aktif yang boleh dibuat oleh satu akun pelanggan dalam satu hari.
                </p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-1">
                    <div>
                        <label for="min_booking_per_user" class="block text-xs font-semibold text-slate-600 mb-1">Minimum Booking</label>
                        <input type="number" 
                               id="min_booking_per_user" 
                               name="min_booking_per_user" 
                               value="{{ old('min_booking_per_user', $pengaturan->min_booking_per_user) }}" 
                               min="1" 
                               required 
                               class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm font-semibold text-slate-900 focus:border-brand focus:ring-2 focus:ring-brand/20 transition-all">
                        <p class="text-[11px] text-slate-400 mt-1">Default pendaftaran minimal (1 booking)</p>
                    </div>

                    <div>
                        <label for="max_booking_per_user" class="block text-xs font-semibold text-slate-600 mb-1">Maksimum Booking Aktif</label>
                        <input type="number" 
                               id="max_booking_per_user" 
                               name="max_booking_per_user" 
                               value="{{ old('max_booking_per_user', $pengaturan->max_booking_per_user) }}" 
                               min="1" 
                               required 
                               class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm font-semibold text-slate-900 focus:border-brand focus:ring-2 focus:ring-brand/20 transition-all">
                        <p class="text-[11px] text-slate-400 mt-1">Maksimal antrian menunggu yang diizinkan per user</p>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
                <button type="submit" class="px-6 py-2.5 bg-brand hover:bg-brand-hover text-white text-sm font-bold rounded-xl shadow-lg shadow-brand/20 transition-all flex items-center gap-2">
                    <i class="fa-solid fa-floppy-disk"></i>
                    Simpan Pengaturan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

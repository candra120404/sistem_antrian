@extends('layouts.admin')

@section('title', 'Kelola Harga')
@section('page-title', 'Konfigurasi Harga')

@section('content')
<div class="max-w-5xl">
    <div class="bg-white rounded-[2.5rem] shadow-[0_8px_40px_rgba(0,0,0,0.02)] border border-slate-100 overflow-hidden">
        <div class="p-8 border-b border-slate-50 flex items-center justify-between">
            <div>
                <h3 class="text-lg font-black text-slate-800 tracking-tight">Daftar Layanan</h3>
                <p class="text-xs text-slate-400 font-medium mt-1">Kelola tarif untuk berbagai jenis kendaraan</p>
            </div>
            <div class="p-2.5 bg-slate-50 rounded-2xl text-slate-400">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 text-slate-400 text-[10px] uppercase font-black tracking-[0.15em]">
                        <th class="px-8 py-5">Nama Layanan</th>
                        <th class="px-8 py-5">Kategori</th>
                        <th class="px-8 py-5 text-right">Harga</th>
                        <th class="px-8 py-5 text-center">Status</th>
                        <th class="px-8 py-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($layanans as $l)
                    <tr class="hover:bg-slate-50/30 transition-colors group">
                        <td class="px-8 py-6">
                            <span class="font-bold text-slate-800 tracking-tight">{{ $l->nama_layanan }}</span>
                        </td>
                        <td class="px-8 py-6">
                            <span class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider {{ $l->jenis_kendaraan == 'motor' ? 'bg-amber-100 text-amber-700' : 'bg-indigo-100 text-indigo-700' }}">
                                {{ $l->jenis_kendaraan }}
                            </span>
                        </td>
                        <td class="px-8 py-6 text-right font-black text-slate-700 tracking-tight">
                            Rp {{ number_format($l->harga, 0, ',', '.') }}
                        </td>
                        <td class="px-8 py-6 text-center">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-widest {{ $l->is_active ? 'bg-emerald-50 text-emerald-600' : 'bg-red-50 text-red-500' }}">
                                {{ $l->is_active ? 'Aktif' : 'Non-Aktif' }}
                            </span>
                        </td>
                        <td class="px-8 py-6 text-right">
                             <button onclick="openModal({{ $l->id }}, '{{ $l->nama_layanan }}', {{ $l->harga }}, {{ $l->is_active }})"
                                    class="p-2 text-slate-400 hover:text-brand transition-colors">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ── Modal Edit ── --}}
<div id="modal-edit" class="fixed inset-0 bg-slate-900/40 hidden z-50 flex items-center justify-center p-4 backdrop-blur-md transition-all duration-300">
    <div class="bg-white rounded-[2.5rem] w-full max-w-md shadow-2xl overflow-hidden scale-95 opacity-0 transition-all duration-300" id="modal-content">
        <div class="bg-primary p-10 text-white text-center relative overflow-hidden">
             <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full -mr-16 -mt-16"></div>
            <h3 class="text-2xl font-black tracking-tight">Update Harga</h3>
            <p class="text-slate-400 text-xs font-bold uppercase tracking-widest mt-2" id="modal-title-name"></p>
        </div>

        <form id="form-update" method="POST" class="p-10 space-y-8">
            @csrf @method('PUT')

            <div class="space-y-2">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Tarif Layanan (Rp)</label>
                <div class="relative group">
                    <span class="absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 font-bold group-focus-within:text-brand transition-colors text-sm">Rp</span>
                    <input type="number" name="harga" id="input-harga"
                           class="w-full pl-14 pr-6 py-4 bg-slate-50 border-2 border-slate-50 rounded-2xl focus:bg-white focus:border-brand focus:outline-none transition-all font-black text-lg text-slate-800"
                           required min="1000">
                </div>
            </div>

            <label class="flex items-center gap-4 bg-slate-50 p-5 rounded-2xl cursor-pointer hover:bg-slate-100/50 transition-colors">
                <div class="relative inline-flex items-center cursor-pointer">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" id="input-active" value="1" class="sr-only peer">
                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-brand/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-brand"></div>
                </div>
                <span class="text-sm font-bold text-slate-600">Layanan ini aktif</span>
            </label>

            <div class="flex gap-3">
                <button type="button" onclick="closeModal()"
                        class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-500 font-black py-4 rounded-2xl transition-all">
                    Batal
                </button>
                <button type="submit"
                        class="flex-1 bg-brand hover:bg-brand-hover text-white font-black py-4 rounded-2xl transition-all shadow-lg shadow-brand/20">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const modal = document.getElementById('modal-edit');
    const modalContent = document.getElementById('modal-content');
    const form = document.getElementById('form-update');
    const titleName = document.getElementById('modal-title-name');
    const inputHarga = document.getElementById('input-harga');
    const inputActive = document.getElementById('input-active');

    function openModal(id, name, harga, active) {
        form.action = `/admin/harga/${id}`;
        titleName.textContent = name;
        inputHarga.value = parseInt(harga);
        inputActive.checked = !!active;

        modal.classList.remove('hidden');
        setTimeout(() => {
            modalContent.classList.remove('scale-95', 'opacity-0');
            modalContent.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeModal() {
        modalContent.classList.remove('scale-100', 'opacity-100');
        modalContent.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    window.onclick = (e) => { if (e.target == modal) closeModal(); }
</script>
@endpush

@extends('layouts.admin')

@section('title', 'Products & Harga')
@section('page-title', 'Products & Harga')

@section('content')
<div class="space-y-6 max-w-6xl">
    <div class="deal-card overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-base font-extrabold text-slate-900 tracking-tight">Daftar Paket & Jenis Cuci</h3>
                <p class="text-xs text-slate-400 font-medium">Kelola tarif, jenis pencucian, deskripsi fasilitas, dan durasi pengerjaan</p>
            </div>
            <div class="w-10 h-10 rounded-2xl bg-blue-50 text-brand flex items-center justify-center">
                <i class="fa-solid fa-box text-base"></i>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-400 text-[11px] font-bold uppercase tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4">Nama Paket & Kendaraan</th>
                        <th class="px-6 py-4">Jenis Cuci & Fasilitas</th>
                        <th class="px-6 py-4 text-center">Estimasi Durasi</th>
                        <th class="px-6 py-4 text-right">Tarif (Rp)</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($layanans as $l)
                    <tr class="hover:bg-slate-50/70 transition-colors">
                        <td class="px-6 py-4">
                            <span class="font-bold text-slate-900 block text-sm">{{ $l->nama_layanan }}</span>
                            <span class="inline-block mt-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase {{ $l->jenis_kendaraan == 'motor' ? 'bg-amber-100 text-amber-800' : 'bg-indigo-100 text-indigo-800' }}">
                                {{ $l->jenis_kendaraan }}
                            </span>
                        </td>
                        <td class="px-6 py-4 max-w-xs">
                            <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-slate-100 text-slate-800 block w-fit mb-1">
                                {{ $l->jenis_cuci ?? 'Cuci Standar' }}
                            </span>
                            <p class="text-xs text-slate-400 leading-relaxed truncate" title="{{ $l->deskripsi }}">
                                {{ $l->deskripsi ?? 'Pencucian bodi kendaraan' }}
                            </p>
                        </td>
                        <td class="px-6 py-4 text-center font-bold text-slate-700">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-slate-100 rounded-xl text-xs font-semibold">
                                <i class="fa-regular fa-clock text-slate-400"></i>
                                {{ $l->est_durasi_menit ?? 20 }} Menit
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right font-extrabold text-slate-900 text-base">
                            Rp {{ number_format($l->harga, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-3 py-1 rounded-full text-xs font-bold {{ $l->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-red-50 text-red-600 border border-red-100' }}">
                                {{ $l->is_active ? 'Aktif' : 'Non-Aktif' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                             <button onclick="openModal({{ $l->id }}, '{{ addslashes($l->nama_layanan) }}', '{{ addslashes($l->jenis_cuci ?? '') }}', '{{ addslashes($l->deskripsi ?? '') }}', {{ $l->est_durasi_menit ?? 20 }}, {{ $l->harga }}, {{ $l->is_active ? 1 : 0 }})"
                                     class="px-3.5 py-1.5 bg-slate-100 hover:bg-brand hover:text-white text-slate-700 rounded-xl transition-all text-xs font-bold inline-flex items-center gap-1.5">
                                <i class="fa-solid fa-pen-to-square"></i> Edit
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ── Modal Edit DealDeck Style ── --}}
<div id="modal-edit" class="fixed inset-0 bg-slate-900/50 hidden z-50 flex items-center justify-center p-4 backdrop-blur-sm transition-all duration-300">
    <div class="bg-white rounded-3xl w-full max-w-lg shadow-2xl border border-slate-100 overflow-hidden scale-95 opacity-0 transition-all duration-300" id="modal-content">
        <div class="bg-slate-900 p-6 text-white relative">
            <h3 class="text-base font-extrabold">Edit Detail & Harga Layanan</h3>
            <p class="text-slate-400 text-xs mt-0.5" id="modal-title-name"></p>
            <button onclick="closeModal()" class="absolute top-5 right-5 text-slate-400 hover:text-white">
                <i class="fa-solid fa-xmark text-base"></i>
            </button>
        </div>

        <form id="form-update" method="POST" class="p-6 space-y-4">
            @csrf 
            @method('PUT')

            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Jenis Cuci Mobil/Motor</label>
                <input type="text" name="jenis_cuci" id="input-jenis-cuci" placeholder="misal: Cuci Hidrolik Salju"
                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200/80 rounded-2xl text-xs font-bold text-slate-900 focus:bg-white focus:border-brand focus:outline-none transition-all">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Deskripsi Fasilitas & Detail</label>
                <textarea name="deskripsi" id="input-deskripsi" rows="3" placeholder="Rincian pembersihan..."
                          class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200/80 rounded-2xl text-xs font-medium text-slate-900 focus:bg-white focus:border-brand focus:outline-none transition-all"></textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Estimasi Durasi (Menit)</label>
                    <input type="number" name="est_durasi_menit" id="input-durasi" min="5"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200/80 rounded-2xl text-xs font-bold text-slate-900 focus:bg-white focus:border-brand focus:outline-none transition-all" required>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Tarif Layanan (Rp)</label>
                    <input type="number" name="harga" id="input-harga" min="1000"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200/80 rounded-2xl text-xs font-extrabold text-slate-900 focus:bg-white focus:border-brand focus:outline-none transition-all" required>
                </div>
            </div>

            <label class="flex items-center gap-3 bg-slate-50 p-3.5 rounded-2xl cursor-pointer hover:bg-slate-100/80 transition-colors border border-slate-200/60">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" id="input-active" value="1" class="w-4 h-4 text-brand rounded border-slate-300 focus:ring-brand">
                <span class="text-xs font-bold text-slate-700">Status Layanan Aktif</span>
            </label>

            <div class="flex gap-3 pt-2">
                <button type="button" onclick="closeModal()"
                        class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold py-3 rounded-2xl transition-all">
                    Batal
                </button>
                <button type="submit"
                        class="flex-1 bg-brand hover:bg-brand-hover text-white text-xs font-extrabold py-3 rounded-2xl transition-all shadow-lg shadow-brand/20">
                    Simpan Perubahan
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
    const inputJenisCuci = document.getElementById('input-jenis-cuci');
    const inputDeskripsi = document.getElementById('input-deskripsi');
    const inputDurasi = document.getElementById('input-durasi');
    const inputHarga = document.getElementById('input-harga');
    const inputActive = document.getElementById('input-active');

    function openModal(id, name, jenisCuci, deskripsi, durasi, harga, active) {
        form.action = `/admin/harga/${id}`;
        titleName.textContent = name;
        inputJenisCuci.value = jenisCuci;
        inputDeskripsi.value = deskripsi;
        inputDurasi.value = parseInt(durasi);
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

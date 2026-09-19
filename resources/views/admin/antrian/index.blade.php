@extends('layouts.admin')

@section('title', 'Sales Report & Dashboard Antrian')
@section('page-title', 'Sales Report')

@section('content')
<div class="space-y-6">

    {{-- ── 4 Top Stat Cards (DealDeck Style) ── --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        {{-- Card 1: Vibrant Royal Blue Primary Card --}}
        <div class="bg-gradient-to-br from-blue-600 to-brand text-white rounded-3xl p-6 shadow-xl shadow-brand/20 relative overflow-hidden flex flex-col justify-between">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center text-white">
                    <i class="fa-solid fa-receipt text-base"></i>
                </div>
                <span class="px-2.5 py-1 rounded-full bg-emerald-400 text-slate-900 text-[11px] font-extrabold flex items-center gap-1 shadow-sm">
                    <i class="fa-solid fa-arrow-trend-up text-[10px]"></i> +12.5%
                </span>
            </div>
            <div>
                <p class="text-xs font-semibold text-blue-100 uppercase tracking-wider">Total Sales / Antrian</p>
                <h3 class="text-3xl font-extrabold tracking-tight mt-1">{{ $stats['total'] }} <span class="text-lg font-bold text-blue-100">Unit</span></h3>
                <p class="text-[11px] text-blue-100/80 mt-1 font-medium">Antrian vs kemarin</p>
            </div>
        </div>

        {{-- Card 2: Menunggu (White Card) --}}
        <div class="deal-card p-6 flex flex-col justify-between">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center">
                    <i class="fa-regular fa-clock text-base"></i>
                </div>
                <span class="px-2.5 py-1 rounded-full bg-red-500 text-white text-[11px] font-extrabold flex items-center gap-1 shadow-sm">
                    <i class="fa-solid fa-arrow-trend-down text-[10px]"></i> -2.08%
                </span>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Visitor / Menunggu</p>
                <h3 class="text-3xl font-extrabold text-slate-900 tracking-tight mt-1">{{ $stats['menunggu'] }}</h3>
                <p class="text-[11px] text-slate-400 mt-1 font-medium">Pelanggan di ruang tunggu</p>
            </div>
        </div>

        {{-- Card 3: Diproses (White Card) --}}
        <div class="deal-card p-6 flex flex-col justify-between">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 rounded-2xl bg-blue-50 text-brand flex items-center justify-center">
                    <i class="fa-solid fa-soap text-base"></i>
                </div>
                <span class="px-2.5 py-1 rounded-full bg-emerald-500 text-white text-[11px] font-extrabold flex items-center gap-1 shadow-sm">
                    <i class="fa-solid fa-arrow-trend-up text-[10px]"></i> +12.4%
                </span>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Orders / Diproses</p>
                <h3 class="text-3xl font-extrabold text-slate-900 tracking-tight mt-1">{{ $stats['diproses'] }}</h3>
                <p class="text-[11px] text-slate-400 mt-1 font-medium">Dalam pengerjaan cuci</p>
            </div>
        </div>

        {{-- Card 4: Selesai (White Card) --}}
        <div class="deal-card p-6 flex flex-col justify-between">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <i class="fa-solid fa-circle-check text-base"></i>
                </div>
                <span class="px-2.5 py-1 rounded-full bg-emerald-500 text-white text-[11px] font-extrabold flex items-center gap-1 shadow-sm">
                    <i class="fa-solid fa-arrow-trend-up text-[10px]"></i> +12.1%
                </span>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Sold / Selesai</p>
                <h3 class="text-3xl font-extrabold text-slate-900 tracking-tight mt-1">{{ $stats['selesai'] }}</h3>
                <p class="text-[11px] text-slate-400 mt-1 font-medium">Selesai dikerjakan hari ini</p>
            </div>
        </div>
    </div>

    {{-- ── Middle Analytics Row (DealDeck Bar Chart & Product Statistics Rings) ── --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        {{-- Left 8 Cols: Customer Habbits / Bar Chart --}}
        <div class="lg:col-span-8 deal-card p-6 flex flex-col justify-between">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-base font-extrabold text-slate-900 tracking-tight">Customer Habbits</h3>
                    <p class="text-xs text-slate-400 font-medium">Track your customer antrian & volume pendaftaran</p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-4 text-xs font-semibold">
                        <span class="flex items-center gap-1.5 text-slate-400">
                            <span class="w-2.5 h-2.5 rounded-full bg-slate-200"></span> Seen antrian
                        </span>
                        <span class="flex items-center gap-1.5 text-slate-700">
                            <span class="w-2.5 h-2.5 rounded-full bg-brand"></span> Selesai
                        </span>
                    </div>
                    <select class="px-3 py-1.5 bg-slate-100 border border-slate-200/60 rounded-xl text-xs font-bold text-slate-700 focus:outline-none">
                        <option>This year</option>
                        <option>This month</option>
                    </select>
                </div>
            </div>

            {{-- Bar Chart Canvas --}}
            <div class="h-64 w-full relative">
                <canvas id="customerChart"></canvas>
            </div>
        </div>

        {{-- Right 4 Cols: Product Statistic Radial Ring Chart --}}
        <div class="lg:col-span-4 deal-card p-6 flex flex-col justify-between">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <h3 class="text-base font-extrabold text-slate-900 tracking-tight">Product Statistic</h3>
                    <p class="text-xs text-slate-400 font-medium">Track your product & vehicle sales</p>
                </div>
                <select class="px-3 py-1.5 bg-slate-100 border border-slate-200/60 rounded-xl text-xs font-bold text-slate-700 focus:outline-none">
                    <option>Today</option>
                </select>
            </div>

            {{-- Doughnut Canvas & Big Metric --}}
            <div class="relative py-2 flex items-center justify-center">
                <div class="w-44 h-44 relative">
                    <canvas id="productRadialChart"></canvas>
                    <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                        <span class="text-2xl font-extrabold text-slate-900 leading-none">{{ $stats['total'] }}</span>
                        <span class="text-[10px] font-bold text-slate-400 mt-1">Vehicle Sales</span>
                        <span class="px-2 py-0.5 mt-1 rounded-full bg-emerald-100 text-emerald-700 text-[10px] font-black">+5.34%</span>
                    </div>
                </div>
            </div>

            {{-- Category Breakdown List --}}
            <div class="space-y-2.5 pt-2 border-t border-slate-100">
                <div class="flex items-center justify-between text-xs">
                    <span class="flex items-center gap-2 font-bold text-slate-700">
                        <i class="fa-solid fa-motorcycle text-brand"></i> Cuci Motor
                    </span>
                    <div class="flex items-center gap-2 font-extrabold text-slate-900">
                        <span>18 unit</span>
                        <span class="px-1.5 py-0.5 rounded bg-emerald-100 text-emerald-700 text-[10px]">+1.8%</span>
                    </div>
                </div>

                <div class="flex items-center justify-between text-xs">
                    <span class="flex items-center gap-2 font-bold text-slate-700">
                        <i class="fa-solid fa-car text-indigo-600"></i> Cuci Mobil
                    </span>
                    <div class="flex items-center gap-2 font-extrabold text-slate-900">
                        <span>12 unit</span>
                        <span class="px-1.5 py-0.5 rounded bg-emerald-100 text-emerald-700 text-[10px]">+2.3%</span>
                    </div>
                </div>

                <div class="flex items-center justify-between text-xs">
                    <span class="flex items-center gap-2 font-bold text-slate-700">
                        <i class="fa-solid fa-spray-can-sparkles text-red-500"></i> Detailing Wax
                    </span>
                    <div class="flex items-center gap-2 font-extrabold text-slate-900">
                        <span>5 unit</span>
                        <span class="px-1.5 py-0.5 rounded bg-red-100 text-red-700 text-[10px]">-1.04%</span>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- ── Live Antrian Table Section (DealDeck Style) ── --}}
    <div class="deal-card overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-base font-extrabold text-slate-900 tracking-tight">Antrian Hari Ini</h3>
                <p class="text-xs text-slate-400 font-medium">Status live pengerjaan cuci unit kendaraan pelanggan</p>
            </div>
            <div class="flex items-center gap-2 px-3 py-1.5 bg-emerald-50 border border-emerald-100 rounded-xl">
                 <span class="relative flex h-2 w-2">
                     <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                     <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                 </span>
                 <span class="text-xs font-bold text-emerald-700">Live Sync</span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-400 text-[11px] font-bold uppercase tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4">No Antrian</th>
                        <th class="px-6 py-4">Pelanggan</th>
                        <th class="px-6 py-4">No Plat & Tipe</th>
                        <th class="px-6 py-4">Paket Layanan</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Aksi Management</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse(['menunggu', 'diproses', 'selesai', 'batal'] as $status)
                        @if(isset($antrians[$status]))
                            @foreach($antrians[$status] as $a)
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <td class="px-6 py-4 font-extrabold text-brand text-lg">
                                    {{ $a->nomor_antrian }}
                                </td>
                                <td class="px-6 py-4">
                                    <p class="font-bold text-slate-900 text-sm leading-tight">{{ $a->nama_pelanggan }}</p>
                                    <p class="text-xs text-slate-400 font-medium">{{ $a->user->email ?? 'Guest' }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="font-mono px-2.5 py-0.5 bg-slate-100 text-slate-800 font-extrabold rounded-md text-xs uppercase">{{ $a->no_plat }}</span>
                                    <span class="text-xs text-slate-400 block capitalize mt-0.5">{{ $a->jenis_kendaraan }}</span>
                                </td>
                                <td class="px-6 py-4 font-semibold text-slate-800">
                                    {{ $a->jenisLayanan->nama_layanan ?? '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($a->status === 'selesai')
                                        <span class="px-3 py-1 bg-emerald-50 text-emerald-700 border border-emerald-100 rounded-full text-xs font-bold">Selesai</span>
                                    @elseif($a->status === 'diproses')
                                        <span class="px-3 py-1 bg-blue-50 text-brand border border-blue-100 rounded-full text-xs font-bold">Diproses</span>
                                    @elseif($a->status === 'menunggu')
                                        <span class="px-3 py-1 bg-amber-50 text-amber-700 border border-amber-100 rounded-full text-xs font-bold">Menunggu</span>
                                    @else
                                        <span class="px-3 py-1 bg-slate-100 text-slate-500 rounded-full text-xs font-bold">Batal</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        @if($a->status == 'menunggu')
                                            <form action="{{ route('admin.antrian.proses', $a) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <button type="submit" class="px-3 py-1.5 bg-brand hover:bg-brand-hover text-white text-xs font-bold rounded-xl shadow-sm transition-all inline-flex items-center gap-1">
                                                    <i class="fa-solid fa-play text-[10px]"></i> Proses
                                                </button>
                                            </form>
                                        @endif

                                        @if($a->status == 'diproses')
                                            <form action="{{ route('admin.antrian.selesai', $a) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <button type="submit" class="px-3 py-1.5 bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-bold rounded-xl shadow-sm transition-all inline-flex items-center gap-1">
                                                    <i class="fa-solid fa-check text-[10px]"></i> Selesai
                                                </button>
                                            </form>
                                        @endif

                                        @if(in_array($a->status, ['menunggu', 'diproses']))
                                            <form action="{{ route('admin.antrian.destroy', $a) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan antrian ini?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="p-1.5 bg-slate-100 hover:bg-red-50 text-slate-400 hover:text-red-500 rounded-xl transition-all" title="Batalkan">
                                                    <i class="fa-solid fa-trash-can text-xs"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        @endif
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-400 font-medium">
                                Belum ada antrian terdaftar hari ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function() {
    // 1. Customer Habbits Bar Chart
    const ctxBar = document.getElementById('customerChart').getContext('2d');
    new Chart(ctxBar, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
            datasets: [
                {
                    label: 'Seen antrian',
                    data: [12, 43, 20, 43, 12, 28, 20],
                    backgroundColor: '#e2e8f0',
                    borderRadius: 8,
                    barThickness: 16,
                },
                {
                    label: 'Selesai',
                    data: [25, 30, 39, 39, 24, 30, 25],
                    backgroundColor: '#3B82F6',
                    borderRadius: 8,
                    barThickness: 16,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                x: { grid: { display: false }, ticks: { font: { size: 11, weight: '600' }, color: '#94a3b8' } },
                y: { grid: { color: '#f1f5f9' }, ticks: { font: { size: 10 }, color: '#94a3b8' } }
            }
        }
    });

    // 2. Product Radial Concentric Ring Doughnut Chart
    const ctxRadial = document.getElementById('productRadialChart').getContext('2d');
    new Chart(ctxRadial, {
        type: 'doughnut',
        data: {
            labels: ['Motor', 'Mobil', 'Detailing'],
            datasets: [{
                data: [50, 35, 15],
                backgroundColor: ['#3B82F6', '#ef4444', '#cbd5e1'],
                borderWidth: 0,
                cutout: '78%',
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } }
        }
    });
});
</script>
@endpush

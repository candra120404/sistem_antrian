<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Antrian;
use App\Models\Transaksi;
use Illuminate\Http\Request;

/**
 * DataHarianController (Admin) — menampilkan laporan antrian dan pendapatan harian.
 */
class DataHarianController extends Controller
{
    /** Tampilkan data harian dengan filter tanggal. */
    public function index(Request $request)
    {
        // Default ke hari ini jika tidak ada filter
        $tanggal = $request->input('tanggal', today()->toDateString());

        $antrians = Antrian::with(['jenisLayanan', 'transaksi'])
            ->whereDate('created_at', $tanggal)
            ->orderBy('created_at')
            ->get();

        // Hitung statistik untuk tanggal yang dipilih
        $stats = [
            'total_kendaraan'  => $antrians->count(),
            'total_pendapatan' => $antrians->sum(fn ($a) => $a->transaksi?->total_bayar ?? 0),
            'total_motor'      => $antrians->where('jenis_kendaraan', 'motor')->count(),
            'total_mobil'      => $antrians->where('jenis_kendaraan', 'mobil')->count(),
            'selesai'          => $antrians->where('status', 'selesai')->count(),
        ];

        return view('admin.data-harian.index', compact('antrians', 'stats', 'tanggal'));
    }
}

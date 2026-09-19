<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Antrian;
use App\Models\JenisLayanan;
use Illuminate\Http\Request;

/**
 * LaporanController (Admin) — mengelola laporan keseluruhan transaksi dan antrian.
 */
class LaporanController extends Controller
{
    /** Tampilkan halaman laporan keseluruhan dengan filter rentang tanggal. */
    public function index(Request $request)
    {
        $dariTanggal   = $request->input('dari_tanggal', now()->startOfMonth()->toDateString());
        $sampaiTanggal = $request->input('sampai_tanggal', today()->toDateString());
        $jenisKendaraan= $request->input('jenis_kendaraan', 'semua');
        $statusFilter  = $request->input('status', 'semua');

        $query = Antrian::with(['user', 'jenisLayanan', 'transaksi'])
            ->whereDate('created_at', '>=', $dariTanggal)
            ->whereDate('created_at', '<=', $sampaiTanggal);

        if ($jenisKendaraan !== 'semua') {
            $query->where('jenis_kendaraan', $jenisKendaraan);
        }

        if ($statusFilter !== 'semua') {
            $query->where('status', $statusFilter);
        }

        $antrians = $query->orderBy('created_at', 'desc')->get();

        // Hitung Ringkasan Statistik
        $stats = [
            'dari_tanggal'     => $dariTanggal,
            'sampai_tanggal'   => $sampaiTanggal,
            'total_pelanggan'  => $antrians->count(),
            'total_selesai'    => $antrians->where('status', 'selesai')->count(),
            'total_batal'      => $antrians->where('status', 'batal')->count(),
            'total_pendapatan' => $antrians->sum(fn ($a) => $a->transaksi?->total_bayar ?? 0),
            'total_motor'      => $antrians->where('jenis_kendaraan', 'motor')->count(),
            'total_mobil'      => $antrians->where('jenis_kendaraan', 'mobil')->count(),
        ];

        // Breakdown per Jenis Layanan
        $jenisLayanans = JenisLayanan::all();
        $breakdownLayanan = $jenisLayanans->map(function ($layanan) use ($antrians) {
            $antrianLayanan = $antrians->where('jenis_layanan_id', $layanan->id);
            return [
                'nama_layanan'   => $layanan->nama_layanan,
                'jenis_cuci'     => $layanan->jenis_cuci ?? '-',
                'jenis_kendaraan'=> $layanan->jenis_kendaraan,
                'jumlah'         => $antrianLayanan->count(),
                'total_omset'    => $antrianLayanan->sum(fn ($a) => $a->transaksi?->total_bayar ?? 0),
            ];
        });

        return view('admin.laporan.index', compact('antrians', 'stats', 'breakdownLayanan', 'dariTanggal', 'sampaiTanggal', 'jenisKendaraan', 'statusFilter'));
    }
}

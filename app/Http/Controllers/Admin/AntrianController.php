<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Antrian;
use App\Services\AntrianService;
use Illuminate\Http\Request;

/**
 * AntrianController (Admin) — mengelola antrian di panel admin.
 * Mendukung mode AJAX untuk auto-refresh real-time.
 */
class AntrianController extends Controller
{
    public function __construct(private AntrianService $antrianService) {}

    /**
     * Tampilkan daftar antrian hari ini beserta statistik.
     * Mendukung request AJAX untuk auto-refresh tabel.
     */
    public function index(Request $request)
    {
        $antrians = Antrian::with(['user', 'jenisLayanan'])
            ->hariIni()
            ->orderBy('created_at')
            ->get()
            ->groupBy('status');

        $stats = $this->antrianService->statistikHariIni();

        // Kembalikan JSON jika request AJAX
        if ($request->ajax()) {
            return response()->json([
                'antrians' => $antrians,
                'stats'    => $stats,
            ]);
        }

        return view('admin.antrian.index', compact('antrians', 'stats'));
    }

    /**
     * Ubah status antrian dari "menunggu" menjadi "diproses".
     */
    public function proses(Antrian $antrian)
    {
        try {
            $this->antrianService->prosesAntrian($antrian);

            return back()->with('success', "Antrian {$antrian->nomor_antrian} sedang diproses.");
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses antrian: ' . $e->getMessage());
        }
    }

    /**
     * Tandai antrian sebagai selesai dan catat transaksi pembayaran.
     */
    public function selesai(Antrian $antrian)
    {
        try {
            $this->antrianService->selesaikanAntrian($antrian);

            return back()->with('success', "Antrian {$antrian->nomor_antrian} telah selesai. Transaksi tercatat.");
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menyelesaikan antrian: ' . $e->getMessage());
        }
    }

    /**
     * Batalkan antrian dan re-kalkulasi posisi antrian lain.
     */
    public function destroy(Antrian $antrian)
    {
        try {
            $this->antrianService->batalkanAntrian($antrian);

            return back()->with('success', "Antrian {$antrian->nomor_antrian} telah dibatalkan.");
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membatalkan antrian: ' . $e->getMessage());
        }
    }
}

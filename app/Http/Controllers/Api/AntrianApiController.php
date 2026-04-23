<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AntrianRequest;
use App\Models\Antrian;
use App\Models\JenisLayanan;
use App\Models\Transaksi;
use App\Services\AntrianService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * AntrianApiController — API endpoint untuk aplikasi mobile (Flutter).
 * Semua response menggunakan format standar: {status, message, data}.
 */
class AntrianApiController extends Controller
{
    public function __construct(private AntrianService $antrianService) {}

    /**
     * GET /api/antrian — ambil semua antrian hari ini (admin only).
     */
    public function index(): JsonResponse
    {
        try {
            if (! request()->user()->isAdmin()) {
                return $this->forbidden();
            }

            $antrians = Antrian::with(['user', 'jenisLayanan'])
                ->hariIni()
                ->orderBy('created_at')
                ->get();

            return $this->success('Berhasil.', $antrians);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    /**
     * POST /api/antrian — buat antrian baru (pelanggan).
     */
    public function store(AntrianRequest $request): JsonResponse
    {
        try {
            $layanan = JenisLayanan::findOrFail($request->jenis_layanan_id);
            $antrian = $this->antrianService->buatAntrian($request->user(), $layanan, $request->no_plat);

            return $this->success('Berhasil mendaftar antrian.', $antrian->load('jenisLayanan'), 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    /**
     * GET /api/antrian/saya — antrian milik user yang sedang login.
     */
    public function milikSaya(Request $request): JsonResponse
    {
        $antrians = Antrian::with('jenisLayanan')
            ->where('user_id', $request->user()->id)
            ->hariIni()
            ->orderBy('created_at', 'desc')
            ->get();

        return $this->success('Berhasil.', $antrians);
    }

    /**
     * GET /api/antrian/{id}/posisi — cek posisi & estimasi waktu tunggu.
     */
    public function posisi(int $id): JsonResponse
    {
        try {
            $antrian = Antrian::with('jenisLayanan')->findOrFail($id);
            $posisi  = Antrian::hitungPosisi($antrian->id);

            return $this->success('Berhasil.', [
                'nomor_antrian'       => $antrian->nomor_antrian,
                'status'              => $antrian->status,
                'posisi'              => $posisi,
                'estimasi_menit'      => $posisi * 15, // asumsi 15 menit per kendaraan
                'total_menunggu'      => Antrian::hariIni()->menunggu()->count(),
            ]);
        } catch (\Exception $e) {
            return $this->error('Antrian tidak ditemukan.', 404);
        }
    }

    /**
     * PATCH /api/antrian/{id}/selesai — tandai antrian selesai (admin only).
     */
    public function selesai(int $id): JsonResponse
    {
        try {
            if (! request()->user()->isAdmin()) {
                return $this->forbidden();
            }

            $antrian = Antrian::findOrFail($id);
            $antrian = $this->antrianService->selesaikanAntrian($antrian);

            return $this->success('Antrian berhasil diselesaikan.', $antrian);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    /**
     * DELETE /api/antrian/{id} — batalkan antrian.
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $antrian = Antrian::findOrFail($id);
            $this->antrianService->batalkanAntrian($antrian);

            return $this->success('Antrian berhasil dibatalkan.');
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    /**
     * GET /api/harga — daftar semua jenis layanan aktif.
     */
    public function harga(): JsonResponse
    {
        $layanans = JenisLayanan::aktif()->orderBy('jenis_kendaraan')->get();

        return $this->success('Berhasil.', $layanans);
    }

    /**
     * PUT /api/harga/{id} — update harga layanan (admin only).
     */
    public function updateHarga(Request $request, int $id): JsonResponse
    {
        try {
            if (! $request->user()->isAdmin()) {
                return $this->forbidden();
            }

            $request->validate(['harga' => 'required|numeric|min:1000']);

            $layanan = JenisLayanan::findOrFail($id);
            $layanan->update(['harga' => $request->harga]);

            return $this->success('Harga berhasil diperbarui.', $layanan);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    /**
     * GET /api/data-harian — statistik harian (admin only).
     */
    public function dataHarian(Request $request): JsonResponse
    {
        try {
            if (! $request->user()->isAdmin()) {
                return $this->forbidden();
            }

            $tanggal  = $request->input('tanggal', today()->toDateString());
            $antrians = Antrian::with(['jenisLayanan', 'transaksi'])
                ->whereDate('created_at', $tanggal)
                ->get();

            $stats = [
                'tanggal'          => $tanggal,
                'total_kendaraan'  => $antrians->count(),
                'total_pendapatan' => $antrians->sum(fn ($a) => $a->transaksi?->total_bayar ?? 0),
                'total_motor'      => $antrians->where('jenis_kendaraan', 'motor')->count(),
                'total_mobil'      => $antrians->where('jenis_kendaraan', 'mobil')->count(),
            ];

            return $this->success('Berhasil.', [
                'stats'    => $stats,
                'antrians' => $antrians,
            ]);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    // ─── Helper Response ──────────────────────────────────────────────────────

    private function success(string $message, mixed $data = null, int $code = 200): JsonResponse
    {
        return response()->json(['status' => true, 'message' => $message, 'data' => $data], $code);
    }

    private function error(string $message, int $code = 500): JsonResponse
    {
        return response()->json(['status' => false, 'message' => $message], $code);
    }

    private function forbidden(): JsonResponse
    {
        return response()->json(['status' => false, 'message' => 'Akses ditolak.'], 403);
    }
}

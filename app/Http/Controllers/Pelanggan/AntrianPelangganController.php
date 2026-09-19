<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Http\Requests\AntrianRequest;
use App\Models\Antrian;
use App\Models\JenisLayanan;
use App\Services\AntrianService;
use Illuminate\Http\Request;

/**
 * AntrianPelangganController — mengelola antrian dari sisi pelanggan.
 */
class AntrianPelangganController extends Controller
{
    public function __construct(private AntrianService $antrianService) {}

    /**
     * Tampilkan dashboard pelanggan.
     */
    public function dashboard()
    {
        $antrianAktif = Antrian::with('jenisLayanan')
            ->where('user_id', auth()->id())
            ->aktif()
            ->latest('id')
            ->first();

        $riwayatAntrians = Antrian::with('jenisLayanan')
            ->where('user_id', auth()->id())
            ->latest('id')
            ->take(5)
            ->get();

        return view('pelanggan.dashboard', compact('antrianAktif', 'riwayatAntrians'));
    }

    /**
     * Tampilkan form buat antrian baru (Hari Ini / Besok).
     */
    public function create(Request $request)
    {
        $jenisKendaraan = $request->input('jenis', null);
        $pilihanTanggal = $request->input('tanggal', 'hari_ini'); // 'hari_ini' atau 'besok'

        $targetDate = $pilihanTanggal === 'besok' ? today()->addDay()->toDateString() : today()->toDateString();

        $layanans = JenisLayanan::aktif()
            ->when($jenisKendaraan, fn ($q) => $q->where('jenis_kendaraan', $jenisKendaraan))
            ->orderBy('harga')
            ->get();

        return view('pelanggan.antrian.create', compact('layanans', 'jenisKendaraan', 'pilihanTanggal', 'targetDate'));
    }

    /**
     * Simpan antrian baru ke database.
     */
    public function store(AntrianRequest $request)
    {
        try {
            $pilihanTanggal = $request->input('pilihan_tanggal', 'hari_ini');
            $tanggalBooking = $pilihanTanggal === 'besok' 
                ? today()->addDay()->toDateString() 
                : today()->toDateString();

            $layanan = JenisLayanan::findOrFail($request->jenis_layanan_id);
            $antrian = $this->antrianService->buatAntrian(auth()->user(), $layanan, $request->no_plat, $tanggalBooking);

            $labelTanggal = $pilihanTanggal === 'besok' ? 'besok' : 'hari ini';
            return redirect()->route('pelanggan.antrian.status')
                ->with('success', "Anda berhasil mendaftar antrian untuk {$labelTanggal}! Nomor antrian: {$antrian->nomor_antrian}");
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal mendaftar antrian: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan halaman status antrian real-time milik pelanggan.
     */
    public function status()
    {
        $antrian = Antrian::with('jenisLayanan')
            ->where('user_id', auth()->id())
            ->aktif()
            ->latest('id')
            ->first();

        $posisi = $antrian ? Antrian::hitungPosisi($antrian->id) : 0;
        $totalMenunggu = $antrian ? Antrian::whereDate('tanggal_booking', $antrian->tanggal_booking)->menunggu()->count() : 0;

        return view('pelanggan.antrian.status', compact('antrian', 'posisi', 'totalMenunggu'));
    }

    /**
     * Batalkan antrian milik pelanggan.
     */
    public function batalkan(Antrian $antrian)
    {
        if ($antrian->user_id !== auth()->id()) {
            return back()->with('error', 'Akses ditolak.');
        }

        try {
            $this->antrianService->batalkanAntrian($antrian);
            return redirect()->route('pelanggan.dashboard')->with('success', 'Antrian Anda berhasil dibatalkan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membatalkan antrian: ' . $e->getMessage());
        }
    }
}

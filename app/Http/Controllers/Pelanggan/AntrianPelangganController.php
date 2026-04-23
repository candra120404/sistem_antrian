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
     * Cek apakah pelanggan sudah memiliki antrian aktif hari ini.
     */
    public function dashboard()
    {
        $antrianAktif = Antrian::where('user_id', auth()->id())
            ->hariIni()
            ->aktif()
            ->latest()
            ->first();

        return view('pelanggan.dashboard', compact('antrianAktif'));
    }

    /**
     * Tampilkan form buat antrian baru.
     * Bisa pre-filter berdasarkan jenis kendaraan dari query string.
     */
    public function create(Request $request)
    {
        // Cegah mendaftar dua kali di hari yang sama
        $sudahAntri = Antrian::where('user_id', auth()->id())
            ->hariIni()
            ->aktif()
            ->exists();

        if ($sudahAntri) {
            return redirect()->route('pelanggan.antrian.status')
                ->with('info', 'Anda masih memiliki antrian aktif hari ini.');
        }

        $jenisKendaraan = $request->input('jenis', null);

        $layanans = JenisLayanan::aktif()
            ->when($jenisKendaraan, fn ($q) => $q->where('jenis_kendaraan', $jenisKendaraan))
            ->orderBy('harga')
            ->get();

        return view('pelanggan.antrian.create', compact('layanans', 'jenisKendaraan'));
    }

    /**
     * Simpan antrian baru ke database.
     */
    public function store(AntrianRequest $request)
    {
        try {
            $layanan = JenisLayanan::findOrFail($request->jenis_layanan_id);
            $antrian = $this->antrianService->buatAntrian(auth()->user(), $layanan, $request->no_plat);

            return redirect()->route('pelanggan.antrian.status')
                ->with('success', "Anda berhasil mendaftar! Nomor antrian Anda: {$antrian->nomor_antrian}");
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
            ->hariIni()
            ->aktif()
            ->latest()
            ->first();

        $totalMenunggu = Antrian::hariIni()->menunggu()->count();

        return view('pelanggan.antrian.status', compact('antrian', 'totalMenunggu'));
    }
}

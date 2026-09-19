<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JenisLayanan;
use Illuminate\Http\Request;

/**
 * HargaController (Admin) — mengelola harga layanan bengkel.
 */
class HargaController extends Controller
{
    /** Tampilkan daftar semua jenis layanan beserta harga. */
    public function index()
    {
        $layanans = JenisLayanan::orderBy('jenis_kendaraan')->orderBy('nama_layanan')->get();

        return view('admin.harga.index', compact('layanans'));
    }

    /** Update harga dan detail layanan berdasarkan ID. */
    public function update(Request $request, JenisLayanan $jenisLayanan)
    {
        $request->validate([
            'harga'            => 'required|numeric|min:1000',
            'jenis_cuci'       => 'nullable|string|max:100',
            'deskripsi'        => 'nullable|string',
            'est_durasi_menit' => 'required|integer|min:5',
            'is_active'        => 'boolean',
        ], [
            'harga.required' => 'Harga wajib diisi.',
            'harga.min'      => 'Harga minimal Rp 1.000.',
            'est_durasi_menit.required' => 'Estimasi durasi wajib diisi.',
        ]);

        try {
            $jenisLayanan->update([
                'harga'            => $request->harga,
                'jenis_cuci'       => $request->jenis_cuci,
                'deskripsi'        => $request->deskripsi,
                'est_durasi_menit' => $request->est_durasi_menit,
                'is_active'        => $request->boolean('is_active', true),
            ]);

            return back()->with('success', "Detail & harga layanan {$jenisLayanan->nama_layanan} berhasil diperbarui.");
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui harga: ' . $e->getMessage());
        }
    }
}

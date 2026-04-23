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

    /** Update harga layanan berdasarkan ID. */
    public function update(Request $request, JenisLayanan $jenisLayanan)
    {
        $request->validate([
            'harga'     => 'required|numeric|min:1000',
            'is_active' => 'boolean',
        ], [
            'harga.required' => 'Harga wajib diisi.',
            'harga.min'      => 'Harga minimal Rp 1.000.',
        ]);

        try {
            $jenisLayanan->update([
                'harga'     => $request->harga,
                'is_active' => $request->boolean('is_active', true),
            ]);

            return back()->with('success', "Harga {$jenisLayanan->nama_layanan} berhasil diperbarui.");
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui harga: ' . $e->getMessage());
        }
    }
}

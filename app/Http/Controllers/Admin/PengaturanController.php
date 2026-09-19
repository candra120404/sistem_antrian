<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengaturan;
use Illuminate\Http\Request;

/**
 * PengaturanController (Admin) — mengelola batas harian dan minimum/maksimum booking.
 */
class PengaturanController extends Controller
{
    /** Tampilkan form pengaturan. */
    public function index()
    {
        $pengaturan = Pengaturan::getAktif();

        return view('admin.pengaturan.index', compact('pengaturan'));
    }

    /** Simpan perubahan pengaturan. */
    public function update(Request $request)
    {
        $request->validate([
            'batas_maksimal_pelanggan_harian' => 'required|integer|min:1',
            'min_booking_per_user'          => 'required|integer|min:1',
            'max_booking_per_user'          => 'required|integer|min:1|gte:min_booking_per_user',
        ], [
            'batas_maksimal_pelanggan_harian.required' => 'Batas maksimal harian wajib diisi.',
            'batas_maksimal_pelanggan_harian.min'      => 'Batas maksimal harian minimal 1 pelanggan.',
            'min_booking_per_user.required'          => 'Minimum booking per user wajib diisi.',
            'max_booking_per_user.required'          => 'Maksimum booking per user wajib diisi.',
            'max_booking_per_user.gte'               => 'Maksimum booking harus lebih besar atau sama dengan minimum booking.',
        ]);

        try {
            $pengaturan = Pengaturan::getAktif();
            $pengaturan->update([
                'batas_maksimal_pelanggan_harian' => $request->batas_maksimal_pelanggan_harian,
                'min_booking_per_user'          => $request->min_booking_per_user,
                'max_booking_per_user'          => $request->max_booking_per_user,
            ]);

            return back()->with('success', 'Pengaturan kuota & booking berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui pengaturan: ' . $e->getMessage());
        }
    }
}

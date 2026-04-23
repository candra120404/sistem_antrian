<?php

namespace App\Services;

use App\Models\Antrian;
use App\Models\JenisLayanan;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Support\Facades\DB;

/**
 * AntrianService — service layer untuk logika bisnis antrian.
 * Memisahkan business logic dari controller agar lebih bersih dan testable.
 */
class AntrianService
{
    /**
     * Buat antrian baru untuk pelanggan.
     * Secara otomatis menentukan nomor antrian dan posisi.
     */
    public function buatAntrian(User $user, JenisLayanan $layanan, string $noPlat): Antrian
    {
        return DB::transaction(function () use ($user, $layanan, $noPlat) {
            $posisi = Antrian::where('status', 'menunggu')->count() + 1;

            return Antrian::create([
                'user_id'          => $user->id,
                'jenis_layanan_id' => $layanan->id,
                'nomor_antrian'    => Antrian::generateNomor(),
                'nama_pelanggan'   => $user->name,
                'no_plat'          => strtoupper($noPlat),
                'jenis_kendaraan'  => $layanan->jenis_kendaraan,
                'status'           => 'menunggu',
                'posisi_antrian'   => $posisi,
            ]);
        });
    }

    /**
     * Ubah status antrian menjadi "diproses".
     */
    public function prosesAntrian(Antrian $antrian): Antrian
    {
        $antrian->update(['status' => 'diproses']);

        return $antrian;
    }

    /**
     * Tandai antrian sebagai selesai dan buat transaksi pembayaran otomatis.
     */
    public function selesaikanAntrian(Antrian $antrian): Antrian
    {
        return DB::transaction(function () use ($antrian) {
            $antrian->update([
                'status'     => 'selesai',
                'selesai_at' => now(),
            ]);

            // Buat transaksi otomatis berdasarkan harga layanan
            Transaksi::create([
                'antrian_id'  => $antrian->id,
                'total_bayar' => $antrian->jenisLayanan->harga,
                'status_bayar' => 'lunas',
                'tanggal'     => today(),
            ]);

            // Re-kalkulasi posisi antria yang masih menunggu
            Antrian::recalculatePosisi();

            return $antrian->fresh();
        });
    }

    /**
     * Batalkan antrian dan re-kalkulasi posisi antrian lain.
     */
    public function batalkanAntrian(Antrian $antrian): void
    {
        DB::transaction(function () use ($antrian) {
            $antrian->update(['status' => 'batal']);
            Antrian::recalculatePosisi();
        });
    }

    /**
     * Ambil statistik antrian untuk hari ini.
     */
    public function statistikHariIni(): array
    {
        $antrians = Antrian::hariIni()->get();

        return [
            'total'    => $antrians->count(),
            'menunggu' => $antrians->where('status', 'menunggu')->count(),
            'diproses' => $antrians->where('status', 'diproses')->count(),
            'selesai'  => $antrians->where('status', 'selesai')->count(),
            'batal'    => $antrians->where('status', 'batal')->count(),
        ];
    }
}

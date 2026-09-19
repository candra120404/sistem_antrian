<?php

namespace App\Services;

use App\Models\Antrian;
use App\Models\JenisLayanan;
use App\Models\Pengaturan;
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
     * Buat antrian baru untuk pelanggan (Hari Ini atau Besok).
     */
    public function buatAntrian(User $user, JenisLayanan $layanan, string $noPlat, ?string $tanggalBooking = null): Antrian
    {
        $targetDate = $tanggalBooking ?? today()->toDateString();
        $pengaturan = Pengaturan::getAktif();

        // 1. Cek Batas Maksimal Pelanggan Harian Total untuk Tanggal Booking
        $totalAntrianTanggal = Antrian::whereDate('tanggal_booking', $targetDate)->count();
        if ($totalAntrianTanggal >= $pengaturan->batas_maksimal_pelanggan_harian) {
            $formattedDate = \Carbon\Carbon::parse($targetDate)->translatedFormat('d F Y');
            throw new \Exception("Batas kuota pendaftaran antrian untuk tanggal {$formattedDate} telah penuh (Maksimal {$pengaturan->batas_maksimal_pelanggan_harian} pelanggan per hari).");
        }

        // 2. Cek Batas Maksimum Booking per User pada Tanggal Booking
        $userActiveBookings = Antrian::where('user_id', $user->id)
            ->whereDate('tanggal_booking', $targetDate)
            ->aktif()
            ->count();

        if ($userActiveBookings >= $pengaturan->max_booking_per_user) {
            throw new \Exception("Anda telah mencapai batas maksimum booking antrian aktif untuk tanggal ini (Maksimal {$pengaturan->max_booking_per_user} antrian per pelanggan).");
        }

        return DB::transaction(function () use ($user, $layanan, $noPlat, $targetDate) {
            $posisi = Antrian::whereDate('tanggal_booking', $targetDate)
                ->where('status', 'menunggu')
                ->count() + 1;

            return Antrian::create([
                'user_id'          => $user->id,
                'jenis_layanan_id' => $layanan->id,
                'tanggal_booking'  => $targetDate,
                'nomor_antrian'    => Antrian::generateNomor($targetDate),
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

            // Re-kalkulasi posisi antrian yang masih menunggu
            Antrian::recalculatePosisi($antrian->tanggal_booking?->toDateString());

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
            Antrian::recalculatePosisi($antrian->tanggal_booking?->toDateString());
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

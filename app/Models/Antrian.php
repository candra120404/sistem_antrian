<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Model Antrian — merepresentasikan satu antrian kendaraan.
 * Berisi logika bisnis: generate nomor otomatis & hitung posisi berdasarkan tanggal_booking.
 */
class Antrian extends Model
{
    protected $fillable = [
        'user_id',
        'jenis_layanan_id',
        'tanggal_booking',
        'nomor_antrian',
        'nama_pelanggan',
        'no_plat',
        'jenis_kendaraan',
        'status',
        'posisi_antrian',
        'selesai_at',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_booking' => 'date',
            'selesai_at'      => 'datetime',
        ];
    }

    // ─── Relasi ────────────────────────────────────────────────────────────────

    /** Antrian dimiliki oleh satu user/pelanggan. */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /** Antrian mengacu pada satu jenis layanan. */
    public function jenisLayanan()
    {
        return $this->belongsTo(JenisLayanan::class);
    }

    /** Antrian yang selesai memiliki satu transaksi. */
    public function transaksi()
    {
        return $this->hasOne(Transaksi::class);
    }

    // ─── Scope ────────────────────────────────────────────────────────────────

    /** Filter antrian hari ini berdasarkan tanggal_booking. */
    public function scopeHariIni($query)
    {
        return $query->whereDate('tanggal_booking', today());
    }

    /** Filter antrian berdasarkan tanggal booking tertentu. */
    public function scopeTanggalBooking($query, $date)
    {
        return $query->whereDate('tanggal_booking', $date);
    }

    /** Hanya antrian yang masih aktif (menunggu / diproses). */
    public function scopeAktif($query)
    {
        return $query->whereIn('status', ['menunggu', 'diproses']);
    }

    /** Hanya antrian dengan status menunggu. */
    public function scopeMenunggu($query)
    {
        return $query->where('status', 'menunggu');
    }

    // ─── Business Logic ───────────────────────────────────────────────────────

    /**
     * Generate nomor antrian otomatis berdasarkan tanggal booking.
     * Format: A001, A002, ... — reset per tanggal booking.
     */
    public static function generateNomor(?string $date = null): string
    {
        $targetDate = $date ?? today()->toDateString();
        $last = self::whereDate('tanggal_booking', $targetDate)->latest('id')->first();
        $number = $last ? ((int) substr($last->nomor_antrian, 1)) + 1 : 1;

        return 'A' . str_pad($number, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Hitung posisi antrian aktif berdasarkan ID antrian.
     */
    public static function hitungPosisi(int $antrianId): int
    {
        $antrian = self::find($antrianId);
        if (!$antrian) return 0;

        return self::whereDate('tanggal_booking', $antrian->tanggal_booking)
            ->where('status', 'menunggu')
            ->where('id', '<=', $antrianId)
            ->count();
    }

    /**
     * Re-kalkulasi posisi untuk semua antrian yang masih menunggu pada tanggal tertentu.
     */
    public static function recalculatePosisi(?string $date = null): void
    {
        $query = self::where('status', 'menunggu');
        if ($date) {
            $query->whereDate('tanggal_booking', $date);
        }

        $query->orderBy('created_at')
            ->each(function ($antrian, $index) {
                $antrian->update(['posisi_antrian' => $index + 1]);
            });
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Model Antrian — merepresentasikan satu antrian kendaraan.
 * Berisi logika bisnis: generate nomor otomatis & hitung posisi.
 */
class Antrian extends Model
{
    protected $fillable = [
        'user_id',
        'jenis_layanan_id',
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
            'selesai_at' => 'datetime',
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

    /** Filter antrian hari ini. */
    public function scopeHariIni($query)
    {
        return $query->whereDate('created_at', today());
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
     * Generate nomor antrian otomatis untuk hari ini.
     * Format: A001, A002, ... — reset setiap hari.
     */
    public static function generateNomor(): string
    {
        $last = self::whereDate('created_at', today())->latest()->first();
        $number = $last ? ((int) substr($last->nomor_antrian, 1)) + 1 : 1;

        return 'A' . str_pad($number, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Hitung posisi antrian aktif berdasarkan ID antrian.
     * Posisi dihitung dari semua antrian "menunggu" yang dibuat sebelum antrian ini.
     */
    public static function hitungPosisi(int $antrianId): int
    {
        return self::where('status', 'menunggu')
            ->where('id', '<=', $antrianId)
            ->count();
    }

    /**
     * Re-kalkulasi posisi untuk semua antrian yang masih menunggu.
     * Dipanggil setelah ada antrian yang selesai/dibatalkan.
     */
    public static function recalculatePosisi(): void
    {
        self::where('status', 'menunggu')
            ->orderBy('created_at')
            ->each(function ($antrian, $index) {
                $antrian->update(['posisi_antrian' => $index + 1]);
            });
    }
}

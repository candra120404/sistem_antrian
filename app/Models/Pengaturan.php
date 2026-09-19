<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model Pengaturan — menyimpan konfigurasi batas harian & limit booking.
 */
class Pengaturan extends Model
{
    protected $table = 'pengaturans';

    protected $fillable = [
        'batas_maksimal_pelanggan_harian',
        'min_booking_per_user',
        'max_booking_per_user',
    ];

    /**
     * Ambil instance pengaturan tunggal (singleton record).
     */
    public static function getAktif(): self
    {
        return self::firstOrCreate(
            ['id' => 1],
            [
                'batas_maksimal_pelanggan_harian' => 50,
                'min_booking_per_user'          => 1,
                'max_booking_per_user'          => 2,
            ]
        );
    }
}

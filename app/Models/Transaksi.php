<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model Transaksi — menyimpan data pembayaran yang otomatis dibuat saat antrian selesai.
 */
class Transaksi extends Model
{
    protected $fillable = [
        'antrian_id',
        'total_bayar',
        'status_bayar',
        'tanggal',
    ];

    protected function casts(): array
    {
        return [
            'total_bayar' => 'decimal:2',
            'tanggal'     => 'date',
        ];
    }

    // ─── Relasi ────────────────────────────────────────────────────────────────

    /** Transaksi berasal dari satu antrian. */
    public function antrian()
    {
        return $this->belongsTo(Antrian::class);
    }
}

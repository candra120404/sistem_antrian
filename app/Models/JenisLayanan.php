<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model JenisLayanan — mewakili jenis layanan cuci/servis kendaraan beserta harga.
 */
class JenisLayanan extends Model
{
    protected $fillable = [
        'nama_layanan',
        'jenis_kendaraan',
        'harga',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'harga'     => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    // ─── Relasi ────────────────────────────────────────────────────────────────

    /** Satu jenis layanan bisa digunakan oleh banyak antrian. */
    public function antrians()
    {
        return $this->hasMany(Antrian::class);
    }

    // ─── Scope ────────────────────────────────────────────────────────────────

    /** Hanya tampilkan layanan yang aktif. */
    public function scopeAktif($query)
    {
        return $query->where('is_active', true);
    }
}

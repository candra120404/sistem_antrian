<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * Model User — mewakili pengguna sistem (admin dan pelanggan).
 */
class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    // ─── Relasi ────────────────────────────────────────────────────────────────

    /** Pelanggan dapat memiliki banyak antrian. */
    public function antrians()
    {
        return $this->hasMany(Antrian::class);
    }

    // ─── Helper ────────────────────────────────────────────────────────────────

    /** Cek apakah user adalah admin. */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /** Cek apakah user adalah pelanggan. */
    public function isPelanggan(): bool
    {
        return $this->role === 'pelanggan';
    }
}

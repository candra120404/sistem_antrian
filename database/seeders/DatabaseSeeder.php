<?php

namespace Database\Seeders;

use App\Models\JenisLayanan;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * DatabaseSeeder — mengisi data awal untuk aplikasi bengkel.
 * Membuat akun admin default dan daftar jenis layanan.
 */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── 1. Buat admin default ──────────────────────────────────────────────
        User::create([
            'name'     => 'Admin Bengkel',
            'username' => 'admin',
            'email'    => 'admin@bengkel.com',
            'password' => bcrypt('password123'),
            'role'     => 'admin',
        ]);

        // ── 2. Buat jenis layanan default ─────────────────────────────────────
        JenisLayanan::insert([
            [
                'nama_layanan'    => 'Cuci Motor Standar',
                'jenis_kendaraan' => 'motor',
                'harga'           => 15000,
                'is_active'       => true,
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
            [
                'nama_layanan'    => 'Cuci Motor Premium',
                'jenis_kendaraan' => 'motor',
                'harga'           => 25000,
                'is_active'       => true,
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
            [
                'nama_layanan'    => 'Cuci Mobil Standar',
                'jenis_kendaraan' => 'mobil',
                'harga'           => 35000,
                'is_active'       => true,
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
            [
                'nama_layanan'    => 'Cuci Mobil Premium',
                'jenis_kendaraan' => 'mobil',
                'harga'           => 60000,
                'is_active'       => true,
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
        ]);
    }
}

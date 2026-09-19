<?php

namespace Database\Seeders;

use App\Models\JenisLayanan;
use App\Models\Pengaturan;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * DatabaseSeeder — mengisi data awal untuk aplikasi bengkel/cuci kendaraan.
 * Membuat akun admin default, pengaturan sistem, dan daftar jenis layanan dengan detail jenis cuci.
 */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── 1. Buat admin default ──────────────────────────────────────────────
        User::firstOrCreate(
            ['username' => 'admin'],
            [
                'name'     => 'Admin Bengkel',
                'email'    => 'admin@bengkel.com',
                'password' => bcrypt('password123'),
                'role'     => 'admin',
            ]
        );

        // ── 2. Buat Pengaturan Default ──────────────────────────────────────────
        Pengaturan::getAktif();

        // ── 3. Buat jenis layanan default dengan detail jenis cuci ─────────────
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        JenisLayanan::truncate();
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        JenisLayanan::insert([
            [
                'nama_layanan'    => 'Cuci Motor Standar',
                'jenis_kendaraan' => 'motor',
                'jenis_cuci'      => 'Cuci Reguler',
                'deskripsi'       => 'Pencucian bodi motor dengan sampo khusus dan pengeringan lap kanebo.',
                'est_durasi_menit'=> 15,
                'harga'           => 15000,
                'is_active'       => true,
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
            [
                'nama_layanan'    => 'Cuci Motor Salju & Semir',
                'jenis_kendaraan' => 'motor',
                'jenis_cuci'      => 'Cuci Salju (Snow Wash)',
                'deskripsi'       => 'Cuci busa salju aktif, pembersihan rantai/mesin ringan, dan semir ban premium.',
                'est_durasi_menit'=> 25,
                'harga'           => 25000,
                'is_active'       => true,
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
            [
                'nama_layanan'    => 'Cuci Mobil Reguler',
                'jenis_kendaraan' => 'mobil',
                'jenis_cuci'      => 'Cuci Bodi & Pengeringan',
                'deskripsi'       => 'Pencucian luar bodi mobil, penyedotan debu karpet luar, dan lap kaca.',
                'est_durasi_menit'=> 30,
                'harga'           => 35000,
                'is_active'       => true,
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
            [
                'nama_layanan'    => 'Cuci Mobil Hidrolik & Salju',
                'jenis_kendaraan' => 'mobil',
                'jenis_cuci'      => 'Cuci Hidrolik (Undercarriage)',
                'deskripsi'       => 'Pengangkatan hidrolik H-Track, pencucian kolong mobil, busa salju, dan semir ban.',
                'est_durasi_menit'=> 45,
                'harga'           => 50000,
                'is_active'       => true,
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
            [
                'nama_layanan'    => 'Cuci Mobil Interior & Detailing Wax',
                'jenis_kendaraan' => 'mobil',
                'jenis_cuci'      => 'Full Detailing & Waxing',
                'deskripsi'       => 'Cuci hidrolik salju lengkap, vacuum interior & bagasi, pembersihan AC, dan pemolesan lapisan wax bodi.',
                'est_durasi_menit'=> 60,
                'harga'           => 85000,
                'is_active'       => true,
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
        ]);
    }
}

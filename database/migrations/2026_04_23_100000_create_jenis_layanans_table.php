<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration untuk tabel jenis_layanans.
 * Menyimpan daftar jenis layanan bengkel beserta harga dan jenis kendaraan.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jenis_layanans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_layanan');                             // Contoh: "Cuci Motor Standar"
            $table->enum('jenis_kendaraan', ['motor', 'mobil']);
            $table->decimal('harga', 10, 2);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jenis_layanans');
    }
};

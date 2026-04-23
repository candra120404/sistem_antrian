<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration untuk tabel antrians.
 * Menyimpan data antrian pelanggan beserta status dan posisi antrian.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('antrians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('jenis_layanan_id')->constrained('jenis_layanans');
            $table->string('nomor_antrian', 10);     // Format: A001, A002, dst
            $table->string('nama_pelanggan');
            $table->string('no_plat');
            $table->enum('jenis_kendaraan', ['motor', 'mobil']);
            $table->enum('status', ['menunggu', 'diproses', 'selesai', 'batal'])->default('menunggu');
            $table->integer('posisi_antrian')->nullable();
            $table->timestamp('selesai_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('antrians');
    }
};

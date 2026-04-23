<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration untuk tabel transaksis.
 * Menyimpan data transaksi pembayaran yang otomatis dibuat saat antrian selesai.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('antrian_id')->constrained()->onDelete('cascade');
            $table->decimal('total_bayar', 10, 2);
            $table->enum('status_bayar', ['belum', 'lunas'])->default('belum');
            $table->date('tanggal');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};

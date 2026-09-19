<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jenis_layanans', function (Blueprint $table) {
            $table->string('jenis_cuci')->nullable()->after('jenis_kendaraan');
            $table->text('deskripsi')->nullable()->after('jenis_cuci');
            $table->integer('est_durasi_menit')->default(20)->after('deskripsi');
        });
    }

    public function down(): void
    {
        Schema::table('jenis_layanans', function (Blueprint $table) {
            $table->dropColumn(['jenis_cuci', 'deskripsi', 'est_durasi_menit']);
        });
    }
};

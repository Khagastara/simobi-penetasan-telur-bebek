<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('detail_penjadwalans', function (Blueprint $table) {
            $table->id();
            $table->time('waktu_kegiatan');
            $table->string('keterangan');
            $table->unsignedBigInteger('id_penjadwalan');
            $table->unsignedBigInteger('id_status_kegiatan');

            $table->foreign('id_penjadwalan')->on('penjadwalan_kegiatans')->references('id');
            $table->foreign('id_status_kegiatan')->on('status_kegiatans')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_penjadwalans');
    }
};

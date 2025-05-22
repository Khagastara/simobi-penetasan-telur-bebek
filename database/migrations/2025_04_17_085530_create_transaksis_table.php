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
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->dateTime('tgl_transaksi');
            $table->unsignedBigInteger('id_pengepul');
            $table->unsignedBigInteger('id_status_transaksi');
            $table->unsignedBigInteger('id_metode_pembayaran');
            $table->unsignedBigInteger('id_keuangan');

            $table->foreign('id_pengepul')->on('pengepuls')->references('id');
            $table->foreign('id_status_transaksi')->on('status_transaksis')->references('id');
            $table->foreign('id_metode_pembayaran')->on('metode_pembayarans')->references('id');
            $table->foreign('id_keuangan')->on('keuangans')->references('id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};

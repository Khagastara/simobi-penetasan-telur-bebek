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
        Schema::create('keuangans', function (Blueprint $table) {
            $table->id();
            $table->integer('saldo_pemasukkan');
            $table->integer('saldo_pengeluaran');
            $table->string('grafik_penjualan');
            $table->date('tgl_rekapitulasi');
            $table->integer('total_penjualan');
            $table->unsignedBigInteger('id_transaksi');

            $table->foreign('id_transaksi')->on('transaksis')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keuangans');
    }
};

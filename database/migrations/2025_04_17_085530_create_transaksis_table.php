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
            $table->string('snap_token')->nullable();
            $table->string('order_id')->nullable();
            $table->enum('payment_status', ['pending', 'success', 'failed', 'expired', 'cancelled'])->default('pending');
            $table->unsignedBigInteger('id_pengepul');
            $table->unsignedBigInteger('id_status_transaksi')->default(1);
            $table->unsignedBigInteger('id_metode_pembayaran');
            $table->unsignedBigInteger('id_keuangan')->nullable();
            $table->timestamps();

            $table->foreign('id_pengepul')->references('id')->on('pengepuls')->onDelete('cascade');
            $table->foreign('id_status_transaksi')->references('id')->on('status_transaksis');
            $table->foreign('id_metode_pembayaran')->references('id')->on('metode_pembayarans');
            $table->foreign('id_keuangan')->references('id')->on('keuangans')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};

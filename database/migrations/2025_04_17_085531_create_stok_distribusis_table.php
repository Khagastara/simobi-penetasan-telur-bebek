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
        Schema::create('stok_distribusis', function (Blueprint $table) {
            $table->id();
            $table->string('nama_stok');
            $table->integer('jumlah_stok');
            $table->integer('harga_stok');
            $table->text('deskripsi_stok')->nullable();
            $table->string('gambar_stok');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stok_distribusis');
    }
};

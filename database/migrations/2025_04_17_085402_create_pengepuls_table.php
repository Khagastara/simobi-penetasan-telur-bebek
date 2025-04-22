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
        Schema::create('pengepuls', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('no_hp')->unique();
            $table->unsignedBigInteger('id_akun');

            $table->foreign('id_akun')->on('akuns')->references('id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengepuls');
    }
};

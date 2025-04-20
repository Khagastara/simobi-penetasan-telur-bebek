<?php

namespace Database\Factories;

use App\Models\Transaksi;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaksi>
 */
class TransaksiFactory extends Factory
{
    protected $model = Transaksi::class;

    public function definition()
    {
        return [
            'tanggal_transaksi' => $this->faker->dateTime(),
            'id_status_transaksi' => \App\Models\StatusKegiatan::factory(),
            'id_pengepul' => \App\Models\Pengepul::factory(),
            'id_metode_pembayaran' => \App\Models\MetodePembayaran::factory(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}

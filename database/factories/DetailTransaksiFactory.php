<?php

namespace Database\Factories;

use App\Models\DetailTransaksi;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DetailTransaksi>
 */
class DetailTransaksiFactory extends Factory
{
    protected $model = DetailTransaksi::class;

    public function definition()
    {
        return [
            'kuantitas' => $this->faker->randomNumber(),
            'subtotal' => $this->faker->randomNumber(),
            'id_transaksi' => \App\Models\Transaksi::factory(),
            'id_stok' => \App\Models\StokDistribusi::factory(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}

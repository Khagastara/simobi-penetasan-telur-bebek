<?php

namespace Database\Factories;

use App\Models\StokDistribusi;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StokDistribusi>
 */
class StokDistribusiFactory extends Factory
{
    protected $model = StokDistribusi::class;

    public function definition()
    {
        return [
            'nama_stok' => $this->faker->name(),
            'jumlah_stok' => $this->faker->randomNumber(),
            'harga_stok' => $this->faker->randomNumber(),
            'deskripsi' => $this->faker->sentence,
            'gambar_stok' => $this->faker->imageUrl(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}

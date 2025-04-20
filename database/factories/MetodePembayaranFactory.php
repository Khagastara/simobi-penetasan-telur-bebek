<?php

namespace Database\Factories;

use App\Models\MetodePembayaran;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MetodePembayaran>
 */
class MetodePembayaranFactory extends Factory
{
    protected $model = MetodePembayaran::class;

    public function definition()
    {
        return [
            'nama_metode' => $this->faker->randomElement(['Tunai', 'Transfer']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}

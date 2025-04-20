<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StatusTransaksi>
 */
class StatusTransaksiFactory extends Factory
{
    public function definition()
    {
        return [
            'nama_status' => $this->faker->randomElement(['Pembayaran Valid', 'Dikemas', 'Dikirim', 'Selesai']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\StatusKegiatan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StatusKegiatan>
 */
class StatusKegiatanFactory extends Factory
{
    protected $model = StatusKegiatan::class;

    public function definition()
    {
        return [
            'nama_status' => $this->faker->randomElement(['To Do', 'Selesai', 'Gagal']),
            'deskripsi' => $this->faker->sentence(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}

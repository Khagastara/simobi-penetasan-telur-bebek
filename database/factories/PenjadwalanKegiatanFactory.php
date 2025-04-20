<?php

namespace Database\Factories;

use App\Models\PenjadwalanKegiatan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PenjadwalanKegiatan>
 */
class PenjadwalanKegiatanFactory extends Factory
{
    protected $model = PenjadwalanKegiatan::class;

    public function definition()
    {
        return [
            'nama_kegiatan' => $this->faker->sentence(3),
            'tgl_penjadwalan' => $this->faker->dateTimeBetween('now'),
            'id_owner' => \App\Models\Owner::factory(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}

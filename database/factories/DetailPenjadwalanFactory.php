<?php

namespace Database\Factories;

use App\Models\DetailPenjadwalan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DetailPenjadwalan>
 */
class DetailPenjadwalanFactory extends Factory
{
    protected $model = DetailPenjadwalan::class;

    public function definition()
    {
        return [
            'waktu_kegiatan' => $this->faker->time(),
            'keterangan' => $this->faker->paragraph,
            'id_penjadwalan' => \App\Models\PenjadwalanKegiatan::factory(),
            'id_status_kegiatan' => \App\Models\StatusKegiatan::factory(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\Pengepul;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pengepul>
 */
class PengepulFactory extends Factory
{
    protected $model = Pengepul::class;

    public function definition()
    {
        return [
            'nama' => $this->faker->name(),
            'no_hp' => '08' . $this->faker->unique()->numerify('##########'),
            'id_akun' => \App\Models\Akun::factory(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\Keuangan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Keuangan>
 */
class KeuanganFactory extends Factory
{
    protected $model = Keuangan::class;

    public function definition()
    {
        return [
            'saldo_pemasukan' => $this->faker->randomNumber(),
            'saldo_pengeluaran' => $this->faker->randomNumberumber(),
            'grafik_penjualan' => $this->faker->imageUrl(640, 480, 'business', true, 'Graph'),
            'tgl_rekapitulasi' => $this->faker->date(),
            'total_penjualan' => $this->faker->randomNumber(),
            'id_transaksi' => \App\Models\Transaksi::factory(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}

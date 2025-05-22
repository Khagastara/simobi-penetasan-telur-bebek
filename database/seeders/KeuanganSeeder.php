<?php

namespace Database\Seeders;

use App\Models\Keuangan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KeuanganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $this->call([TransaksiSeeder::class]);
        Keuangan::insert([
            'saldo_pemasukkan' => 300000,
            'saldo_pengeluaran' => 100000,
            'tgl_rekapitulasi' => '2025-04-20',
            'total_penjualan' => 70,
        ]);

        Keuangan::insert([
            'saldo_pemasukkan' => 560000,
            'saldo_pengeluaran' => 100000,
            'tgl_rekapitulasi' => '2025-04-21',
            'total_penjualan' => 70,
        ]);

        Keuangan::insert([
            'saldo_pemasukkan' => 160000,
            'saldo_pengeluaran' => 50000,
            'tgl_rekapitulasi' => '2025-05-17',
            'total_penjualan' => 70,
        ]);
    }
}

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
            'saldo_pemasukkan' => 4600000,
            'saldo_pengeluaran' => 100000,
            'tgl_rekapitulasi' => '2025-04-20',
            'total_penjualan' => 70,
            'id_transaksi' => 2,
        ]);

        Keuangan::insert([
            'saldo_pemasukkan' => 4600000,
            'saldo_pengeluaran' => 100000,
            'tgl_rekapitulasi' => '2025-04-20',
            'total_penjualan' => 70,
            'id_transaksi' => 1,
        ]);

        Keuangan::insert([
            'saldo_pemasukkan' => 560000,
            'saldo_pengeluaran' => 50000,
            'tgl_rekapitulasi' => '2025-04-28',
            'total_penjualan' => 70,
            'id_transaksi' => 2,
        ]);
    }
}

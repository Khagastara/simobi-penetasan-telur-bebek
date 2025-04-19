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
        $this->call([TransaksiSeeder::class]);
        Keuangan::insert([
            'id' => 1,
            'saldo_pemasukan' => 2000000,
            'saldo_pengeluaran' => 1000000,
            'grafik_penjualan' => 'X,Y(20,10)',
            'tgl_rekapitulasi' => '20-4-2025',
            'total_penjualan' => 100,
            'id_transaksi' => 1,
        ]);

        Keuangan::insert([
            'id' => 2,
            'saldo_pemasukan' => 2500000,
            'saldo_pengeluaran' => 900000,
            'grafik_penjualan' => 'X,Y(25,10)',
            'tgl_rekapitulasi' => '28-4-2025',
            'total_penjualan' => 125,
            'id_transaksi' => 2,
        ]);

        Keuangan::insert([
            'id' => 3,
            'saldo_pemasukan' => 1000000,
            'saldo_pengeluaran' => 500000,
            'grafik_penjualan' => 'X,Y(10,5)',
            'tgl_rekapitulasi' => '30-4-2025',
            'total_penjualan' => 50,
            'id_transaksi' => 3,
        ]);
    }
}

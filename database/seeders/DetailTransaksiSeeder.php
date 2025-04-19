<?php

namespace Database\Seeders;

use App\Models\DetailTransaksi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DetailTransaksiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([StokDistribusiSeeder::class, TransaksiSeeder::class]);
        DetailTransaksi::insert([
            'id' => 1,
            'kuantitas' => 30,
            'sub_total' => 8000,
            'id_stok_distribusi' => 1,
            'id_transaksi' => 1,
        ]);

        DetailTransaksi::insert([
            'id' => 2,
            'kuantitas' => 20,
            'sub_total' => 10000,
            'id_stok_distribusi' => 1,
            'id_transaksi' => 1,
        ]);
    }
}

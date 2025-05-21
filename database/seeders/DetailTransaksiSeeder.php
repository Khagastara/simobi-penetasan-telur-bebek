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
        // $this->call([StokDistribusiSeeder::class, TransaksiSeeder::class]);
        DetailTransaksi::insert([
            // 'id' => 1,
            'kuantitas' => 30,
            'sub_total' => 180000,
            'id_stok_distribusi' => 1,
            'id_transaksi' => 1,
        ]);

        DetailTransaksi::insert([
            'kuantitas' => 20,
            'sub_total' => 120000,
            'id_stok_distribusi' => 1,
            'id_transaksi' => 2,
        ]);

        DetailTransaksi::insert([
            'kuantitas' => 40,
            'sub_total' => 320000,
            'id_stok_distribusi' => 2,
            'id_transaksi' => 3,
        ]);

        DetailTransaksi::insert([
            'kuantitas' => 30,
            'sub_total' => 240000,
            'id_stok_distribusi' => 2,
            'id_transaksi' => 4,
        ]);

        DetailTransaksi::insert([
            'kuantitas' => 20,
            'sub_total' => 160000,
            'id_stok_distribusi' => 2,
            'id_transaksi' => 5,
        ]);
    }
}

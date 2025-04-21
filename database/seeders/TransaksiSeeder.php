<?php

namespace Database\Seeders;

use App\Models\Transaksi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TransaksiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $this->call([StatusTransaksiSeeder::class, PengepulSeeder::class, MetodePembayaranSeeder::class]);
        Transaksi::insert([
            // 'id' => 1,
            'tgl_transaksi' => '2025-04-20 12:09:30',
            'id_status_transaksi' => 1,
            'id_pengepul' => 1,
            'id_metode_pembayaran' => 1,
        ]);

        Transaksi::insert([
            // 'id' => 2,
            'tgl_transaksi' => '2025-04-20 12:08:40',
            'id_status_transaksi' => 1,
            'id_pengepul' => 1,
            'id_metode_pembayaran' => 2,
        ]);

        Transaksi::insert([
            // 'id' => 3,
            'tgl_transaksi' => '2025-04-21 10:10:10',
            'id_status_transaksi' => 2,
            'id_pengepul' => 2,
            'id_metode_pembayaran' => 2,
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\DetailPenjadwalan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DetailPenjadwalanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $this->call([StatusKegiatanSeeder::class, PenjadwalanKegiatanSeeder::class]);
        DetailPenjadwalan::insert([
            // 'id' => 1,
            'waktu_kegiatan' => '07:00',
            'keterangan' => 'Mengecek kondisi telur',
            'id_penjadwalan' => 1,
            'id_status_kegiatan' => 2,
        ]);

        DetailPenjadwalan::insert([
            // 'id' => 2,
            'waktu_kegiatan' => '07:00',
            'keterangan' => 'Memberikan pakan',
            'id_penjadwalan' => 1,
            'id_status_kegiatan' => 2,
        ]);

        DetailPenjadwalan::insert([
            // 'id' => 3,
            'waktu_kegiatan' => '22:30',
            'keterangan' => 'Memberikan vitamin',
            'id_penjadwalan' => 1,
            'id_status_kegiatan' => 3,
        ]);
    }
}

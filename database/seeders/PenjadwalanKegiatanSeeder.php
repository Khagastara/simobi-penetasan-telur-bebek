<?php

namespace Database\Seeders;

use App\Models\PenjadwalanKegiatan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PenjadwalanKegiatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $this->call([OwnerSeeder::class]);
        PenjadwalanKegiatan::insert([
            // 'id' => 1,
            'tgl_penjadwalan' => '2025-05-18',
            'id_owner' => 1,
        ]);
    }
}

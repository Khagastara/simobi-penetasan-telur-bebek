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
        $this->call([OwnerSeeder::class]);
        PenjadwalanKegiatan::insert([
            'id' => 1,
            'tgl_penjadwalan' => '20-4-2025',
            'id_owner' => 1,
        ]);

        PenjadwalanKegiatan::insert([
            'id' => 2,
            'tgl_penjadwalan' => '20-4-2025',
            'id_owner' => 1,
        ]);

        PenjadwalanKegiatan::insert([
            'id' => 3,
            'tgl_penjadwalan' => '21-4-2025',
            'id_owner' => 1,
        ]);

        PenjadwalanKegiatan::insert([
            'id' => 4,
            'tgl_penjadwalan' => '1-5-2025',
            'id_owner' => 1,
        ]);
    }
}

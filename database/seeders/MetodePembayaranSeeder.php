<?php

namespace Database\Seeders;

use App\Models\MetodePembayaran;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MetodePembayaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MetodePembayaran::insert([
            // 'id' => 1,
            'nama_metode' => 'tunai',
        ]);

        MetodePembayaran::insert([
            // 'id' => 2,
            'nama_metode' => 'transfer',
        ]);
    }
}

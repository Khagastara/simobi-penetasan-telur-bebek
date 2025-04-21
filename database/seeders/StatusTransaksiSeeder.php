<?php

namespace Database\Seeders;

use App\Models\StatusTransaksi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatusTransaksiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        StatusTransaksi::insert([
            'id' => 1,
            'nama_status' => 'Pembayaran Valid',
        ]);

        StatusTransaksi::insert([
            'id' => 2,
            'nama_status' => 'Dikirim',
        ]);

        StatusTransaksi::insert([
            'id' => 3,
            'nama_status' => 'Selesai',
        ]);

        StatusTransaksi::insert([
            'id' => 4,
            'nama_status' => 'Dikemas',
        ]);

        StatusTransaksi::insert([
            'id' => 5,
            'nama_status' => 'Dikemas',
        ]);
    }
}

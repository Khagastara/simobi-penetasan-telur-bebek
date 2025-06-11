<?php

namespace Database\Seeders;

use App\Models\StokDistribusi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StokDistribusiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        StokDistribusi::insert([
            // 'id' => 1,
            'nama_stok' => 'Anakan Bebek',
            'jumlah_stok' => 20,
            'harga_stok' => 6000,
            'deskripsi_stok' => 'Anakan bebek berkualitas umur 2 minggu',
            'gambar_stok' => 'storage/stok/Anakan-Bebek.jpg',
        ]);

        StokDistribusi::insert([
            // 'id' => 2,
            'nama_stok' => 'Telur Penetasan',
            'jumlah_stok' => 100,
            'harga_stok' => 8000,
            'deskripsi_stok' => 'Telur yang belum menetas tapi ada embrio',
            'gambar_stok' => 'storage/stok/Telur-Penetasan-Bebek.jpg',
        ]);
    }
}

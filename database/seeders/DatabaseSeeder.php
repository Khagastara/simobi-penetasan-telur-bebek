<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AkunSeeder::class,
            PengepulSeeder::class,
            OwnerSeeder::class,
            PenjadwalanKegiatanSeeder::class,
            StatusKegiatanSeeder::class,
            DetailPenjadwalanSeeder::class,
            MetodePembayaranSeeder::class,
            StatusTransaksiSeeder::class,
            TransaksiSeeder::class,
            KeuanganSeeder::class,
            StokDistribusiSeeder::class,
            DetailTransaksiSeeder::class,
        ]);
    }
}

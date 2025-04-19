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
            OwnerSeeder::class,
            PengepulSeeder::class,
            PenjadwalanKegiatanSeeder::class,
            StatusKegiatanSeeder::class,
            DetailPenjadwalanSeeder::class,
            MetodePembayaranSeeder::class,
            TransaksiSeeder::class,
            KeuanganSeeder::class,
            StatusTransaksiSeeder::class,
            StokDistribusiSeeder::class,
            DetailTransaksiSeeder::class,
        ]);
    }
}

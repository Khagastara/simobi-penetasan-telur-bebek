<?php

namespace Database\Seeders;

use App\Models\Owner;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OwnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([AkunSeeder::class]);
        Owner::insert([
            'id' => 1,
            'nama' => 'Amadeus Mozart',
            'no_hp' => '08585802650',
            'id_akun' => 4,
        ]);
    }
}

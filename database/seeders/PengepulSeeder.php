<?php

namespace Database\Seeders;

use App\Models\Pengepul;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PengepulSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([AkunSeeder::class]);
        Pengepul::insert([
            'id' => 1,
            'nama' => 'Ilay Riegrow',
            'no_hp' => '081335191658',
            'id_akun' => 1,
        ]);

        Pengepul::insert([
            'id' => 2,
            'nama' => 'Sagara Sastra',
            'no_hp' => '081234789087',
            'id_akun' => 2,
        ]);

        Pengepul::insert([
            'id' => 3,
            'nama' => 'Alsaggaf Alam',
            'no_hp' => '087875334566',
            'id_akun' => 3,
        ]);
    }
}

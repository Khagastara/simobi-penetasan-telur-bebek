<?php

namespace Database\Seeders;

use App\Models\Akun;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AkunSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Akun::insert([
            'id' => 1,
            'username' => 'ilay_riegrow',
            'email' => 'ilayriegrow20@gmail.com',
            'password' => 'ilaymuda89',
        ]);
        Akun::insert([
            'id' => 2,
            'username' => 'sagara_sas',
            'email' => 'sagarasastra88@gmail.com',
            'password' => 'sag789ok',
        ]);
        Akun::insert([
            'id' => 3,
            'username' => 'alam_rich',
            'email' => 'pecintalam90@gmail.com',
            'password' => 'alam0090',
        ]);
        Akun::insert([
            'id' => 4,
            'username' => 'ama_mozzart',
            'email' => 'jagopiano77@gmail.com',
            'password' => 'mozart567',
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\StatusKegiatan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatusKegiatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        StatusKegiatan::insert([
            'nama_status_kgtn' => 'To Do',
            'deskripsi' => 'Kegiatan yang akan dilaksanakan',
        ]);

        StatusKegiatan::insert([
            'nama_status_kgtn' => 'Selesai',
            'deskripsi' => 'Kegiatan yang telah dilaksanakan dan sudah selesai',
        ]);

        StatusKegiatan::insert([
            'nama_status_kgtn' => 'Gagal',
            'deskripsi' => 'kegiatan yang gagal dilaksanakan',
        ]);
    }
}

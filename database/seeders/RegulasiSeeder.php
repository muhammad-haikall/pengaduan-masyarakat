<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RegulasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('regulasi')->insert([
            'nama_regulasi' => 'Regulasi Umum',
            'deskripsi' => 'Regulasi umum untuk pengaduan masyarakat',
        ]);
    }
}

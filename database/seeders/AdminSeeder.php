<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('admin')->insert([
            'username' => 'admin',
            'password' => 'admin123', // tanpa hash
            'nama' => 'Super Admin',
        ]);
    }
}
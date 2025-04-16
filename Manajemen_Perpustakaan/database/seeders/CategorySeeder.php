<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // seeder untuk mengisi tabel dari categories
        $data = [
            ['name' => 'Pemrograman'],
            ['name' => 'Jaringan'],
        ];
        DB::table('categories')->insert($data);
    }
}

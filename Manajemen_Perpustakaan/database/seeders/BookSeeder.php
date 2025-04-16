<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // seeder untuk mengisi tabel dari books
        $data = [
            [
                'title' => 'Belajar PHP',
                'author' => 'Rudi Hartono',
                'publisher' => 'Informatika Bandung',
                'year' => 2023,
                'category_id' => 1,
                'stock' => 7,
            ],
            [
                'title' => 'Belajar Laravel',
                'author' => 'Mahfud Satrio',
                'publisher' => 'Informatika POLINEMA',
                'year' => 2020,
                'category_id' => 2,
                'stock' => 7,
            ]
        ];
        DB::table('books')->insert($data);
    }
}

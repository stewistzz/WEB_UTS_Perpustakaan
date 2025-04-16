<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // seeder untuk mengisi tabel dari categories
        $data = [
            [
                'name' => 'Admin Perpus',
                'email' => 'admin@perpus.com',
                'password' => bcrypt('password123'),
            ]
        ];
        DB::table('users')->insert($data);
    }
}

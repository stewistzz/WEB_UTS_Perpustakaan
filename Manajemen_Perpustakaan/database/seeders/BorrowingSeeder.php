<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BorrowingSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'user_id' => 1,
                'book_id' => 1,
                'borrowed_at' => Carbon::now()->subDays(3)->format('Y-m-d'),
                'due_date' => Carbon::now()->addDays(4)->format('Y-m-d'),
                'returned_at' => Carbon::now()->subDays(2)->format('Y-m-d'),
                'status' => 'dipinjam',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 1,
                'book_id' => 2,
                'borrowed_at' => Carbon::now()->subDays(10)->format('Y-m-d'),
                'due_date' => Carbon::now()->subDays(3)->format('Y-m-d'),
                'returned_at' => Carbon::now()->subDays(2)->format('Y-m-d'),
                'status' => 'dikembalikan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('borrowings')->insert($data);
    }
}


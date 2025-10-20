<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('reviews')->insert([
            ['id' => 1, 'product_id' => 1, 'user_id' => 1, 'rating' => 5, 'title' => 'Best headphones I\'ve ever owned!', 'comment' => 'The noise cancellation is incredible. I use these daily for work and they\'re perfect. Battery life is exactly as advertised.', 'verified' => true, 'helpful_count' => 45, 'created_at' => '2025-01-15 10:30:00'],
            ['id' => 2, 'product_id' => 1, 'user_id' => 1, 'rating' => 4, 'title' => 'Great sound quality', 'comment' => 'Love the sound quality and comfort. Only minor issue is they can feel a bit tight after extended wear.', 'verified' => true, 'helpful_count' => 23, 'created_at' => '2025-01-20 14:20:00'],
            ['id' => 3, 'product_id' => 2, 'user_id' => 1, 'rating' => 5, 'title' => 'Perfect fit and quality', 'comment' => 'The fabric is so soft and the fit is perfect. Will definitely buy more colors!', 'verified' => true, 'helpful_count' => 67, 'created_at' => '2025-01-18 09:15:00'],
        ]);
    }
}

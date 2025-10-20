<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductTagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('product_tags')->insert([
            ['product_id' => 1, 'tag' => 'electronics'], ['product_id' => 1, 'tag' => 'audio'], ['product_id' => 1, 'tag' => 'wireless'],
            ['product_id' => 2, 'tag' => 'fashion'], ['product_id' => 2, 'tag' => 'casual'], ['product_id' => 2, 'tag' => 'organic'],
            ['product_id' => 3, 'tag' => 'home'], ['product_id' => 3, 'tag' => 'lighting'], ['product_id' => 3, 'tag' => 'modern'],
            ['product_id' => 4, 'tag' => 'sports'], ['product_id' => 4, 'tag' => 'yoga'], ['product_id' => 4, 'tag' => 'fitness'],
            ['product_id' => 5, 'tag' => 'electronics'], ['product_id' => 5, 'tag' => 'smartphone'], ['product_id' => 5, 'tag' => '5g'],
            ['product_id' => 6, 'tag' => 'fashion'], ['product_id' => 6, 'tag' => 'accessories'], ['product_id' => 6, 'tag' => 'leather'],
            ['product_id' => 7, 'tag' => 'home'], ['product_id' => 7, 'tag' => 'kitchen'], ['product_id' => 7, 'tag' => 'ceramic'],
            ['product_id' => 8, 'tag' => 'sports'], ['product_id' => 8, 'tag' => 'running'], ['product_id' => 8, 'tag' => 'footwear'],
            ['product_id' => 9, 'tag' => 'sports'], ['product_id' => 9, 'tag' => 'running'], ['product_id' => 9, 'tag' => 'footwear'], ['product_id' => 9, 'tag' => 'fitness'], ['product_id' => 9, 'tag' => 'health'],
        ]);
    }
}

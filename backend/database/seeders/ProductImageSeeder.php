<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('product_images')->insert([
            ['product_id' => 1, 'image_url' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=600', 'sort_order' => 1, 'is_primary' => true],
            ['product_id' => 1, 'image_url' => 'https://images.unsplash.com/photo-1484704849700-f032a568e944?w=600', 'sort_order' => 2, 'is_primary' => false],
            ['product_id' => 2, 'image_url' => 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=600', 'sort_order' => 1, 'is_primary' => true],
            ['product_id' => 2, 'image_url' => 'https://images.unsplash.com/photo-1583743814966-8936f5b7be1a?w=600', 'sort_order' => 2, 'is_primary' => false],
            ['product_id' => 3, 'image_url' => 'https://images.unsplash.com/photo-1507473885765-e6ed057f782c?w=600', 'sort_order' => 1, 'is_primary' => true],
            ['product_id' => 4, 'image_url' => 'https://images.unsplash.com/photo-1601925260368-ae2f83cf8b7f?w=600', 'sort_order' => 1, 'is_primary' => true],
            ['product_id' => 5, 'image_url' => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=600', 'sort_order' => 1, 'is_primary' => true],
            ['product_id' => 5, 'image_url' => 'https://images.unsplash.com/photo-1592286927505-a52e4a4e8ded?w=600', 'sort_order' => 2, 'is_primary' => false],
            ['product_id' => 6, 'image_url' => 'https://images.unsplash.com/photo-1590874103328-eac38a683ce7?w=600', 'sort_order' => 1, 'is_primary' => true],
            ['product_id' => 7, 'image_url' => 'https://images.unsplash.com/photo-1514228742587-6b1558fcca3d?w=600', 'sort_order' => 1, 'is_primary' => true],
            ['product_id' => 8, 'image_url' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=600', 'sort_order' => 1, 'is_primary' => true],
            ['product_id' => 9, 'image_url' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=600', 'sort_order' => 1, 'is_primary' => true],
        ]);
    }
}

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
        DB::table('categories')->insert([
            ['id' => 1, 'name' => 'Electronics', 'slug' => 'electronics', 'image' => 'https://images.unsplash.com/photo-1498049794561-7780e7231661?w=400', 'product_count' => 125, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'Fashion', 'slug' => 'fashion', 'image' => 'https://images.unsplash.com/photo-1445205170230-053b83016050?w=400', 'product_count' => 342, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'Home & Living', 'slug' => 'home-living', 'image' => 'https://images.unsplash.com/photo-1556911220-bff31c812dba?w=400', 'product_count' => 218, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'name' => 'Sports', 'slug' => 'sports', 'image' => 'https://images.unsplash.com/photo-1461896836934-ffe607ba8211?w=400', 'product_count' => 156, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'name' => 'Books', 'slug' => 'books', 'image' => 'https://images.unsplash.com/photo-1495446815901-a7297e633e8d?w=400', 'product_count' => 89, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 6, 'name' => 'Beauty', 'slug' => 'beauty', 'image' => 'https://images.unsplash.com/photo-1596462502278-27bfdc403348?w=400', 'product_count' => 167, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 7, 'name' => 'Health & Fitness', 'slug' => 'health-fitness', 'image' => 'https://images.unsplash.com/photo-1596462502278-27bfdc403348?w=400', 'product_count' => 20, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}

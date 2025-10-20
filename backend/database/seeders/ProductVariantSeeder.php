<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductVariantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('product_variants')->insert([
            ['id' => 1, 'product_id' => 2, 'sku' => 'FASH-001-BLK-M', 'name' => 'Black / Medium', 'price' => 29.99, 'stock' => 30, 'attributes' => json_encode(['color' => 'Black', 'size' => 'M']), 'image' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'product_id' => 2, 'sku' => 'FASH-001-BLK-L', 'name' => 'Black / Large', 'price' => 29.99, 'stock' => 25, 'attributes' => json_encode(['color' => 'Black', 'size' => 'L']), 'image' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'product_id' => 2, 'sku' => 'FASH-001-WHT-M', 'name' => 'White / Medium', 'price' => 29.99, 'stock' => 35, 'attributes' => json_encode(['color' => 'White', 'size' => 'M']), 'image' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'product_id' => 8, 'sku' => 'SPORT-002-BLK-9', 'name' => 'Black / Size 9', 'price' => 159.99, 'stock' => 20, 'attributes' => json_encode(['color' => 'Black', 'size' => '9']), 'image' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'product_id' => 8, 'sku' => 'SPORT-002-BLK-10', 'name' => 'Black / Size 10', 'price' => 159.99, 'stock' => 25, 'attributes' => json_encode(['color' => 'Black', 'size' => '10']), 'image' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 6, 'product_id' => 9, 'sku' => 'FIT-002-BLK-9', 'name' => 'Black / Size 9', 'price' => 159.99, 'stock' => 20, 'attributes' => json_encode(['color' => 'Black', 'size' => '9']), 'image' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=600', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 7, 'product_id' => 9, 'sku' => 'FIT-002-BLK-10', 'name' => 'Black / Size 10', 'price' => 159.99, 'stock' => 25, 'attributes' => json_encode(['color' => 'Black', 'size' => '10']), 'image' => 'https://images.unsplash.com/photo-1514228742587-6b1558fcca3d?w=600', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('order_items')->insert([
            ['id' => 1, 'order_id' => 1, 'product_id' => 1, 'variant_id' => null, 'name' => 'Wireless Noise-Cancelling Headphones', 'image' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=400', 'price' => 299.99, 'quantity' => 1, 'attributes' => null],
            ['id' => 2, 'order_id' => 2, 'product_id' => 2, 'variant_id' => 1, 'name' => 'Classic Cotton T-Shirt', 'image' => 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=400', 'price' => 29.99, 'quantity' => 2, 'attributes' => json_encode(['color' => 'Black', 'size' => 'M'])],
            ['id' => 3, 'order_id' => 2, 'product_id' => 4, 'variant_id' => null, 'name' => 'Yoga Mat Premium', 'image' => 'https://images.unsplash.com/photo-1601925260368-ae2f83cf8b7f?w=400', 'price' => 49.99, 'quantity' => 1, 'attributes' => null],
        ]);
    }
}

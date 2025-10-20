<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('orders')->insert([
            [
                'id' => 1,
                'user_id' => 1,
                'order_number' => 'ORD-2025-001234',
                'status' => 'delivered',
                'subtotal' => 299.99,
                'shipping' => 10.00,
                'tax' => 24.00,
                'discount' => 0,
                'total' => 333.99,
                'payment_status' => 'paid',
                'payment_method' => 'Credit Card',
                'tracking_number' => 'TRK1234567890',
                'shipping_address_id' => 1,
                'created_at' => '2025-01-15 10:30:00',
                'updated_at' => '2025-01-20 14:20:00',
            ],
            [
                'id' => 2,
                'user_id' => 1,
                'order_number' => 'ORD-2025-001235',
                'status' => 'processing',
                'subtotal' => 109.97,
                'shipping' => 5.00,
                'tax' => 8.80,
                'discount' => 10.00,
                'total' => 113.77,
                'payment_status' => 'paid',
                'payment_method' => 'PayPal',
                'tracking_number' => 'TRK1234567891',
                'shipping_address_id' => 1,
                'created_at' => '2025-02-01 08:15:00',
                'updated_at' => '2025-02-01 10:30:00',
            ],
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('coupons')->insert([
            ['id' => 1, 'code' => 'WELCOME10', 'type' => 'percentage', 'amount' => 10, 'min_purchase' => 50, 'uses_limit' => 100, 'expires_at' => '2025-12-31 23:59:59'],
            ['id' => 2, 'code' => 'SAVE20', 'type' => 'fixed', 'amount' => 20, 'min_purchase' => 100, 'uses_limit' => 50, 'expires_at' => '2025-06-30 23:59:59'],
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSpecificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('product_specifications')->insert([
            ['product_id' => 1, 'spec_key' => 'Battery Life', 'spec_value' => '30 hours'],
            ['product_id' => 1, 'spec_key' => 'Wireless Range', 'spec_value' => '10 meters'],
            ['product_id' => 1, 'spec_key' => 'Weight', 'spec_value' => '250g'],
            ['product_id' => 1, 'spec_key' => 'Connectivity', 'spec_value' => 'Bluetooth 5.0'],
        ]);
    }
}

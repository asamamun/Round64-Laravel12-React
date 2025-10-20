<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('addresses')->insert([
            ['id' => 1, 'user_id' => 1, 'name' => 'John Doe', 'phone' => '+1 (555) 123-4567', 'address' => '123 Main Street, Apt 4B', 'city' => 'New York', 'state' => 'NY', 'zip_code' => '10001', 'country' => 'United States', 'is_default' => true],
        ]);
    }
}

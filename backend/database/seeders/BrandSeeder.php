<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('brands')->insert([
            ['id' => 1, 'name' => 'TechPro', 'slug' => 'techpro', 'logo' => 'https://via.placeholder.com/100', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'StyleMax', 'slug' => 'stylemax', 'logo' => 'https://via.placeholder.com/100', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'HomeEssentials', 'slug' => 'homeessentials', 'logo' => 'https://via.placeholder.com/100', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'name' => 'FitGear', 'slug' => 'fitgear', 'logo' => 'https://via.placeholder.com/100', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BrandsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $brands = [
            ['name' => 'Sony', 'created_by' => 2],
            ['name' => 'LG', 'created_by' => 2],
            ['name' => 'Samsung', 'created_by' => 2],
            ['name' => 'Apple', 'created_by' => 2],
            ['name' => 'Vaio', 'created_by' => 2],
            ['name' => 'MSI', 'created_by' => 2],
            ['name' => 'TSCO', 'created_by' => 2],
            ['name' => 'Beyond', 'created_by' => 2],
        ];

        Brand::insert($brands);
    }
}

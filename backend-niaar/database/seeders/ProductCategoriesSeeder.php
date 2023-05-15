<?php

namespace Database\Seeders;

use App\Models\ProductCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            ['title' => 'Digital', 'created_by' => 2],
            ['title' => 'Mobile', 'created_by' => 2],
            ['title' => 'PC', 'created_by' => 2],
            ['title' => 'LapTpo', 'created_by' => 2],
            ['title' => 'Electrical', 'created_by' => 2],
            ['title' => 'Apparel', 'created_by' => 2],
            ['title' => 'Sport', 'created_by' => 2],
            ['title' => 'Entertainment', 'created_by' => 2],
        ];

        ProductCategory::insert($categories);
    }
}

<?php

namespace Database\Seeders;

use App\Models\Box;
use App\Models\Shipping;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ShippingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Shipping::factory()
            ->count(100)
            ->has(Box::factory()->count(4), 'boxes')
            ->create();
    }
}

<?php

namespace Database\Seeders;

use App\Models\Calculation;
use App\Models\FutureDue;
use App\Models\Inquiry;
use App\Models\Payment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InquiriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Inquiry::factory()
            ->has(Calculation::factory()->count(rand(5, 10)))
            ->has(FutureDue::factory()->count(rand(2, 5)))
            ->has(Payment::factory()->count(150))
            ->count(60)
            ->create();
    }
}

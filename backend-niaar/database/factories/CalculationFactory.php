<?php

namespace Database\Factories;

use App\Models\Calculation;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Calculation>
 */
class CalculationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'supplier_id' => Supplier::inRandomOrder()->first()->id,
            'buying_price' => rand(150, 150000),
            'buying_currency' => Arr::random(['USD', 'AED', 'EUR']),
            'buying_total_price_aed' => rand(150, 15000),
            'quoted_price' => rand(150, 15000),
            'quoted_currency' => Arr::random(['USD', 'AED', 'EUR']),
            'quoted_price_aed' => rand(150, 15000),
            'actual_ordered_price_aed'  => rand(150, 15000),
            'qty'  => rand(150, 500),
            'is_extra' => Arr::random([1, 0]),
            'created_by' => Arr::random(range(2, 15)),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Calculation $calculation) {
            $calculation->remark()->create([
                'body' => $this->faker->sentence,
                'created_by' => $calculation->created_by
            ]);

            $calculation->description()->create([
                'body' => $this->faker->sentence,
                'created_by' => $calculation->created_by
            ]);
        });
    }
}

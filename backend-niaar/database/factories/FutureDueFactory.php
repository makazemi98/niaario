<?php

namespace Database\Factories;

use App\Enums\BillToEnum;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FutureDue>
 */
class FutureDueFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'bill_to' => Arr::random(BillToEnum::values()),
            'payee_name' => $this->faker->name,
            'payable_amount' => rand(150, 15000),
            'receivable_amount' => rand(150, 15000),
            'currency' => Arr::random(['USD', 'AED', 'EUR']),
            'due_date' => now()->addDays(rand(3, 10))->format('Y-m-d'),
            'is_paid' => Arr::random([0, 1]),
            'created_by' => Arr::random(range(2, 15))
        ];
    }
}

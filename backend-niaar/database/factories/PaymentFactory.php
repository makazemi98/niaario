<?php

namespace Database\Factories;

use App\Enums\BalanceSheetCategoryEnum;
use App\Enums\PaymentTabsEnum;
use App\Enums\PaymentTypeEnum;
use App\Enums\RolesEnum;
use App\Models\Payment;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'description' => $this->faker->sentence(),
            'debit' => rand(1800, 780000),
            'credit' => rand(1800, 780000),
            'balance' => function (array $attributes) {
                return $attributes['credit'] - $attributes['debit'];
            },
            'date' => now()->addDays(rand(20, 100)),
            'is_paid' => Arr::random([1, 0]),
            'invoice_no' => $this->faker->numerify('Ni-######'),
            'type' => Arr::random(PaymentTypeEnum::values()),
            'tab' => Arr::random(PaymentTabsEnum::values()),
            'created_by' => User::whereHas('roles', function ($query) {
                $query->whereNotIn('name', [RolesEnum::CLIENT->value, RolesEnum::SUPER_ADMIN->value]);
            })
                ->inRandomOrder()
                ->first()
                ->id,
        ];
    }

    public function configure()
    {
        return $this->afterMaking(function (Payment $payment) {
            if ($payment->tab == PaymentTabsEnum::BALANCE_SHEET->value) {
                $payment->supplier_id = Supplier::inRandomOrder()
                    ->first()
                    ->id;
                $payment->category = Arr::random(BalanceSheetCategoryEnum::values());
                $payment->customer_id = User::role(RolesEnum::CLIENT->value)
                    ->inRandomOrder()
                    ->first()
                    ->id;
            }

            if ($payment->tab == PaymentTabsEnum::PAYMENTS->value) {
                $payment->customer_id = User::role(RolesEnum::CLIENT->value)
                    ->inRandomOrder()
                    ->first()
                    ->id;
            }
        });
    }
}

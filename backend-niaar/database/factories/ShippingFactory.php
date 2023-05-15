<?php

namespace Database\Factories;

use App\Enums\RolesEnum;
use App\Enums\ShippingStatusEnum;
use App\Models\Shipping;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Shipping>
 */
class ShippingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $aToZ = range('A', 'Z');

        return [
            'captain_info' => 'Captain' . $this->faker->name('male'),
            'agent_name' => 'agent-' . Arr::random($aToZ),
            'agent_no' => $this->faker->numerify('########'),
            'sign' => implode('', Arr::random(range('A', 'Z'), 4)),
            'handed_over_date' => now()->subDays(rand(1, 30))->format('Y-m-d'),
            'created_by' => User::role(Arr::random([
                RolesEnum::MANAGER->value,
                RolesEnum::TEAM_LEADER->value,
                RolesEnum::PROCUREMENT->value
            ]))
            ->inRandomOrder()
            ->first()
            ->id
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Shipping $shipping) {
            $shipping->setStatus(Arr::random(ShippingStatusEnum::array()));
        });
    }
}

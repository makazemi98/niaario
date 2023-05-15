<?php

namespace Database\Factories;

use App\Enums\RolesEnum;
use App\Models\Inquiry;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Box>
 */
class BoxFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'client_id' => User::role(RolesEnum::CLIENT->value)->inRandomOrder()->first()->id,
            'inquiry_id' => Inquiry::inRandomOrder()->first()->id,
            'sign' => implode('', Arr::random(range('A', 'Z'), 4)),
            'content' => $this->faker->words(3, true),
            'height' => rand(5, 30),
            'length' => rand(5, 30),
            'width' => rand(5, 30),
            'volume' => $this->faker->randomFloat($nbMaxDecimals = 2, $min = 1, $max = 100),
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
}

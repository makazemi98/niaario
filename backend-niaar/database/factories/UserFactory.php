<?php

namespace Database\Factories;

use App\Enums\GenderEnum;
use App\Enums\RolesEnum;
use App\Models\User;
use App\Notifications\UserWasMentioned;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $gender = Arr::random(GenderEnum::values());
        return [
            'first_name' => fake()->firstName($gender),
            'last_name' => fake()->lastName(),
            'gender' => $gender,
            'abbreviation' => implode(
                '',
                $this->faker->randomElements(
                    range('A', 'Z'),
                    rand(3, 6)
                )
            ),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified()
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function setRenewal()
    {
        return $this->state(fn (array $attributes) => [
            'renewal_date' => now()->addDays(rand(15, 20)),
        ]);
    }

    public function client()
    {
        return $this->afterCreating(function (User $user) {
            $user->confidential()->create([
                'body' => $this->faker->sentence,
                'created_by' => 1
            ]);

            $user->assignRole(RolesEnum::CLIENT->value);
        });
    }
}

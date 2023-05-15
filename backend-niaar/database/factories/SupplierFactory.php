<?php

namespace Database\Factories;

use App\Enums\RolesEnum;
use App\Models\Brand;
use App\Models\ProductCategory;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Supplier>
 */
class SupplierFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'company' => $this->faker->company(),
            'email' => $this->faker->unique()->email(),
            'phone' => $this->faker->phoneNumber(),
            'mobile' => $this->faker->phoneNumber(),
            'website' => $this->faker->url(),
            'country' => $this->faker->century(),
            'created_by' => Arr::random(range(2, 15))
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function(Supplier $supplier) {
            $supplier->customers()
                ->attach(
                    User::role(RolesEnum::CLIENT->value)
                        ->inRandomOrder()
                        ->take(rand(1, 3))
                        ->pluck('id')
                        ->toArray()
                );

            $supplier->personsInCharge()
                ->attach(
                    User::role(RolesEnum::PROCUREMENT->value)
                        ->inRandomOrder()
                        ->take(rand(1, 3))
                        ->pluck('id')
                        ->toArray()
                );

            $supplier->supplyingBrands()->attach(Brand::pluck('id')->toArray());

            $supplier->productCategories()->attach(ProductCategory::pluck('id')->toArray());
        });
    }
}

<?php

namespace Database\Seeders;

use App\Enums\RolesEnum;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $superAdmin = User::create([
            'title' => 'Mr',
            'first_name' => "Farshid",
            'last_name' => "Sohrabiani",
            'gender' => "male",
            'email' => "fsohrabi047@gmail.com",
            'email_verified_at' => now(),
            'password' => bcrypt('102030405060'),
            'remember_token' => Str::random(10),
        ]);

        $superAdmin->syncRoles(RolesEnum::SUPER_ADMIN->value);

        $superAdmin = User::create([
            'title' => 'Ms',
            'first_name' => "Mahsa",
            'last_name' => "Joudarzi",
            'gender' => "female",
            'email' => "mahsa.joudarzi@gmail.com",
            'email_verified_at' => now(),
            'password' => bcrypt('102030405060'),
            'remember_token' => Str::random(10),
        ]);

        $superAdmin->syncRoles(RolesEnum::SUPER_ADMIN->value);

        $superAdmin = User::create([
            'title' => 'Mr',
            'first_name' => "Pouriya",
            'last_name' => "Najafabadi",
            'gender' => "male",
            'email' => "pouriya.n@gmail.com",
            'email_verified_at' => now(),
            'password' => bcrypt('102030405060'),
            'remember_token' => Str::random(10),
        ]);

        $superAdmin->syncRoles(RolesEnum::MANAGER->value);
    }
}

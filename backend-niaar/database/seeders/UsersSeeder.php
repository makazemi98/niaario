<?php

namespace Database\Seeders;

use App\Enums\RolesEnum;
use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->count(50)->client()->create();

        foreach (RolesEnum::cases() as $role) {
            User::factory()->count(rand(5, 15))->setRenewal()->create()->each(function ($user) use ($role) {
               return $user->assignRole($role->value);
            });
        }

//        User::factory()->count(12)->create()->each(function ($user))
//        User::where('id')->syncRoles(RolesEnum::MANAGER->value);
//        User::find(4)->syncRoles(RolesEnum::TEAM_LEADER->value);
//        User::find(5)->syncRoles(RolesEnum::PROCUREMENT->value);
//        User::find(6)->syncRoles(RolesEnum::CLIENT->value);


    }
}

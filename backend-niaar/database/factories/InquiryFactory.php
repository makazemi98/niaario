<?php

namespace Database\Factories;

use App\Enums\InquiryStatusesEnum;
use App\Enums\RolesEnum;
use App\Models\Inquiry;
use App\Models\User;
use App\Notifications\UserWasMentioned;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Inquiry>
 */
class InquiryFactory extends Factory
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
            'title' => $this->faker->words(rand(3, 5), true),
            'description' => $this->faker->sentences(20, true),
            'remark' => $this->faker->sentences(10, true),
            'created_by' => User::role(RolesEnum::MANAGER->value)->inRandomOrder()->first()->id,
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Inquiry $inquiry) {
            $inquiry->update([
                'assigned_to' => User::whereHas('roles', function ($query) {
                        $query->where('name', '!=', RolesEnum::CLIENT->value);
                    })
                    ->inRandomOrder()
                    ->first()
                    ->id
            ]);

            $this->storeQuestionnaire($inquiry);

            $statuses = InquiryStatusesEnum::cases();
            foreach (Arr::random($statuses, rand(1, count($statuses))) as $status) {
                $inquiry->setStatus($status->value, $this->faker->sentence(6, false));
                $inquiry->latestStatus()->update(['created_by' => $inquiry->created_by]);
                $inquiry->comments()->create([
                    'body' => $this->faker->sentence(),
                    'created_by' => $inquiry->created_by
                ]);
            }

            $inquiry->assignedTo->notify(new UserWasMentioned($inquiry));

            $inquiry->client->notify(new UserWasMentioned($inquiry));

            $this->storeQuestionnaire($inquiry);
        });
    }

    protected function storeQuestionnaire($inquiry)
    {
        return DB::table('question_response_time')
            ->insert([
                'questioner' => Arr::random([$inquiry->clinet_id, $inquiry->assigned_to]) ?? 2,
                'asked_in' => rand(1, 500),
                'inquiry_id' => $inquiry->id,
                'questioned' => Arr::random([$inquiry->clinet_id, $inquiry->assigned_to]) ?? 2,
                'replayed_in' => rand(1, 500),
                'created_at' => now()->subDays(rand(1, 3))->subMinutes(rand(10, 45)),
                'updated_at' => now()->subDays(rand(1, 2))->addMinutes(rand(10, 45)),
            ]);
    }
}

<?php

namespace Database\Factories;

use App\Services\Seeding\LoremPicsumService;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     *
     * @throws \DateMalformedStringException
     */
    public function definition(): array
    {
        $startDate = \DateTimeImmutable::createFromMutable(fake()->dateTimeBetween('-4 year', '+14 month'));
        $days = fake()->numberBetween(1, 4);
        $endDate = $startDate->modify("+{$days} day");

        $name = fake()->words(fake()->numberBetween(1, 3), true);

        $users = \App\Models\User::all();

        $picture = app(LoremPicsumService::class)->getPicture();

        return [
            'name' => $name,
            'user_id' => $users->random()->id,
            'description' => fake()->paragraphs(fake()->numberBetween(1, 3), true),
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d'),
            'location' => fake()->city(),
            'picture' => $picture,
        ];
    }
}

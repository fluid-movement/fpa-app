<?php

namespace Database\Factories;

use App\Models\Player;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class PlayerFactory extends Factory
{
    protected $model = Player::class;

    protected static int $memberNumber = 20;

    public function definition(): array
    {
        $yearOfBirth = $this->faker->numberBetween(1970, 2005);
        $freestylingSince = $yearOfBirth + $this->faker->numberBetween(14, 20);
        $firstCompetition = $freestylingSince + $this->faker->numberBetween(1, 5);

        return [
            'user_id' => $this->faker->randomNumber(),
            'name' => $this->faker->firstName(),
            'surname' => $this->faker->lastName(),
            'email' => $this->faker->email(),
            'year_of_birth' => $yearOfBirth,
            'gender' => $this->faker->randomElement(['male', 'female', 'other']),
            'country' => $this->faker->country(),
            'city' => $this->faker->city(),
            'freestyling_since' => $freestylingSince,
            'first_competition' => $firstCompetition,
            'member_number' => self::$memberNumber++,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}

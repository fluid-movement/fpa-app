<?php

namespace Database\Seeders;

use App\Enums\AssetType;
use App\Enums\EventUserStatus;
use App\Enums\MembershipType;
use App\Models\Event;
use App\Models\Player;
use App\Models\User;
use App\Services\AssetManagerService;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function __construct(private AssetManagerService $assetManagerService) {}

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->assetManagerService->deleteAll(AssetType::Banner);
        $this->assetManagerService->deleteAll(AssetType::Icon);

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $userCount = 60;
        User::factory($userCount)->create();
        Event::factory(80)->create();
        Player::factory(200)->create();

        foreach (Player::all() as $player) {
            // create ActiveYears
            $years = range(2015, 2025);
            foreach ($years as $year) {
                if (round(rand(0, 1))) {
                    $player->activeYears()->create(
                        [
                            'year' => $year,
                            'membership_type' => $this->getWeightedRandomMembership(),
                        ]
                    );
                }
            }
        }

        // create "attending" relationships
        $users = User::all();
        foreach (Event::all() as $event) {
            $organizer = User::find($event->user_id);
            $event->users()->attach($organizer, ['status' => EventUserStatus::Organizing]);

            $attendingUsers = $users->random(random_int(1, $userCount))->pluck('id')->toArray();
            $event->users()->attach(
                array_filter($attendingUsers, fn ($id) => $id !== $organizer->id),
                ['status' => EventUserStatus::Attending]
            );
        }
    }

    private function getWeightedRandomMembership(): MembershipType
    {

        $weights = [
            [
                MembershipType::Standard,
                70,
            ],
            [
                MembershipType::Platinum,
                10,
            ],
            [
                MembershipType::Juniors,
                10,
            ],
            [
                MembershipType::FirstTimer,
                5,
            ],
            [
                MembershipType::Group,
                5,
            ],
        ];

        $totalWeight = array_sum(array_map(fn ($entry) => $entry[1], $weights));
        $random = rand(1, $totalWeight);

        foreach ($weights as [$membershipType, $weight]) {
            if ($random <= $weight) {
                return $membershipType;
            }
            $random -= $weight;
        }

        // Just in case something goes wrong, return a default
        return MembershipType::Standard;
    }
}

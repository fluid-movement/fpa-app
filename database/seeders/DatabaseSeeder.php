<?php

namespace Database\Seeders;

use App\Core\Enum\AssetType;
use App\Core\Enum\EventUserStatus;
use App\Core\Service\AssetManagerService;
use App\Models\Event;
use App\Models\User;
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
        $this->assetManagerService->deleteAll(AssetType::BANNER);
        $this->assetManagerService->deleteAll(AssetType::ICON);

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $userCount = 40;
        User::factory($userCount)->create();
        Event::factory(80)->create();

        // create "interested" and "attending" relationships
        $users = User::all();
        foreach (Event::all() as $event) {
            $organizer = User::find($event->user_id);
            $event->users()->attach($organizer, ['status' => EventUserStatus::ORGANIZING]);

            $interestedUsers = $users->random(random_int(1, $userCount))->pluck('id')->toArray();
            $event->users()->attach(
                array_filter($interestedUsers, fn($id) => $id !== $organizer->id),
                ['status' => EventUserStatus::INTERESTED]
            );

            $attendingUsers = $users->random(random_int(1, $userCount))->pluck('id')->toArray();
            $event->users()->attach(
                array_filter($attendingUsers, fn($id) => $id !== $organizer->id),
                ['status' => EventUserStatus::ATTENDING]
            );
        }
    }
}

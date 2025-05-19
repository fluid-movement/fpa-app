<?php

namespace App\Observers;

use App\Enums\AssetType;
use App\Models\Event;
use App\Services\AssetManagerService;

readonly class EventObserver
{
    public function __construct(private AssetManagerService $assetManagerService) {}

    public function deleted(Event $event): void
    {
        // Delete the event's picture
        if ($event->picture) {
            $this->assetManagerService->delete(AssetType::Picture, $event->picture);
        }
    }
}

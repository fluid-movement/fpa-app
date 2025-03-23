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
        // Delete the event's banner and icon
        if ($event->banner) {
            $this->assetManagerService->delete(AssetType::BANNER, $event->banner);
        }
        if ($event->icon) {
            $this->assetManagerService->delete(AssetType::ICON, $event->icon);
        }
    }
}

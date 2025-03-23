<?php

namespace App\Enums;

enum AdminTabs: string
{
    // The order of cases here will determine the order of tabs in the UI
    case ATTENDING = 'attending';
    case SCHEDULE = 'schedule';
    case DIVISIONS = 'divisions';
    case ORGANIZERS = 'organizers';

    public static function getTabs(): array
    {
        return AdminTabs::cases();
    }

    public function getTitle(): string
    {
        return ucfirst($this->value);
    }

    public function getComponent(): string
    {
        return 'events.admin._'.$this->value;
    }
}

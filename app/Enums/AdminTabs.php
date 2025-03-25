<?php

namespace App\Enums;

enum AdminTabs: string
{
    // The order of cases here will determine the order of tabs in the UI
    case Attending = 'attending';
    case Schedule = 'schedule';
    case Divisions = 'divisions';
    case Organizers = 'organizers';

    public static function getTabs(): array
    {
        return AdminTabs::cases();
    }

    public function getTitle(): string
    {
        return $this->name;
    }

    public function getComponent(): string
    {
        return 'events.admin._'.$this->value;
    }
}

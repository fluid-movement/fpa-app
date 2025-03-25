<?php

namespace App\Enums;

enum MembershipType
{
    case Standard;
    case Platinum;
    case Juniors;
    case FirstTimer;
    case Group;

    public function getDisplayName(): string
    {
        // Convert camel case to title case
        return preg_replace('/(?<!^)([A-Z])/', ' $1', $this->name);
    }
}

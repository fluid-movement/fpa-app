<?php

namespace App\Enums\Division;

enum DivisionType: string
{
    case OPEN_PAIRS = 'Open Pairs';
    case MIXED_PAIRS = 'Mixed Pairs';
    case WOMEN_PAIRS = 'Women Pairs';
    case OPEN_COOP = 'Open Coop';
    case INDIVIDUAL = 'Individual';
    case OTHER = 'Other';

    public static function getDefault(): DivisionType
    {
        return DivisionType::OPEN_PAIRS;
    }

    public function getDisplayName(): string
    {
        return $this->value;
    }

    public function hasFixedPlayerCount(): bool
    {
        return true;
    }

    public function getPlayerCount(): int
    {
        return match ($this) {
            DivisionType::OPEN_PAIRS, DivisionType::MIXED_PAIRS, DivisionType::WOMEN_PAIRS => 2,
            DivisionType::OPEN_COOP => 3,
            DivisionType::INDIVIDUAL => 1,
            default => 2,
        };
    }
}

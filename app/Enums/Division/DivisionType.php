<?php

namespace App\Enums\Division;

enum DivisionType: string
{
    case OpenPairs = 'Open Pairs';
    case MixedPairs = 'Mixed Pairs';
    case WomenPairs = 'Women Pairs';
    case OpenCoop = 'Open Coop';
    case Individual = 'Individual';
    case Other = 'Other';

    public static function getDefault(): DivisionType
    {
        return DivisionType::OpenPairs;
    }

    public function getDisplayName(): string
    {
        return $this->value;
    }

    public function getPlayerCount(): int
    {
        return match ($this) {
            DivisionType::OpenPairs, DivisionType::MixedPairs, DivisionType::WomenPairs => 2,
            DivisionType::OpenCoop => 3,
            DivisionType::Individual => 1,
            default => 2,
        };
    }
}

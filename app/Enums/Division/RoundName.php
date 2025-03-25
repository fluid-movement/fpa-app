<?php

namespace App\Enums\Division;

enum RoundName: string
{
    case Finals = 'Finals';
    case SemiFinals = 'Semifinals';
    case QuarterFinals = 'Quarterfinals';
    case RoundOf16 = 'Round of 16';
    case RoundOf32 = 'Round of 32';
    case RoundOf64 = 'Round of 64';

    case Default = 'Round';

    public function getName(): string
    {
        return $this->value;
    }

    public static function getRound(int $roundNumber): RoundName
    {
        return match ($roundNumber) {
            1 => self::Finals,
            2 => self::SemiFinals,
            3 => self::QuarterFinals,
            4 => self::RoundOf16,
            5 => self::RoundOf32,
            6 => self::RoundOf64,
            default => self::Default,
        };
    }
}

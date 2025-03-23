<?php

namespace App\Enums\Division;

enum RoundName: string
{
    case FINALS = 'Finals';
    case SEMIFINALS = 'Semifinals';
    case QUARTERFINALS = 'Quarterfinals';
    case ROUND_OF_16 = 'Round of 16';
    case ROUND_OF_32 = 'Round of 32';
    case ROUND_OF_64 = 'Round of 64';

    case DEFAULT = 'Round';

    public function getName(): string
    {
        return $this->value;
    }

    public static function getRoundCount(int $playerCount, int $poolSize, int $advanceCount): int
    {
        $rounds = 0;
        while ($playerCount > $poolSize) {
            $rounds++;
            $pools = (int) ceil($playerCount / $poolSize);
            $playerCount = $pools * $advanceCount;
        }

        return $rounds + 1; // Including the final round
    }

    public static function getRound(int $roundNumber): RoundName
    {
        return match ($roundNumber) {
            1 => self::FINALS,
            2 => self::SEMIFINALS,
            3 => self::QUARTERFINALS,
            4 => self::ROUND_OF_16,
            5 => self::ROUND_OF_32,
            6 => self::ROUND_OF_64,
            default => self::DEFAULT,
        };
    }
}

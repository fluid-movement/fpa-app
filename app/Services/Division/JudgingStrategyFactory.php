<?php

namespace App\Services\Division;

use App\Enums\Division\JudgingSystemType;
use App\Models\Result;
use InvalidArgumentException;

class JudgingStrategyFactory
{
    public static function make(JudgingSystemType $type, Result $result): JudgingStrategyInterface
    {
        return match ($type) {
            JudgingSystemType::Simple => new SimpleJudgingStrategy($result),
            JudgingSystemType::Vibes => new VibeJudgingStrategy($result),
            default => throw new InvalidArgumentException("Invalid judging type: {$type->value}"),
        };
    }
}

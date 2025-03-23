<?php

namespace App\Services\Division;

use App\Models\Result;

class VibeJudgingStrategy implements JudgingStrategyInterface
{
    protected Result $result;

    public function __construct(Result $result)
    {
        $this->result = $result;
    }

    public function calculateScore(): float
    {
        $up = $this->result->data['thumbs_up'] ?? 0;
        $down = $this->result->data['thumbs_down'] ?? 0;

        if (($up + $down) === 0) {
            return 0;
        }

        // Simple percentage-based calculation
        return ($up / ($up + $down)) * 9; // normalize to a 1-9 scale
    }
}

<?php

namespace App\Services\Division;

use App\Models\Result;

class SimpleJudgingStrategy implements JudgingStrategyInterface
{
    protected Result $result;

    public function __construct(Result $result)
    {
        $this->result = $result;
    }

    public function calculateScore(): float
    {
        // Assumes a single numeric value
        return (float) $this->result->data;
    }
}

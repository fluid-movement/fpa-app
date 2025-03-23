<?php

namespace App\Services\Division;

interface JudgingStrategyInterface
{
    public function calculateScore(): float;
}

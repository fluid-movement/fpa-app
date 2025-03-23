<?php

namespace App\Enums\Division;

enum DivisionSetupSteps: string
{
    case TEAMS = 'teams';
    case ROUNDS_AND_POOLS = 'rounds';

    public function getComponent(): string
    {
        return 'events.division._'.$this->value;
    }

    public function getTitle(): string
    {
        return match ($this) {
            DivisionSetupSteps::TEAMS => '1) Teams',
            DivisionSetupSteps::ROUNDS_AND_POOLS => '2) Create first round',
        };
    }

    public function getDescription(): string
    {
        return match ($this) {
            DivisionSetupSteps::TEAMS => 'Add teams to the division',
            DivisionSetupSteps::ROUNDS_AND_POOLS => 'Set up rounds and pools',
        };
    }
}

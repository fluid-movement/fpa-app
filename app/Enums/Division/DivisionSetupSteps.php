<?php

namespace App\Enums\Division;

enum DivisionSetupSteps: string
{
    case Teams = 'teams';
    case Rounds = 'rounds';

    public function getComponent(): string
    {
        return 'events.division._'.$this->value;
    }

    public function getTitle(): string
    {
        return match ($this) {
            DivisionSetupSteps::Teams => '1) Teams',
            DivisionSetupSteps::Rounds => '2) Create first round',
        };
    }

    public function getDescription(): string
    {
        return match ($this) {
            DivisionSetupSteps::Teams => 'Add teams to the division',
            DivisionSetupSteps::Rounds => 'Set up rounds and pools',
        };
    }
}

<?php

namespace App\Livewire\Forms;

use App\Models\Player;
use Livewire\Attributes\Validate;
use Livewire\Form;

class PlayerForm extends Form
{
    public Player $player;

    #[Validate('required|string', message: 'Enter player first name')]
    public string $name;

    #[Validate('required|string', message: 'Enter player surname')]
    public string $surname;

    #[Validate('string', message: 'Enter player email')]
    public string $email = '';

    #[Validate('required|integer', message: 'Enter FPA member number')]
    public ?int $member_number;
    public ?int $year_of_birth = 0;
    public ?string $gender = '';
    public ?string $country = '';
    public ?string $city = '';
    public ?int $freestyling_since;
    public ?int $first_competition;
    public ?string $notes;

    private array $fields = [
        'name',
        'surname',
        'email',
        'year_of_birth',
        'gender',
        'country',
        'city',
        'freestyling_since',
        'first_competition',
        'member_number',
        'notes'
    ];

    public function store(): Player
    {
        $this->validate();

        return Player::create($this->only($this->fields));
    }

    public function update(): bool
    {
        $this->validate();

        return $this->player->update($this->only($this->fields));
    }

    public function setPlayer(Player $player): void
    {
        $this->player = $player;
        foreach ($this->fields as $field) {
            $this->$field = $player->$field;
        }
    }
}

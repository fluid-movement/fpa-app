<?php

use App\Models\Division;
use App\Models\Player;
use Livewire\Volt\Component;

new class extends Component
{
    public Division $division;

    public array $team = [];

    public array $availablePlayers = [];

    public int $playersPerTeam;

    public function mount(Division $division): void
    {
        $this->division = $division;
        $this->availablePlayers = Player::all()->pluck('name', 'id')->toArray() ?? [];
        $this->playersPerTeam = $division->type->getPlayerCount();
    }

    public function addTeam(): void
    {
        $team = array_unique(array_filter($this->team));

        // Validate the number of unique selected players
        if (count($team) !== $this->playersPerTeam) {
            session()->flash('error', 'Please select '.$this->playersPerTeam.' unique players.');

            return;
        }

        // create the team and attach the players
        $this->division->teams()->create()->players()->attach($team);

        $this->team = [];

        session()->flash('success', 'Team successfully created.');
    }

    public function deleteTeam(string $teamId): void
    {
        $team = $this->division->teams()->find($teamId);

        if ($team) {
            $team->delete();
            session()->flash('success', 'Team successfully deleted.');
        }
    }
}; ?>

<div>
    <!-- Add Team -->
    <flux:heading class="mb-4">Add Team</flux:heading>
    <div class="flex flex-col sm:flex-row gap-4">
        @foreach(range(0, $playersPerTeam - 1) as $playerIndex)
            <flux:select
                wire:model="team.{{ $playerIndex }}"
                placeholder="Select Player {{ $playerIndex + 1 }}"
                variant="listbox"
                searchable
            >
                @foreach($availablePlayers as $playerId => $playerName)
                    <flux:select.option value="{{ $playerId }}">
                        {{ $playerName }}
                    </flux:select.option>
                @endforeach
            </flux:select>
        @endforeach
        <flux:button wire:click="addTeam">Add Team</flux:button>
    </div>

    <!-- List Teams -->
    @if($division->teams)
        <flux:separator class="my-8"/>
        <flux:heading class="mb-4">Teams</flux:heading>
        <flux:table class="mb-16">
            <flux:table.columns>
                @foreach(range(1, $playersPerTeam) as $playerIndex)
                    <flux:table.column>Player {{$playerIndex}}</flux:table.column>
                @endforeach
                <flux:table.column></flux:table.column>
            </flux:table.columns>
            <flux:table.rows>
                @foreach($division->teams as $team)
                    <flux:table.row>
                        @foreach($team->players as $player)
                            <flux:table.column>{{$player->name}}</flux:table.column>
                        @endforeach
                        <flux:table.cell>
                            <flux:button size="xs" wire:click="deleteTeam('{{ $team->id }}')">Delete</flux:button>
                        </flux:table.cell>
                    </flux:table.row>
                @endforeach
            </flux:table.rows>
        </flux:table>
    @endif
</div>

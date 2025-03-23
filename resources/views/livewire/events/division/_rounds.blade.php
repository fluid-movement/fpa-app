<?php

use App\Models\Division;
use App\Services\Division\DivisionBuilder;
use Livewire\Volt\Component;

new class extends Component
{
    public Division $division;

    public array $poolSizes;

    public array $advanceOptions;

    public int $teamsPerPool;

    public int $advance;

    public function mount(Division $division): void
    {
        $this->division = $division;
        $this->teamsPerPool = $division->teams_per_pool;
        $this->advance = $division->advance_per_pool;
        $this->poolSizes = range(2, 10);
        $this->advanceOptions = range(1, 4);
    }

    public function generateFirstRound(): void
    {
        $this->division->update([
            'teams_per_pool' => $this->teamsPerPool,
            'advance_per_pool' => $this->advance,
        ]);
        (new DivisionBuilder($this->division))->generateFirstRound($this->teamsPerPool, $this->advance);
    }
}; ?>

<div class="flex flex-col gap-4">
    <flux:text>There are currently {{ $division->teams->count()}} teams in this division.</flux:text>
    <div class="flex flex-col md:flex-row gap-4">

        <flux:select wire:model.live="teamsPerPool" placeholder="Choose number of teams per pool">
            @foreach($poolSizes as $option)
                <flux:select.option value="{{$option}}">{{$option}} Teams per pool</flux:select.option>
            @endforeach
        </flux:select>
        <flux:select wire:model.live="advance" placeholder="How many will advance">
            @foreach($advanceOptions as $option)
                <flux:select.option value="{{$option}}">{{$option}} {{Str::plural('Team', $option)}} will advance
                </flux:select.option>
            @endforeach
        </flux:select>
        <flux:button wire:click="generateFirstRound" class="bg-blue-500 text-white">Create First Round</flux:button>
    </div>

    <!-- Displaying Rounds and Pools -->
    <div class="mt-6">
        @foreach($division->rounds as $round)
            <div class="mb-4">
                <flux:heading level="2" class="text-xl font-bold">Round {{ $round->name }}</flux:heading>
                <div class="ml-4">
                    @foreach($round->pools as $pool)
                        <div class="mb-2">
                            <flux:heading level="3" class="text-lg font-semibold">Pool {{ $pool->name }}</flux:heading>
                            <ul class="list-disc list-inside ml-4">
                                @foreach($pool->teams as $team)
                                    <li>{{ implode(' - ', array_map(fn($player) => $player['name'], $team->players->toArray())) }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>

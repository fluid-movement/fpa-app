<?php

use App\Models\Pool;
use Livewire\Volt\Component;

new class extends Component
{
    public Pool $pool;

    public function setScore($teamId, $score): void
    {
        $team = $this->pool->teams()->find($teamId);
        $team->data = $score;
        $team->save();
    }
}; ?>

<div>
    <flux:heading level="3" class="text-lg font-semibold mb-8">Judging Pool {{ $pool->name }}</flux:heading>
    <div class="flex flex-col gap-8">
        @foreach($pool->teams()->get() as $team)
            <div>
                <flux:modal.trigger name="{{$team->id}}">
                    <div class="flex w-full border rounded-md shadow-md md:w-auto cursor-pointer">
                        <div class="flex flex-col gap-2 p-4">
                            @foreach(array_map(fn($player) => $player['name'], $team->players->toArray()) as $player)
                                <span>{{ $player }}</span>
                            @endforeach
                        </div>
                        <flux:spacer />
                    </div>
                </flux:modal.trigger>

                <flux:modal name="{{$team->id}}" class="w-full md:w-96">
                    <div class="space-y-6">
                        <div class="grid grid-cols-3 mt-8">
                            @foreach(range(1,9) as $possibleScore)
                                <flux:button
                                    class="p-4"
                                    wire:click="setScore('{{ $team->id }}', '{{ $possibleScore }}')"
                                    variant="{{ $team->score === $possibleScore ? 'primary' : 'ghost' }}">{{ $possibleScore }}
                                </flux:button>
                            @endforeach
                        </div>
                    </div>
                </flux:modal>
            </div>
        @endforeach
    </div>
</div>

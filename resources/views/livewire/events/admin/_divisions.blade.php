<?php

use App\Enums\Division\DivisionType;
use App\Models\Event;
use Livewire\Volt\Component;

new class extends Component
{
    public Event $event;

    public DivisionType $divisionType;

    public function mount(Event $event)
    {
        $this->event = $event;
        $this->divisionType = DivisionType::getDefault();
    }

    public function createDivision(): void
    {
        $this->event->divisions()->create([
            'type' => $this->divisionType,
        ]);
    }

    public function deleteDivision(string $id): void
    {
        $this->event->divisions()->where('id', $id)->delete();
    }
}; ?>

<div>
    <flux:card>
        <flux:heading size="lg" class="mb-4">Create a new division</flux:heading>
        <div class="flex gap-4">

            <flux:select wire:model="divisionType" placeholder="Choose division...">
                @foreach(DivisionType::cases() as $type)
                    <flux:select.option>{{ $type->value }}</flux:select.option>
                @endforeach
            </flux:select>
            <flux:button wire:click="createDivision">Create Division</flux:button>
        </div>
    </flux:card>
    @if($event->divisions)
        <flux:table>
            <flux:table.columns>
                <flux:table.column>Division</flux:table.column>
                <flux:table.column></flux:table.column>
                <flux:table.column></flux:table.column>
                <flux:table.column></flux:table.column>
            </flux:table.columns>
            <flux:table.rows>
                @foreach($event->divisions as $division)
                    <flux:table.row :key="$division->id">
                        <flux:table.cell>
                            {{ $division->type }}
                        </flux:table.cell>
                        <flux:table.cell>
                            <flux:button
                                href="{{ route('events.division.edit', $division) }}"
                                icon="pencil"
                                size="xs"
                            >
                                Edit
                            </flux:button>
                        </flux:table.cell>
                        <flux:table.cell>
                            <flux:button
                                href="{{ route('events.division.run', $division) }}"
                                icon="rocket-launch"
                                size="xs"
                            >
                                Run Division!
                            </flux:button>
                        </flux:table.cell>
                        <flux:table.cell>
                            <flux:button
                                wire:click="deleteDivision('{{ $division->id }}')"
                                variant="danger"
                                icon="trash"
                                size="xs"
                            >
                                Delete
                            </flux:button>
                        </flux:table.cell>
                    </flux:table.row>
                @endforeach
            </flux:table.rows>
        </flux:table>
    @endif
</div>

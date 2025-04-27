<?php

use Carbon\Carbon;
use Livewire\Volt\Component;
use App\Models\Event;
use Illuminate\Support\Collection;

new class extends Component {
    // Set to the schedule item's id when editing; null when creating a new item
    public ?string $editingScheduleItemId = null;

    public array $scheduleEntry = [
        'start_time' => '',
        'end_time' => '',
        'name' => '',
        'location' => '',
        'description' => '',
    ];

    // Validation rules for the schedule item form
    protected array $rules = [
        'scheduleEntry.start_time' => 'required|date_format:H:i',
        'scheduleEntry.end_time' => 'required|date_format:H:i|after:scheduleEntry.start_time',
        'scheduleEntry.name' => 'required|string|max:255',
    ];

    public Collection $schedule;
    public Event $event;
    public Carbon $day;

    public function mount(Event $event, Carbon $day): void
    {
        $this->event = $event;
        $this->day = $day;
        $this->schedule = $event->schedule->filter(function ($item) use ($day) {
            return Carbon::parse($item->start_date)->isSameDay($day);
        });
    }

    public function addOrUpdateScheduleItem(string $date): void
    {
        $this->validate();

        if ($this->editingScheduleItemId) {
            // Update existing schedule item
            $item = $this->event->schedule()->find($this->editingScheduleItemId);
            $item->update([
                'start_date' => Carbon::parse($date . ' ' . $this->scheduleEntry['start_time']),
                'end_date' => Carbon::parse($date . ' ' . $this->scheduleEntry['end_time']),
                'name' => $this->scheduleEntry['name'],
                'location' => $this->scheduleEntry['location'],
                'description' => $this->scheduleEntry['description'],
            ]);

            // Update the schedule collection with the updated item
            $this->schedule = $this->schedule->map(function ($scheduleItem) use ($item) {
                return $scheduleItem->id === $item->id ? $item : $scheduleItem;
            });

            // Exit edit mode
            $this->editingScheduleItemId = null;
        } else {
            // Create new schedule item
            $entry = $this->event->schedule()->create([
                'start_date' => Carbon::parse($date . ' ' . $this->scheduleEntry['start_time']),
                'end_date' => Carbon::parse($date . ' ' . $this->scheduleEntry['end_time']),
                'name' => $this->scheduleEntry['name'],
                'location' => $this->scheduleEntry['location'],
                'description' => $this->scheduleEntry['description'],
            ]);

            $this->schedule->push($entry);
            $this->schedule = $this->schedule->sortBy('start_date');
        }

        $this->resetScheduleEntry();
    }

    public function deleteScheduleItem(string $id): void
    {
        $this->event->schedule()->find($id)->delete();
        $this->schedule = $this->schedule->filter(function ($item) use ($id) {
            return $item->id !== $id;
        });
    }

    public function editScheduleItem(string $id): void
    {
        $item = $this->event->schedule()->find($id);
        if ($item) {
            $this->editingScheduleItemId = $id;
            $this->scheduleEntry = [
                'start_time' => Carbon::parse($item->start_date)->format('H:i'),
                'end_time' => Carbon::parse($item->end_date)->format('H:i'),
                'name' => $item->name,
                'location' => $item->location,
                'description' => $item->description,
            ];
        }
    }

    private function resetScheduleEntry(): void
    {
        $this->scheduleEntry = [
            'start_time' => '',
            'end_time' => '',
            'name' => '',
            'location' => '',
            'description' => '',
        ];
    }
}; ?>

<div>
    <form wire:submit.prevent="addOrUpdateScheduleItem('{{ $day->format('Y-m-d') }}')">
        <flux:card class="flex flex-col gap-4 mb-8" wire:key="{{ md5($day->format('Y-m-d')) }}">
            <div class="flex gap-4">
                <flux:input
                    label="{{ __('Start Time') }}"
                    type="time"
                    wire:model="scheduleEntry.start_time"
                />
                <flux:input
                    label="{{ __('End Time') }}"
                    type="time"
                    wire:model="scheduleEntry.end_time"
                />
            </div>
            <flux:input
                label="{{ __('Short description') }}"
                placeholder="What is happening here?"
                type="text"
                wire:model="scheduleEntry.name"
            />
            <flux:input
                label="{{ __('Location') }}"
                type="text"
                wire:model="scheduleEntry.location"
            />
            <flux:textarea
                label="{{ __('Description') }}"
                placeholder="Optional, write any additional details here"
                wire:model="scheduleEntry.description"
            />
            <flux:button type="submit" class="w-fit">
                @if($editingScheduleItemId)
                    Update Item
                @else
                    Add Item
                @endif
            </flux:button>
        </flux:card>
    </form>

    @foreach($schedule as $item)
        <div class="mb-8">
            <x-events.schedule-item :item="$item"/>
            <flux:button :disabled="$editingScheduleItemId === $item->id"
                         icon="pencil-square"
                         size="sm"
                         wire:click="editScheduleItem('{{ $item->id }}')"
                         class="mr-4">
                Edit
            </flux:button>
            <flux:button :disabled="$editingScheduleItemId === $item->id"
                         icon="trash"
                         size="sm"
                         wire:click="deleteScheduleItem('{{ $item->id }}')"
                         class="mt-4">
                Delete
            </flux:button>
        </div>
    @endforeach
</div>

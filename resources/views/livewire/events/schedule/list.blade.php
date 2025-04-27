<?php

use Livewire\Volt\Component;

new class extends Component {
    public array $schedule = [];

    public function mount(\App\Models\Event $event): void
    {
        foreach ($event->schedule as $schedule) {
            $day = strtoupper($schedule->start_date->format('l')) . ', ' . $schedule->start_date->format('F j');
            $this->schedule[$day][] = $schedule;
        }
    }
}; ?>

<div class="flex flex-col gap-8">
    @foreach($schedule as $day => $items)
        <flux:card>
            <flux:heading size="lg" class="mb-4">{{$day}}</flux:heading>
            <div class="flex flex-col gap-4">
                @foreach($items as $item)
                    <x-events.schedule-item :$item/>
                @endforeach
            </div>
        </flux:card>
    @endforeach
</div>

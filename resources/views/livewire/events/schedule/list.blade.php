<?php

use Livewire\Volt\Component;

new class extends Component {
    public array $schedule = [];

    public function mount(\App\Models\Event $event)
    {
        foreach ($event->schedule as $schedule) {
            $this->schedule[$schedule->start_date->format('l, F j')][] = $schedule;
        }
    }
}; ?>

<div class="flex flex-col gap-8">
    @foreach($schedule as $day => $items)
        <flux:card>
            <flux:heading size="lg" class="mb-4">{{$day}}</flux:heading>
            <div class="flex flex-col gap-4 ml-2 lg:ml-8">
                @foreach($items as $item)
                    <x-events.schedule-item :item="$item"/>
                @endforeach
            </div>
        </flux:card>
    @endforeach
</div>

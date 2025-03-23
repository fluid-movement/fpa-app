<?php

use App\Models\Event;
use Livewire\Volt\Component;

new class extends Component
{
    public Event $event;
}; ?>

<div>
    <flux:button class="mb-8" href="{{route('events.schedule.edit', $event)}}">
        Edit Schedule
    </flux:button>
    <livewire:events.schedule.list :event="$event"/>
</div>

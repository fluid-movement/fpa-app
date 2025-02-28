<?php

use Illuminate\Support\Collection;
use Livewire\Volt\Component;

new class extends Component {
    public Collection $events;

    public function mount()
    {
        $this->events = auth()->user()->events()->get();
    }
}; ?>

<div class="flex flex-col gap-4">
    @if($events->isEmpty())
        <div class="flex items-center justify-center h-[30vh]">
            <flux:heading size="lg">
                {{ __('You are currently not organizing any events.') }}
            </flux:heading>
        </div>
        <flux:button class="self-center w-1/2" icon="plus" href="{{ route('events.create') }}" variant="primary">
            {{ __('Create Event') }}
        </flux:button>
    @endif
    @foreach($events as $event)
        <x-events.card :event="$event"/>
    @endforeach
</div>

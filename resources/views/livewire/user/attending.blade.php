<?php

use Illuminate\Support\Collection;
use Livewire\Volt\Component;

new #[\Livewire\Attributes\Title('Attending Events')] class extends Component {
    public Collection $events;

    public function mount()
    {
        $this->events = auth()->user()->attendingEvents()->get();
    }
}; ?>

<div class="flex flex-col gap-4">
    @if($events->isEmpty())
        <div class="flex items-center justify-center h-[50vh]">
            <p class="text-lg text-neutral-500 dark:text-neutral-400">
                {{ __('You are not attending any events.') }}
            </p>
        </div>
    @endif
    @foreach($events as $event)
        <x-events.card :event="$event"/>
    @endforeach
</div>

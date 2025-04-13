<?php

use Livewire\Volt\Component;
use App\Models\Event;

new class extends Component {
    public Event $heroEvent;
    public \Illuminate\Support\Collection $events;

    public function mount()
    {
        $events = Event::query()
            ->where('start_date', '>=', now())
            ->orderBy('start_date')
            ->limit(5)
            ->get();

        $this->heroEvent = $events->shift();
        $this->events = $events;
    }
}; ?>

<x-slot name="breadcrumbs">
    {{ Breadcrumbs::render('home') }}
</x-slot>

<div>
    <x-events.hero-card :event="$heroEvent"/>
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4 mt-8">
        @foreach($events as $event)
            <x-events.card :event="$event"/>
        @endforeach
    </div>
</div>

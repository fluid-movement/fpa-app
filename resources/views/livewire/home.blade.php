<?php

use Illuminate\Support\Collection;
use Livewire\Volt\Component;
use App\Models\Event;

new class extends Component {
    public Event $heroEvent;
    public Collection $events;

    public function mount(): void
    {
        $events = Event::query()
            ->where('start_date', '>=', now())
            ->orderBy('start_date')
            ->limit(5)
            ->get();

        if ($events->count()) {
            $this->heroEvent = $events->shift();
        }

        $this->events = $events;
    }
}; ?>

<div>
    @if($heroEvent)
        <x-events.hero-card :event="$heroEvent"/>
    @endif
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-8">
        @foreach($events as $event)
            <x-events.card :event="$event"/>
        @endforeach
    </div>
</div>

<?php

use App\Models\Event;
use Illuminate\Support\Collection;
use Livewire\Volt\Component;

new #[\Livewire\Attributes\Title('Attending Events')] class extends Component
{
    /** @var Collection<Event> */
    public Collection $events;

    public ?Event $nextEvent;

    public function mount(): void
    {
        $this->events = auth()->user()->attendingEvents()->get();
        if (count($this->events)) {
            $this->nextEvent = $this->events->shift();
        }
    }

    #[\Livewire\Attributes\Computed]
    public function daysUntilNextEvent(): int
    {
        return $this->nextEvent?->start_date->diffInDays(now(), true) ?? 0;
    }
}; ?>

<div class="flex flex-col gap-4">
    @if(!$this->daysUntilNextEvent)
        <div class="flex items-center justify-center h-[50vh]">
            <p class="text-lg text-neutral-400">
                {{ __('You are not attending any events.') }}
            </p>
        </div>
    @else
        <flux:card class="flex flex-col gap-2">
            <flux:heading>Your next event is {{$nextEvent->name}}, in <span class="text-2xl">{{ $this->daysUntilNextEvent }}</span> days!</flux:heading>
            <flux:text>
                When: {{ $nextEvent->start_date->format('jS F Y') }}
            </flux:text>
            <flux:text>
                @if($nextEvent->location)
                    Where: {{ $nextEvent->location }}
                @endif
            </flux:text>
            <flux:button class="mt-4" href="{{ route('events.show', $nextEvent) }}">View Event Page</flux:button>
        </flux:card>
        @if(count($events))
            <flux:heading class="mt-8">More upcoming events:</flux:heading>
            @foreach($events as $event)
                <flux:text class="flex gap-4 items-center">
                    <div>{{ $event->start_date->format('jS F') }}</div>
                    <div>{{$event->name}}</div>
                    <flux:button
                        href="{{ route('events.show', $event) }}"
                        size="xs"
                        icon="arrow-right" />
                </flux:text>
                @if(!$loop->last)
                    <flux:separator />
                @endif

            @endforeach
        @endif
    @endif
</div>

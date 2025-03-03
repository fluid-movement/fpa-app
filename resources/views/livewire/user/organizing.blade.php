<?php

use Illuminate\Support\Collection;
use Livewire\Volt\Component;

new class extends Component {
    public Collection $events;
    public Collection $pastEvents;

    public function mount(): void
    {
        $this->events = auth()->user()->organizingEvents()->get();
        $this->pastEvents = auth()->user()->organizedEvents()->get();
    }
}; ?>


<flux:tab.group>
    <flux:tabs wire:model="tab">
        <flux:tab name="upcoming">Upcoming Events</flux:tab>
        <flux:tab name="past">Past Events</flux:tab>
    </flux:tabs>
    <flux:tab.panel class="flex flex-col gap-4" name="upcoming">
        @if($events->isEmpty())
            <div class="flex items-center justify-center h-[30vh]">
                <flux:heading size="lg">
                    {{ __('You are currently not organizing any events.') }}
                </flux:heading>
            </div>
            <flux:button class="self-center w-1/2" icon="plus" href="{{ route('events.create') }}">
                {{ __('Create Event') }}
            </flux:button>
        @else
            <flux:heading size="xl">
                {{ __('Events You Are Organizing') }}
            </flux:heading>
        @endif
        @foreach($events as $event)
            <flux:card wire:key="{{$event->id}}">
                <div class="flex flex-wrap gap-8">
                    <div class="grow flex flex-col gap-1 justify-between mb-4 text-sm">
                        <flux:heading class="mb-4" size="lg">{{$event->name}}</flux:heading>
                        <p class="flex gap-2 items-center text-slate-500 dark:text-white">
                            <flux:icon name="calendar-days"/>{{ $event->date_range_full }}
                        </p>
                        <p class="flex gap-2 items-center text-slate-500 dark:text-white">
                            <flux:icon name="map-pin"/>{{ $event->location }}
                        </p>
                        <p class="flex gap-2 items-center text-slate-500 dark:text-white">
                            <flux:icon
                                name="user-group"/>{{ __(':count attending', ['count' => $event->attending->count()]) }}
                        </p>
                        <p class="flex gap-2 items-center text-slate-500 dark:text-white">
                            <flux:icon
                                name="question-mark-circle"/>{{ __(':count interested', ['count' => $event->interested->count()]) }}
                        </p>
                    </div>
                    <div class="flex flex-col flex-wrap gap-4">
                        <flux:button icon="magnifying-glass" wire:navigate
                                     href="{{ route('events.show', $event) }}">
                            {{ __('View Event') }}
                        </flux:button>
                        <flux:button icon="pencil-square" wire:navigate href="{{ route('events.edit', $event) }}">
                            {{ __('Edit Event Details') }}
                        </flux:button>
                        <flux:button icon="book-open" wire:navigate href="{{ route('events.admin', $event) }}">
                            {{ __('Organize Event') }}
                        </flux:button>
                    </div>
                </div>
            </flux:card>
        @endforeach
    </flux:tab.panel>
    <flux:tab.panel class="flex flex-col gap-4" name="past">
        @if($pastEvents->isEmpty())
            <div class="flex items-center justify-center h-[30vh]">
                <flux:heading size="lg">
                    {{ __('No past events.') }}
                </flux:heading>
            </div>
        @else
            <flux:heading size="xl">
                {{ __('Events You Have Organized') }}
            </flux:heading>
        @endif
        @foreach($pastEvents as $event)
            <flux:card wire:key="{{$event->id}}">
                <div class="flex flex-wrap gap-8">
                    <div class="grow flex flex-col gap-1 justify-between mb-4 text-sm">
                        <flux:heading class="mb-4" size="lg">{{$event->name}}</flux:heading>
                        <p class="flex gap-2 items-center text-slate-500 dark:text-white">
                            <flux:icon name="calendar-days"/>{{ $event->date_range_full }}
                        </p>
                        <p class="flex gap-2 items-center text-slate-500 dark:text-white">
                            <flux:icon name="map-pin"/>{{ $event->location }}
                        </p>
                        <p class="flex gap-2 items-center text-slate-500 dark:text-white">
                            <flux:icon name="user-group"/>{{ $event->attending->count() }} attending
                        </p>
                        <p class="flex gap-2 items-center text-slate-500 dark:text-white">
                            <flux:icon name="question-mark-circle"/>{{ $event->interested->count() }} interested
                        </p>
                    </div>
                    <div class="flex flex-col flex-wrap gap-4">
                        <flux:button icon="magnifying-glass" wire:navigate
                                     href="{{ route('events.show', $event) }}">
                            {{ __('View Event') }}
                        </flux:button>
                    </div>
                </div>
            </flux:card>
        @endforeach
    </flux:tab.panel>
</flux:tab.group>

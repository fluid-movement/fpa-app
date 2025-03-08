<?php

use Illuminate\Support\Collection;
use Livewire\Volt\Component;

new #[\Livewire\Attributes\Title('Event Organizer')] class extends Component {
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
        @endif
        @foreach($events as $event)
            <flux:card wire:key="{{$event->id}}">
                <div class="flex flex-wrap gap-8">
                    <flux:heading class="mb-4 w-full md:w-1/4" size="lg">{{$event->name}}</flux:heading>
                    <x-events.infos :event="$event"/>
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
        @endif
        @foreach($pastEvents as $event)
            <flux:card wire:key="{{$event->id}}">
                <div class="flex flex-wrap gap-8">
                    <x-events.infos :event="$event"/>
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

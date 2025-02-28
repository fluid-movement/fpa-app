<?php

use App\Core\Service\EventStatusService;
use Livewire\Volt\Component;
use App\Models\Event;
use Illuminate\View\View;

new class extends Component {
    public Event $event;
    public string $status = '';

    public function mount(Event $event, EventStatusService $eventStatusService): void
    {
        $this->event = $event;
        $this->status = auth()->user() ? $eventStatusService->getStatus($event, auth()->user()) : '';
    }

    public function edit(): void
    {
        $this->redirect(route('events.edit', ['event' => $this->event]));
    }

    public function delete(): void
    {
        $this->event->delete();
        $this->redirect(route('events.index'));
    }

    public function updatedStatus(): void
    {
        if ($this->status === '') {
            $this->event->users()->detach(auth()->id());
        } else {
            $this->event->users()->syncWithoutDetaching([auth()->id() => ['status' => $this->status]]);
        }
    }

}; ?>

<div>
    <x-slot name="banner">
        @if($event->banner)
            @php
                [$width, $height] = $event->getBannerWidthHeight();
            @endphp
            <img
                src="{{ $event->banner_url }}"
                alt="{{ $event->name }}"
                width="{{ $width }}"
                height="{{ $height }}"
                class="w-full object-cover">
        @endif
    </x-slot>
    <flux:heading size="xl" class="mb-4">{{ $event->name }}</flux:heading>
    <div class="flex flex-col gap-1 justify-between mb-4 text-sm">
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
    @if($status !== \App\Core\Enum\EventUserStatus::RUNNING)
        <flux:radio.group wire:model.live="status" variant="cards" :indicator="false" class="max-sm:flex-col mb-4">
            <flux:radio value="{{\App\Core\Enum\EventUserStatus::ATTENDING->value}}" icon="heart" label="Attending"/>
            <flux:radio value="{{\App\Core\Enum\EventUserStatus::INTERESTED->value}}" icon="question-mark-circle"
                        label="Interested"/>
            <flux:radio value="" label="Not interested"/>
        </flux:radio.group>
    @endif
    <flux:tab.group>
        <flux:tabs>
            <flux:tab name="description">Description</flux:tab>
            <flux:tab name="schedule">Schedule</flux:tab>
            <flux:tab name="organizer">Organizer Area</flux:tab>
        </flux:tabs>

        <flux:tab.panel class="prose dark:prose-invert" name="description">{!! $event->description !!}</flux:tab.panel>
        <flux:tab.panel name="schedule"><p>Schedule goes here...</p></flux:tab.panel>
        <flux:tab.panel name="organizer">
            <div class="flex justify-between">
                <flux:button class="mb-8" wire:click="edit">Edit Event Details</flux:button>
                <div>
                    <flux:modal.trigger name="delete-event">
                        <flux:button variant="danger">Delete</flux:button>
                    </flux:modal.trigger>
                    <flux:modal name="delete-event" class="min-w-[22rem] space-y-6">
                        <div>
                            <flux:heading size="lg">Delete Event?</flux:heading>
                            <flux:subheading>
                                <p>You're about to delete this event.</p>
                                <p>This action cannot be reversed.</p>
                            </flux:subheading>
                        </div>
                        <div class="flex gap-2">
                            <flux:spacer/>
                            <flux:modal.close>
                                <flux:button variant="ghost">Cancel</flux:button>
                            </flux:modal.close>
                            <flux:button wire:click="delete" variant="danger">Delete event</flux:button>
                        </div>
                    </flux:modal>
                </div>
            </div>

            <flux:heading size="lg" class="mb-4">Attending Jammers</flux:heading>
            <flux:table>
                <flux:table.columns>
                    <flux:table.column>Name</flux:table.column>
                    <flux:table.column>Status</flux:table.column>
                </flux:table.columns>
                <flux:table.rows>
                    @foreach ($event->users as $user)
                        <flux:table.row :key="$user->id">
                            <flux:table.cell>
                                {{ $user->name }}
                            </flux:table.cell>
                            <flux:table.cell>
                                <flux:badge size="sm"
                                            color="{{$user->pivot->status === 'attending' ? 'green' : 'amber' }}"
                                            inset="top bottom">{{ $user->pivot->status }}</flux:badge>
                            </flux:table.cell>
                        </flux:table.row>
                    @endforeach
                </flux:table.rows>
            </flux:table>
        </flux:tab.panel>
    </flux:tab.group>
</div>

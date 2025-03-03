<?php

use App\Core\Enum\EventUserStatus;
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

    public function updatedStatus(): void
    {
        if (!in_array($this->status, [EventUserStatus::ATTENDING->value, EventUserStatus::INTERESTED->value, ''])) {
            $this->status = '';
            return;
        }

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
    @if(\Illuminate\Support\Facades\Auth::user() && $status !== EventUserStatus::ORGANIZING->value)
        <flux:radio.group wire:model.live="status" variant="cards" :indicator="false" class="max-sm:flex-col mb-4">
            <flux:radio value="{{EventUserStatus::ATTENDING->value}}" icon="heart" label="Attending"/>
            <flux:radio value="{{EventUserStatus::INTERESTED->value}}" icon="question-mark-circle"
                        label="Interested"/>
            <flux:radio value="" label="Not interested"/>
        </flux:radio.group>
    @endif
    <flux:tab.group>
        <flux:tabs>
            <flux:tab name="description">Description</flux:tab>
            <flux:tab name="schedule">Schedule</flux:tab>
        </flux:tabs>
        <flux:tab.panel class="prose dark:prose-invert" name="description">{!! $event->description !!}</flux:tab.panel>
        <flux:tab.panel name="schedule"><p>Schedule goes here...</p></flux:tab.panel>
    </flux:tab.group>
</div>

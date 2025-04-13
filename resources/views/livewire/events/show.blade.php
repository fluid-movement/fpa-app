<?php

use App\Enums\EventUserStatus;
use App\Models\Event;
use Livewire\Volt\Component;

new class extends Component
{
    public Event $event;

    public string $status = '';

    public bool $showButtons = false;

    public function rendering(Illuminate\View\View $view): void
    {
        $view->title($this->event->name);
    }

    public function mount(Event $event): void
    {
        $this->event = $event;
        $this->status =
            auth()->user()
                ? $event->users()->where('user_id', auth()->user()->id)->first()?->pivot->status ?? ''
                : '';
        $this->showButtons = auth()->user() && $this->status !== EventUserStatus::Organizing->value && $this->event->end_date->isFuture();
    }

    public function updatedStatus(): void
    {
        if (! in_array($this->status, [EventUserStatus::Attending->value, ''])) {
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

<x-slot name="breadcrumbs">
    {{ Breadcrumbs::render('events.show', $event) }}
</x-slot>

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
                class="w-full object-cover mb-8">
        @endif
    </x-slot>
    <x-events.infos :event="$event"/>
    @if($showButtons)
        <flux:radio.group wire:model.live="status"
                          variant="cards"
                          :indicator="false"
                          class="max-sm:flex-col mb-4">
            <flux:radio value="{{EventUserStatus::Attending->value}}"
                        icon="heart"
                        label="Attending"/>
            <flux:radio value="" label="Not interested"/>
        </flux:radio.group>
    @endif
    <flux:tab.group>
        <flux:tabs>
            <flux:tab name="description">Description</flux:tab>
            <flux:tab name="schedule">Schedule</flux:tab>
        </flux:tabs>
        <flux:tab.panel name="description">
            <flux:text>
            {!! $event->description !!}

            </flux:text>
        </flux:tab.panel>
        <flux:tab.panel name="schedule">
            <livewire:events.schedule.list :$event/>
        </flux:tab.panel>
    </flux:tab.group>
</div>


<?php

use App\Core\Enum\EventUserStatus;
use App\Core\Service\EventStatusService;
use Illuminate\Support\Collection;
use Livewire\Volt\Component;
use App\Models\Event;

new class extends Component {
    public Event $event;
    public string $status = '';

    public function rendering(Illuminate\View\View $view): void
    {
        $view->title($this->event->name);
    }

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
    @if(\Illuminate\Support\Facades\Auth::user() && $status !== EventUserStatus::ORGANIZING->value)
        <flux:radio.group wire:model.live="status"
                          variant="cards"
                          :indicator="false"
                          class="max-sm:flex-col mb-4">
            <flux:radio value="{{EventUserStatus::ATTENDING->value}}"
                        icon="heart"
                        label="Attending"/>
            <flux:radio value="{{EventUserStatus::INTERESTED->value}}"
                        icon="bookmark"
                        label="Interested"/>
            <flux:radio value="" label="Not interested"/>
        </flux:radio.group>
    @endif
    <flux:tab.group>
        <flux:tabs>
            <flux:tab name="description">Description</flux:tab>
            <flux:tab name="schedule">Schedule</flux:tab>
        </flux:tabs>
        <flux:tab.panel class="prose dark:prose-invert" name="description">
            {!! $event->description !!}
        </flux:tab.panel>
        <flux:tab.panel name="schedule">
            <livewire:events.schedule.list :event="$event"/>
        </flux:tab.panel>
    </flux:tab.group>
</div>


<?php

use App\Enums\EventUserStatus;
use App\Models\Event;
use Livewire\Volt\Component;

new class extends Component {
    public Event $event;

    public string $status = '';

    public bool $showButtons = false;

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
        if (!in_array($this->status, [EventUserStatus::Attending->value, ''])) {
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

<div class="grid grid-cols-2 gap-4">
    <div class="prose prose-invert col-span-2 {{ $event->picture ? 'md:col-span-1' : '' }}">
        <h1>{{$event->name}}</h1>
        <p class="flex gap-2 items-center">
            <flux:icon name="calendar-days"/>
            @if($event->start_date->is($event->end_date))
                {{ $event->start_date->format('d F Y') }}
            @elseif($event->start_date->format('m') == $event->end_date->format('m'))
                {{ $event->start_date->format('d') }} - {{ $event->end_date->format('d F Y') }}
            @else
                {{ $event->start_date->format('d F') }} - {{ $event->end_date->format('d F Y') }}
            @endif
        </p>
        <p class="flex gap-2 items-center ">
            <flux:icon name="map-pin"/>{{ $event->location }}
        </p>
        <p class="flex gap-2 items-center ">
            <flux:icon name="heart"/>{{ $event->attending_count }} attending
        </p>
        <flux:spacer/>
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
    </div>
    @if($event->picture)
        @php
            [$width, $height] = $event->getPictureWidthHeight();
        @endphp
        <img
            src="{{ $event->picture_url }}"
            alt="{{ $event->name }}"
            width="{{ $width }}"
            height="{{ $height }}"
            class="hidden md:block w-full object-cover mb-8">
    @endif
    <div class="col-span-2 md:hidden">
        <flux:tab.group>
            <flux:tabs>
                <flux:tab name="description">Description</flux:tab>
                <flux:tab name="schedule">Schedule</flux:tab>
            </flux:tabs>
            <flux:tab.panel name="description">
                <flux:text class="prose prose-invert">{!! $event->description !!}</flux:text>
            </flux:tab.panel>
            <flux:tab.panel name="schedule">
                <livewire:events.schedule.list :$event/>
            </flux:tab.panel>
        </flux:tab.group>
    </div>
    <flux:text class="hidden md:block prose prose-invert">
        {!! $event->description !!}
    </flux:text>
    <div class="hidden md:block">
        <livewire:events.schedule.list :$event/>
    </div>
</div>


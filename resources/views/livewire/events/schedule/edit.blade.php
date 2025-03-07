<?php

use Illuminate\Support\Collection;
use Livewire\Volt\Component;
use App\Models\Event;
use App\Models\Schedule;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

new class extends Component {
    public Event $event;
    public Collection $days;

    public function mount(Event $event): void
    {
        $this->event = $event;

        $this->days = collect(
            CarbonPeriod::create(
                Carbon::parse($event->start_date),
                Carbon::parse($event->end_date)
            )->toArray()
        );
    }

}; ?>

<div>
    <flux:button
        icon="chevron-left"
        href="{{ route('events.show', $event) }}"
        class="mb-8">
        Back to Event
    </flux:button>
    @foreach($days as $day)
        <flux:heading class="mb-4">{{ $day->format('l, F j') }}</flux:heading>
        <livewire:events.schedule.form :event="$event" :day="$day"/>
        @if(!$loop->last)
            <flux:separator class="my-8"/>
        @endif
    @endforeach
</div>

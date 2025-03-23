<?php

use App\Models\Event;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Collection;
use Livewire\Volt\Component;

new class extends Component
{
    public Event $event;

    public Collection $days;

    public function rendering(Illuminate\View\View $view): void
    {
        $view->title('Edit Schedule | '.$this->event->name);
    }

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
    <x-back-button href="{{ route('events.admin', [$event, \App\Enums\AdminTabs::SCHEDULE]) }}"/>
    @foreach($days as $day)
        <flux:heading class="mb-4">{{ $day->format('l, F j') }}</flux:heading>
        <livewire:events.schedule.form :event="$event" :day="$day"/>
        @if(!$loop->last)
            <flux:separator class="my-8"/>
        @endif
    @endforeach
</div>

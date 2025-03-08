<?php

use Livewire\Attributes\Title;
use Livewire\Volt\Component;
use App\Models\Event;
use App\Core\Service\EventCalendarService;
use Illuminate\Support\Carbon;

new #[Title('Upcoming Events')] class extends Component {
    public array $calendarData = [];
    public array $userAttending = [];

    public function mount(): void
    {
        $this->calendarData = EventCalendarService::getFormattedCalendar(
            Event::whereFuture('end_date')->get()
        );

        if (\Illuminate\Support\Facades\Auth::user()) {
            DB::table('event_user')
                ->where('user_id', \Illuminate\Support\Facades\Auth::user()->id)
                ->get()
                ->each(function ($eventUser) {
                    $this->userAttending[$eventUser->event_id] = $eventUser->status;
                })->toArray();
        }
    }
}; ?>

<x-slot name="breadcrumbs">
    {{ Breadcrumbs::render('events.index') }}
</x-slot>

<div>
    <x-events._list :calendarData="$calendarData" :userAttending="$userAttending"/>
</div>

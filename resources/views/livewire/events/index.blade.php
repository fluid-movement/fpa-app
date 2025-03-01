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

<div>
    @if(count($calendarData) > 0)
        @foreach($calendarData as $year => $months)
            <flux:heading size="xl">Upcoming Events in {{ $year }}</flux:heading>
            @foreach($months as $month => $events)
                <section class="mb-8">
                    <div class="flex gap-4 items-center my-8">
                        <flux:separator text="{{ $month }}"/>
                    </div>
                    <div class="grid gap-4 grid-cols-1 md:grid-cols-2 xl:grid-cols-3">
                        @foreach($events as $event)
                            <x-events.card
                                :event="$event"
                                :key="$event->id"
                                :badge="$this->userAttending[$event->id] ?? ''"
                            />
                        @endforeach
                    </div>
                </section>
            @endforeach
        @endforeach
    @endif
</div>

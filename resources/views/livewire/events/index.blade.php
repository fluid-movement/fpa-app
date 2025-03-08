<?php

use JetBrains\PhpStorm\NoReturn;
use Livewire\Attributes\Title;
use Livewire\Volt\Component;
use App\Models\Event;
use App\Core\Service\EventCalendarService;
use Illuminate\Support\Carbon;

enum EventListType: string
{
    case Upcoming = 'upcoming';
    case Past = 'past';
}

new #[Title('Event Calendar')] class extends Component {
    public array $calendarData = [];

    // badges for upcoming events
    public array $userAttending = [];

    // switch between upcoming and past events
    public EventListType $showEventsType = EventListType::Upcoming;

    // archive properties
    public array $radioYears = [];
    public array $dropdownYears = [];
    public int $year = 0;
    public int $dropdownAfter = 3;

    public function mount(string $year = null): void
    {
        $years = EventCalendarService::getArchiveYears();
        $this->radioYears = array_slice($years, 0, $this->dropdownAfter);
        $this->dropdownYears = array_slice($years, $this->dropdownAfter);

        $this->year = $year ?? $years[0] ?? Carbon::now()->year;

        if ($year) {
            $this->showEventsType = EventListType::Past;
            $this->updateCalendarData();
        } else {
            $this->calendarData = EventCalendarService::getFormattedCalendar(
                Event::whereFuture('end_date')->get()
            );
        }

        if (\Illuminate\Support\Facades\Auth::user()) {
            DB::table('event_user')
                ->where('user_id', \Illuminate\Support\Facades\Auth::user()->id)
                ->get()
                ->each(function ($eventUser) {
                    $this->userAttending[$eventUser->event_id] = $eventUser->status;
                })->toArray();
        }
    }

    public function updatedShowEventsType(): void
    {
        if ($this->showEventsType === EventListType::Past) {
            $this->updateCalendarData();
            $this->js('setUrl', route('events.index.past', ['year' => $this->year]));
        } else {
            $this->calendarData = EventCalendarService::getFormattedCalendar(
                Event::whereFuture('end_date')->get()
            );
            $this->js('setUrl', route('events.index'));
        }

    }

    public function updatedYear(): void
    {
        $this->updateCalendarData();
        $this->js('setUrl', route('events.index.past', ['year' => $this->year]));
    }

    private function updateCalendarData(): void
    {
        $this->calendarData = EventCalendarService::getFormattedCalendar(
            Event::whereYear('start_date', $this->year)
                ->where('end_date', '<', now())->get()
        );
    }
}; ?>

<x-slot name="breadcrumbs">
    {{ Breadcrumbs::render('events.index') }}
</x-slot>

<div class="flex flex-col gap-8">
    <flux:radio.group wire:model.live="showEventsType" variant="segmented">
        <flux:radio class="cursor-pointer" value="upcoming" label="Upcoming Events"/>
        <flux:radio class="cursor-pointer" value="past" label="Past Events"/>
    </flux:radio.group>

    <!-- radio buttons for past year selection -->
    @if($showEventsType === EventListType::Past)
        <div class="flex gap-2 mb-8">
            @if(count($radioYears))
                @foreach($radioYears as $radioYear)
                    <flux:button
                        class="cursor-pointer"
                        wire:click="$set('year', {{ $radioYear }})"
                        variant="{{ $radioYear == $year ? 'filled' : 'ghost' }}">
                        {{ $radioYear }}
                    </flux:button>
                @endforeach
                @if($dropdownYears)
                    <flux:dropdown position="bottom">
                        <flux:button class="cursor-pointer" variant="ghost">more</flux:button>
                        <flux:navmenu>
                            @foreach($dropdownYears as $dropdownYear)
                                <flux:navmenu.item
                                    class="cursor-pointer"
                                    wire:click="$set('year', {{ $dropdownYear }})">
                                    {{$dropdownYear}}
                                </flux:navmenu.item>
                            @endforeach
                        </flux:navmenu>
                    </flux:dropdown>
                @endif
            @endif
        </div>
    @endif
    @if($showEventsType === EventListType::Past)
        <x-events._list :calendarData="$calendarData"/>
    @else
        <x-events._list :calendarData="$calendarData" :userAttending="$userAttending"/>
    @endif
</div>

@script
<script>
    $js('setUrl', (url) => {
        window.history.pushState({}, '', url);
    })
</script>
@endscript

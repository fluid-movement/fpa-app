<?php

use App\Core\Service\EventCalendarService;
use App\Models\Event;
use Illuminate\Support\Carbon;
use Livewire\Volt\Component;

enum EventListType: string
{
    case Upcoming = 'upcoming';
    case Past = 'past';
}

new class extends Component
{
    public array $calendarData = [];

    // badges for upcoming events
    public array $badges = [];

    // switch between upcoming and past events
    public EventListType $eventListType = EventListType::Upcoming;

    // archive properties
    public array $radioYears = [];

    public array $dropdownYears = [];

    public int $year = 0;

    public int $dropdownAfter = 3;

    public function rendering(Illuminate\View\View $view): void
    {
        $title = match ($this->eventListType) {
            EventListType::Upcoming => 'Upcoming Events',
            EventListType::Past => 'Past Events in '.$this->year,
        };
        $view->title($title);
    }

    public function mount(?string $year = null): void
    {
        $years = EventCalendarService::getArchiveYears();
        $this->radioYears = array_slice($years, 0, $this->dropdownAfter);
        $this->dropdownYears = array_slice($years, $this->dropdownAfter);

        $this->year = $year ?? $years[0] ?? Carbon::now()->year;

        if ($year) {
            $this->eventListType = EventListType::Past;
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
                    $this->badges[$eventUser->event_id][] = $eventUser->status;
                })->toArray();
        }
    }

    public function updatedEventListType(): void
    {
        if ($this->eventListType === EventListType::Past) {
            $this->updateCalendarData();
        } else {
            $this->calendarData = EventCalendarService::getFormattedCalendar(
                Event::whereFuture('end_date')->get()
            );
        }

    }

    public function updatedYear(): void
    {
        $this->updateCalendarData();
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
    <div class="p-2 w-full flex gap-2 justify-stretch border border-zinc-200 dark:border-zinc-700 rounded-md">
        <flux:button
            class="w-full"
            href="{{route('events.index')}}"
            variant="{{$eventListType === EventListType::Upcoming ? 'filled' : 'ghost'}}">
            Upcoming Events
        </flux:button>
        <flux:button
            class="w-full"
            href="{{route('events.index.past', ['year' => $year])}}"
            variant="{{$eventListType === EventListType::Past ? 'filled' : 'ghost'}}"
        >
            Past Events
        </flux:button>
    </div>

    <!-- radio buttons for past year selection -->
    @if($eventListType === EventListType::Past)
        <div class="flex gap-2 mb-8">
            <flux:spacer />
            @if(count($radioYears))
                @foreach($radioYears as $radioYear)
                    <flux:button
                        class="cursor-pointer"
                        href="{{route('events.index.past', ['year' => $radioYear])}}"
                        wire:navigate
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
                                    href="{{route('events.index.past', ['year' => $dropdownYear])}}"
                                    wire:navigate
                                    >
                                    {{$dropdownYear}}
                                </flux:navmenu.item>
                            @endforeach
                        </flux:navmenu>
                    </flux:dropdown>
                @endif
            @endif
        </div>
    @endif
    <x-events._list :calendarData="$calendarData" :badges="$badges"/>
</div>

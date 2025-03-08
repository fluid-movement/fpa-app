<?php

use App\Core\Service\EventCalendarService;
use Illuminate\Support\Carbon;
use Livewire\Volt\Component;
use App\Models\Event;

new class extends Component {
    public array $calendarData = [];
    public array $radioYears = [];
    public array $dropdownYears = [];

    public int $year = 0;
    public int $dropdownAfter = 3;

    public function rendering(Illuminate\View\View $view): void
    {
        $view->title("Past Events in $this->year");
    }

    public function mount($year = null): void
    {
        $years = EventCalendarService::getArchiveYears();
        $this->radioYears = array_slice($years, 0, $this->dropdownAfter);
        $this->dropdownYears = array_slice($years, $this->dropdownAfter);

        $this->year = $year ?? $years[0] ?? Carbon::now()->year;
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

<div>
    <div class="flex gap-2 mb-8">
        @if(count($radioYears))
            @foreach($radioYears as $radioYear)
                <flux:button
                    wire:navigate
                    href="{{route('events.archive.index', $radioYear)}}"
                    variant="{{ $radioYear == $year ? 'filled' : 'ghost' }}">
                    {{ $radioYear }}
                </flux:button>
            @endforeach
            @if($dropdownYears)
                <flux:dropdown position="bottom">
                    <flux:button variant="ghost">more</flux:button>
                    <flux:navmenu>
                        @foreach($dropdownYears as $dropdownYear)
                            <flux:navmenu.item wire:navigate href="{{route('events.archive.index', $dropdownYear)}}">
                                {{$dropdownYear}}
                            </flux:navmenu.item>
                        @endforeach
                    </flux:navmenu>
                </flux:dropdown>
            @endif
        @endif
    </div>
    <x-events._list :calendarData="$calendarData" />
</div>

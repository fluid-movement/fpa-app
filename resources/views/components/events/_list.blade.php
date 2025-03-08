@if(count($calendarData) > 0)
    @foreach($calendarData as $year => $months)
        @foreach($months as $month => $events)
            <section class="mb-8 relative">
                <div class="sticky top-14 z-5 bg-white dark:bg-zinc-800 shadow-2xl dark:shadow-zinc-800">
                    @if($loop->first)
                        <div class="flex gap-4 items-center mb-8">
                            <flux:separator text="{{ $month }} {{ $year }}"/>
                        </div>
                    @else
                        <div class="flex gap-4 items-center my-8">
                            <flux:separator text="{{ $month }} {{ $year }}"/>
                        </div>
                    @endif
                </div>
                <div class="grid gap-8 grid-cols-1 items-stretch md:grid-cols-2 xl:grid-cols-3">
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

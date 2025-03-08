@if(count($calendarData) > 0)
    @foreach($calendarData as $year => $months)
        @foreach($months as $month => $events)
            <section class="relative">
                <div class="sticky z-10 top-14 mb-8">
                    <flux:separator text="{{ $month }} {{ $year }}"/>
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

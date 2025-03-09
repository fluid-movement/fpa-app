@props(['event', 'badges' => []])

<a class="size-full" href="{{route('events.show', ['event' => $event])}}">
    <div
        class="size-full p-4 rounded-sm flex flex-col justify-between
        bg-white hover:bg-blue-50 dark:hover:bg-blue-200/10 dark:bg-white/10 border border-zinc-200 dark:border-white/10">
        <div class="flex mb-4">
            <flux:heading size="lg">{{ $event->name }}</flux:heading>
        </div>
        <div class="flex h-24">
            <div class="grow flex flex-col gap-4">
                @if($event->end_date->isFuture())
                    <div>
                        @foreach($badges as $badge)
                            <x-events.badge :badge="$badge"/>
                        @endforeach
                    </div>
                @endif
                <flux:spacer/>
                <div class="text-slate-500 dark:text-slate-300">
                    <flux:text variant="strong" class="flex gap-2 items-center mb-1">
                        <flux:icon variant="micro" name="calendar-days"/>{{ $event->date_range }}
                    </flux:text>
                    <flux:text class="flex gap-2 items-center mb-1">
                        <flux:icon variant="micro" name="map-pin"/>{{ $event->location }}
                    </flux:text>

                </div>
            </div>
            @if($event->icon)
                <img
                    src="{{ $event->icon_url }}"
                    alt="{{ $event->name }}"
                    class="object-contain rounded-sm"
                />
            @endif
        </div>
    </div>
</a>

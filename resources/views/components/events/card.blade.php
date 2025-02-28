@props(['event', 'badge' => ''])
<a href="{{route('events.show', ['event' => $event])}}">
    <flux:card class="relative">
        @if($badge)
            <div class="absolute top-0 right-0 mt-2 mr-2">
                <flux:badge color="green">{{ ucfirst($badge) }}</flux:badge>
            </div>
        @endif
        <div class="grid grid-cols-[0.4fr_1.6fr] grid-rows-[auto_auto] items-center gap-x-2 gap-y-2">
            <div class="row-span-2 flex flex-col gap-2 items-center">
                <div class="text-5xl font-bold text-slate-700 dark:text-slate-300 leading-none">
                    {{ $event->day }}
                </div>
                @if($event->icon)
                    <img
                        src="{{ $event->icon_url }}"
                        alt="{{ $event->name }}"
                        class="rounded-lg h-16 w-16 object-contain"
                    />
                @endif
            </div>

            <flux:heading size="lg" class="self-end">
                {{ $event->name }}
            </flux:heading>

            <div class="space-y-1 text-slate-500 dark:text-slate-300 self-center">
                <p class="flex gap-2 items-center">
                    <flux:icon name="calendar-days"/>{{ $event->date_range }}
                </p>
                <p class="flex gap-2 items-center">
                    <flux:icon name="map-pin"/>{{ $event->location }}
                </p>
            </div>
        </div>
    </flux:card>
</a>

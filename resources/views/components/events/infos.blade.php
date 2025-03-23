<div class="grow flex flex-col gap-1 justify-between mb-4 text-sm">
    <p class="flex gap-2 items-center text-slate-500 dark:text-white">
        <flux:icon name="calendar-days"/>
        @if($event->start_date->format('m') == $event->end_date->format('m'))
            {{ $event->start_date->format('d') }} - {{ $event->end_date->format('d F') }}
        @else
            {{ $event->start_date->format('d F') }} - {{ $event->end_date->format('d F') }}
        @endif
    </p>
    <p class="flex gap-2 items-center text-slate-500 dark:text-white">
        <flux:icon name="map-pin"/>{{ $event->location }}
    </p>
    <p class="flex gap-2 items-center text-slate-500 dark:text-white">
        <flux:icon name="heart"/>{{ $event->attending->count() }} attending
    </p>
</div>

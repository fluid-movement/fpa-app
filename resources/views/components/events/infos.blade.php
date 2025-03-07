<div class="grow flex flex-col gap-1 justify-between mb-4 text-sm">
    <p class="flex gap-2 items-center text-slate-500 dark:text-white">
        <flux:icon name="calendar-days"/>{{ $event->date_range_full }}
    </p>
    <p class="flex gap-2 items-center text-slate-500 dark:text-white">
        <flux:icon name="map-pin"/>{{ $event->location }}
    </p>
    <p class="flex gap-2 items-center text-slate-500 dark:text-white">
        <flux:icon name="heart"/>{{ $event->attending->count() }} attending
    </p>
    <p class="flex gap-2 items-center text-slate-500 dark:text-white">
        <flux:icon name="bookmark"/>{{ $event->interested->count() }} interested
    </p>
</div>

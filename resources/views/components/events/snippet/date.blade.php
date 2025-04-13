@props(['event'])

<flux:text variant="strong" class="flex gap-2 items-center mb-1">
    <flux:icon variant="micro" name="calendar-days"/>
    @if($event->start_date->format('m') == $event->end_date->format('m'))
        {{ $event->start_date->format('d') }} - {{ $event->end_date->format('d F') }}
    @else
        {{ $event->start_date->format('d F') }} - {{ $event->end_date->format('d F') }}
    @endif
</flux:text>

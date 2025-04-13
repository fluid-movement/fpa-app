@props(['event', 'badges' => []])

<a class="size-full" href="{{route('events.show', ['event' => $event])}}">
    <x-ui.card class="flex flex-col md:flex-row">
        <div class="grow">
            <div>
                <div>Next Event</div>
            </div>
            <header class="flex flex-col gap-2">
                <h1 class="text-white text-3xl font-bold">{{$event->name}}</h1>
                <flux:text variant="strong" class="flex gap-2 items-center mb-1">
                    <flux:icon variant="micro" name="calendar-days"/>
                    @if($event->start_date->format('m') == $event->end_date->format('m'))
                        {{ $event->start_date->format('d') }} - {{ $event->end_date->format('d F') }}
                    @else
                        {{ $event->start_date->format('d F') }} - {{ $event->end_date->format('d F') }}
                    @endif
                </flux:text>
                <flux:text class="flex gap-2 items-center mb-1">
                    <flux:icon variant="micro" name="map-pin"/>{{ $event->location }}
                </flux:text>
            </header>
        </div>
        <img
            src="{{ $event->banner_url }}"
            alt="{{ $event->name }}"
            class="md:max-w-1/2 object-contain rounded-sm"
        />
    </x-ui.card>
</a>

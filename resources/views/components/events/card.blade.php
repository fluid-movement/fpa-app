@props(['event', 'badges' => []])

<a class="size-full" href="{{route('events.show', ['event' => $event])}}">
    <x-ui.card>
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
                <div class="text-slate-300">
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
    </x-ui.card>
</a>

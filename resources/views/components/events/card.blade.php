@props(['event', 'badges' => []])

<a href="{{ route('events.show', ['event' => $event]) }}" wire:navigate>
    <div class="relative">
        <x-ui.card class="flex gap-4 relative z-0">
            {{-- Date Column --}}
            <div class="flex flex-col">
                <div class="tracking-tight text-4xl font-bold text-slate-700 whitespace-nowrap">
                    @if($event->start_date->is($event->end_date))
                        {{ $event->start_date->format('d') }}
                    @else
                        {{ $event->start_date->format('d') }}–{{ $event->end_date->format('d') }}
                    @endif
                </div>
                <div class="text-slate-700 text-sm">
                    @if($event->start_date->format('m') === $event->end_date->format('m'))
                        {{ $event->start_date->format('F') }}
                    @else
                        {{ $event->start_date->format('M') }} – {{ $event->end_date->format('M') }}
                    @endif
                </div>
            </div>

            <flux:separator vertical="true" />

            {{-- Event Info --}}
            <div class="flex flex-col justify-center">
                <h2 class="text-xl font-bold text-slate-700">{{ $event->name }}</h2>
                <flux:text class="flex items-center gap-2 text-slate-700">
                    <flux:icon variant="micro" name="map-pin" />
                    {{ $event->location }}
                </flux:text>
            </div>
        </x-ui.card>

        {{-- Floating Badges --}}
        @if($event->end_date->isFuture())
            <div class="absolute -top-3 right-3 z-10 flex gap-1">
                @foreach($badges as $badge)
                    <x-events.badge :badge="$badge"/>
                @endforeach
            </div>
        @endif
    </div>
</a>

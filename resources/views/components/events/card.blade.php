@props(['event', 'badge' => ''])

<div
    class="p-4 h-48 rounded-sm relative bg-white hover:bg-blue-50 dark:hover:bg-blue-200/10 dark:bg-white/10 border border-zinc-200 dark:border-white/10">
    <a href="{{route('events.show', ['event' => $event])}}">
        <div class="flex gap-4 h-full">
            <div class="grow flex flex-col p-4">
                <flux:heading size="lg" class="h-1/2">{{ Str::limit($event->name, 20) }}</flux:heading>
                <div class="text-slate-500 dark:text-slate-300">
                    <flux:text class="flex gap-2 items-center mb-1">
                        <flux:icon variant="micro" name="calendar-days"/>{{ $event->date_range }}
                    </flux:text>
                    <flux:text class="flex gap-2 items-center mb-1">
                        <flux:icon variant="micro" name="map-pin"/>{{ $event->location }}
                    </flux:text>
                    @switch($badge)
                        @case(\App\Core\Enum\EventUserStatus::ATTENDING->value)
                            <flux:badge inset="left" size="sm" color="green">
                                {{__(ucfirst(\App\Core\Enum\EventUserStatus::ATTENDING->value))}}
                            </flux:badge>
                            @break
                        @case(\App\Core\Enum\EventUserStatus::INTERESTED->value)
                            <flux:badge inset="left" size="sm" color="amber">
                                {{__(ucfirst(\App\Core\Enum\EventUserStatus::INTERESTED->value))}}
                            </flux:badge>
                            @break
                        @case(\App\Core\Enum\EventUserStatus::ORGANIZING->value)
                            <flux:badge inset="left" size="sm" color="blue">{{ucfirst($badge)}}</flux:badge>
                            @break
                    @endswitch
                </div>
            </div>
            @if($event->icon)
                <div class="h-40 w-40">
                    <img
                        src="{{ $event->icon_url }}"
                        alt="{{ $event->name }}"
                        class="rounded-sm h-full object-contain"
                    />
                </div>
            @endif
        </div>
    </a>
</div>

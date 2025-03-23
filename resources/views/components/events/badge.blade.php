@props([
    /** @var \App\Enums\EventUserStatus */
    'badge'
])

@switch($badge)
    @case(\App\Enums\EventUserStatus::ATTENDING->value)
        <flux:badge inset="left" size="sm" color="green">
            {{__(ucfirst(\App\Enums\EventUserStatus::ATTENDING->value))}}
        </flux:badge>
        @break
    @case(\App\Enums\EventUserStatus::ORGANIZING->value)
        <flux:badge inset="left" size="sm" color="blue">{{ucfirst($badge)}}</flux:badge>
        @break
@endswitch

@props([
    /** @var \App\Enums\EventUserStatus */
    'badge'
])

@switch($badge)
    @case(\App\Enums\EventUserStatus::Attending->value)
        <flux:badge inset="left" size="sm" color="green">
            {{__(ucfirst(\App\Enums\EventUserStatus::Attending->value))}}
        </flux:badge>
        @break
    @case(\App\Enums\EventUserStatus::Organizing->value)
        <flux:badge inset="left" size="sm" color="blue">{{ucfirst($badge)}}</flux:badge>
        @break
@endswitch

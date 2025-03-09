@props([
    /** @var \mixed */
    'badge'
])

@switch($badge)
    @case(\App\Core\Enum\EventUserStatus::ATTENDING->value)
        <flux:badge inset="left" size="sm" color="green">
            {{__(ucfirst(\App\Core\Enum\EventUserStatus::ATTENDING->value))}}
        </flux:badge>
        @break
    @case(\App\Core\Enum\EventUserStatus::INTERESTED->value)
        <flux:badge inset="left" size="sm">
            {{__(ucfirst(\App\Core\Enum\EventUserStatus::INTERESTED->value))}}
        </flux:badge>
        @break
    @case(\App\Core\Enum\EventUserStatus::ORGANIZING->value)
        <flux:badge inset="left" size="sm" color="blue">{{ucfirst($badge)}}</flux:badge>
        @break
@endswitch

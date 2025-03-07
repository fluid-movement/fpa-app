<div class="flex gap-4">
    <flux:text class="flex-none w-fit whitespace-nowrap">{{ $item->time }}</flux:text>
    <div class="grow flex flex-col gap-2">
        <flux:heading >{{ $item->name }}</flux:heading>
        @if($item->location)
            <flux:text class="flex gap-2 items-center">
                <flux:icon.map-pin variant="micro"/>
                {{ $item->location }}
            </flux:text>
        @endif
        @if($item->description)
            <flux:text>
                {{ $item->description }}
            </flux:text>
        @endif
    </div>
</div>

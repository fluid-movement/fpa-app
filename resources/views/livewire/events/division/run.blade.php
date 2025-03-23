<?php

use Livewire\Volt\Component;

new class extends Component
{
    public \App\Models\Division $division;
}; ?>

<div>
    <x-back-button href="{{route('events.admin', [$division->event, \App\Enums\AdminTabs::DIVISIONS])}}"/>
    <flux:heading size="xl" class="mb-8">{{ $division->type }}</flux:heading>
    @foreach($division->rounds()->get() as $round)
        <flux:heading size="lg" class="mb-4">Round {{ $round->name }}</flux:heading>
        @foreach($round->pools as $pool)
            <flux:button href="{{ route('events.division.judge', [$pool]) }}">{{$pool->name}}</flux:button>
        @endforeach
    @endforeach
</div>

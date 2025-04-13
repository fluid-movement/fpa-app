<?php

use Livewire\Volt\Component;

new class extends Component
{
    public \App\Models\Division $division;

    public function mount(\App\Models\Division $division): void
    {
        $this->division = $division;
    }
}; ?>

<div>
    <x-back-button href="{{route('events.admin', [$division->event, \App\Enums\AdminTabs::Divisions])}}"/>
    <flux:heading size="xl" class="mb-8">{{ $division->type }}</flux:heading>
    @foreach($division->rounds()->get() as $round)
        <flux:heading size="lg" class="mb-4">Round {{ $round->name }}</flux:heading>
        @foreach($round->pools as $pool)
            <flux:modal.trigger name="{{$pool->id}}">
                <flux:button>{{$pool->name}}</flux:button>
            </flux:modal.trigger>

            <flux:modal name="{{$pool->id}}" class="md:w-96">
                <div class="space-y-6">
                    <div>
                        <flux:heading size="lg">QR Code for pool {{$pool->name}}</flux:heading>
                        <flux:text class="mt-2">Show this to all judges</flux:text>
                    </div>
                    <img src="{{app(\App\Services\PoolQrCodeService::class)->generate($pool)?->getDataUri()}}" alt="qr-code for pool {{$pool->name}}">
                    <flux:text>If the QR code isn't working, copy this link and send it to the judges:</flux:text>
                    <flux:input value="{{route('events.division.judge', $pool)}}" readonly copyable />
                </div>
            </flux:modal>
        @endforeach
    @endforeach
</div>

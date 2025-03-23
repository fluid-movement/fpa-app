<?php

use App\Models\Event;
use App\Models\EventMagicLink;
use Livewire\Volt\Component;

new class extends Component
{
    public Event $event;

    public ?EventMagicLink $magicLink;

    public function mount(Event $event): void
    {
        $this->event = $event;
        $this->magicLink = $this->event->magicLink;
    }

    public function generateMagicLink(): void
    {
        $this->magicLink = $this->event->magicLink()->create([
            'expires_at' => now()->addDays(2),
        ]);
    }
}; ?>

<div>
    @if(Auth::user()->id === $event->user_id)
        <flux:heading class="mb-4">Invite other Organizers</flux:heading>
        @if($magicLink)
            <flux:input class="mb-4" icon="key" value="{{ $magicLink->link }}" readonly="true" copyable="true"/>
            <flux:text>Link valid till {{$magicLink->expires_at->diffForHumans()}}</flux:text>
        @else
            <flux:button class="mb-4" wire:click="generateMagicLink">Generate Magic Link</flux:button>
        @endif
        <flux:separator class="mb-8"/>
    @endif
    <flux:table>
        <flux:table.columns>
            <flux:table.column>Organizers</flux:table.column>
            <flux:table.column></flux:table.column>
        </flux:table.columns>
        <flux:table.rows>
            @foreach ($event->organizers as $user)
                <flux:table.row :key="$user->id">
                    <flux:table.cell>
                        {{ $user->name }}
                    </flux:table.cell>
                    <flux:table.cell>
                        @if($event->user_id === $user->id)
                            <flux:badge variant="primary">Creator</flux:badge>
                        @else
                            <flux:badge variant="primary">Organizer</flux:badge>
                        @endif
                    </flux:table.cell>
                </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>
</div>

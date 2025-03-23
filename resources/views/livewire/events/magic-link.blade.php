<?php

use App\Enums\EventUserStatus;
use App\Models\Event;
use App\Models\EventMagicLink;
use Livewire\Volt\Component;

new class extends Component
{
    public Event $event;

    public EventMagicLink $magicLink;

    public string $status;

    public function mount(string $id): void
    {
        $magicLink = EventMagicLink::find($id);
        if (! $magicLink) {
            $this->status = 'invalid';

            return;
        }
        $this->event = $magicLink->event;
        $currentStatus = $this->event->users()->find(auth()->id())?->pivot->status;
        if ($magicLink->isActive()
            && $this->event->id === $magicLink->event_id
        ) {
            if ($currentStatus !== EventUserStatus::ORGANIZING->value) {
                $this->event->users()
                    ->syncWithoutDetaching(
                        [auth()->id() => ['status' => EventUserStatus::ORGANIZING]]
                    );
                $this->status = 'added';
            } else {
                $this->status = 'already';
            }
        } else {
            $this->status = 'invalid';
        }
    }
}; ?>

<div class="flex flex-col gap-4 items-center h-[30vh]">
    <flux:spacer/>
    <flux:heading size="xl">
        @if($status === 'added')
            {{ __('You are now an organizer of :name', ['name' => $event->name]) }}
        @elseif($status === 'already')
            {{ __('You are already an organizer of :name', ['name' => $event->name]) }}
        @elseif($status === 'invalid')
            {{ __('The invite link is invalid or has expired.') }}
        @endif
    </flux:heading>
    @if($event)
        <flux:link href="{{ route('events.admin', $event) }}">
            {{ __('Go to admin area') }}
        </flux:link>
    @endif
</div>

<?php

use App\Models\EventMagicLink;
use Livewire\Volt\Component;
use App\Models\Event;
use Illuminate\Support\Facades;
use Symfony\Component\Uid\Ulid;

new class extends Component {
    public Event $event;
    public ?EventMagicLink $magicLink;

    public function rendering(Illuminate\View\View $view): void
    {
        $view->title('Admin | '.$this->event->name);
    }

    public function mount(Event $event): void
    {
        Gate::authorize('admin', $event);
        $this->event = $event;
        $this->magicLink = $this->event->magicLink;
    }

    public function delete(): void
    {
        $this->event->delete();
        $this->redirect(route('events.index'));
    }

    public function generateMagicLink(): void
    {
        $this->magicLink = $this->event->magicLink()->create([
            'expires_at' => now()->addDays(2),
        ]);
    }
}; ?>

<div>
    <div class="flex justify-between">
        <flux:button class="mb-8" href="{{route('events.edit', $event)}}">Edit Event Details</flux:button>
        <div>
            <flux:modal.trigger name="delete-event">
                <flux:button variant="danger">Delete Event</flux:button>
            </flux:modal.trigger>
            <flux:modal name="delete-event" class="min-w-[22rem] space-y-6">
                <div>
                    <flux:heading size="lg">Delete Event?</flux:heading>
                    <flux:subheading>
                        <p>You're about to delete this event.</p>
                        <p>This action cannot be reversed.</p>
                    </flux:subheading>
                </div>
                <div class="flex gap-2">
                    <flux:spacer/>
                    <flux:modal.close>
                        <flux:button variant="ghost">Cancel</flux:button>
                    </flux:modal.close>
                    <flux:button wire:click="delete" variant="danger">Delete Event</flux:button>
                </div>
            </flux:modal>
        </div>
    </div>

    <flux:tab.group>
        <flux:tabs wire:model="tab">
            <flux:tab name="attending">Attending</flux:tab>
            <flux:tab name="interested">Interested</flux:tab>
            <flux:tab name="schedule">Schedule</flux:tab>
            <flux:tab name="divisions">Divisions</flux:tab>
            <flux:tab name="organizers">Organizers</flux:tab>
        </flux:tabs>

        <flux:tab.panel name="attending">
            <flux:heading size="lg" class="mb-4">Attending Jammers</flux:heading>
            <flux:table>
                <flux:table.columns>
                    <flux:table.column>Name</flux:table.column>
                    <flux:table.column>Updated</flux:table.column>
                </flux:table.columns>
                <flux:table.rows>
                    @foreach ($event->attending as $user)
                        <flux:table.row :key="$user->id">
                            <flux:table.cell>
                                {{ $user->name }}
                            </flux:table.cell>
                            <flux:table.cell>
                                {{ $user->pivot->updated_at->diffForHumans() }}
                            </flux:table.cell>
                        </flux:table.row>
                    @endforeach
                </flux:table.rows>
            </flux:table>
        </flux:tab.panel>
        <flux:tab.panel name="interested">
            <flux:heading size="lg" class="mb-4">Interested Jammers</flux:heading>
            <flux:table>
                <flux:table.columns>
                    <flux:table.column>Name</flux:table.column>
                    <flux:table.column>Updated</flux:table.column>
                </flux:table.columns>
                <flux:table.rows>
                    @foreach ($event->interested as $user)
                        <flux:table.row :key="$user->id">
                            <flux:table.cell>
                                {{ $user->name }}
                            </flux:table.cell>
                            <flux:table.cell>
                                {{ $user->pivot->updated_at->diffForHumans() }}
                            </flux:table.cell>
                        </flux:table.row>
                    @endforeach
                </flux:table.rows>
            </flux:table>
        </flux:tab.panel>
        <flux:tab.panel name="schedule">
            <flux:button class="mb-8" href="{{route('events.schedule.edit', $event)}}">
                Edit Schedule
            </flux:button>
            <livewire:events.schedule.list :event="$event"/>
        </flux:tab.panel>
        <flux:tab.panel name="divisions">
            Divisions go here
        </flux:tab.panel>
        <flux:tab.panel name="organizers">
            @if(Auth::user()->id === $event->user_id)
                <flux:heading class="mb-4">Invite other Organizers</flux:heading>
                @if($magicLink)
                    <flux:input class="mb-4" icon="key" value="{{ $magicLink->link }}" readonly="true" copyable="true"/>
                    <flux:text >Link valid till {{$magicLink->expires_at->diffForHumans()}}</flux:text>
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
        </flux:tab.panel>
    </flux:tab.group>
</div>

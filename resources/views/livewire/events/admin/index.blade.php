<?php

use App\Enums\AdminTabs;
use App\Models\Event;
use Livewire\Volt\Component;

new class extends Component
{
    public Event $event;

    public AdminTabs $tab;

    public function rendering(Illuminate\View\View $view): void
    {
        $view->title('Admin | '.$this->event->name);
    }

    public function mount(Event $event, AdminTabs $tab = AdminTabs::ATTENDING): void
    {
        Gate::authorize('admin', $event);
        $this->event = $event;
        $this->tab = $tab;
    }

    public function delete(): void
    {
        $this->event->delete();
        $this->redirect(route('events.index'));
    }
}; ?>

<div>
    <div class="flex justify-between gap-4">
        <x-back-button href="{{route('user.organizing')}}"/>
        <flux:spacer/>
        <flux:button icon="pencil-square" class="mb-8" href="{{route('events.edit', $event)}}">
            Edit Event
        </flux:button>
        <div>
            <flux:modal.trigger name="delete-event">
                <flux:button variant="danger" icon="trash">Delete</flux:button>
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
    <flux:tab.group wire:cloak>
        <flux:tabs wire:model="tab">
            @foreach(AdminTabs::getTabs() as $adminTab)
                <flux:tab
                    wire:click="$js.updateUrl('{{route('events.admin', [$event, $adminTab])}}')"
                    name="{{ $adminTab->value }}"
                >
                    {{ $adminTab->getTitle() }}
                </flux:tab>
            @endforeach
        </flux:tabs>

        @foreach(AdminTabs::getTabs() as $adminTab)
            <flux:tab.panel name="{{$adminTab->value}}">
                <livewire:is :component="$adminTab->getComponent()" :event="$event"/>
            </flux:tab.panel>
        @endforeach
    </flux:tab.group>
</div>

@script
<script>
    $js('updateUrl', (url) => {
        console.log(url)
        window.history.pushState({}, '', url);
    })
</script>
@endscript

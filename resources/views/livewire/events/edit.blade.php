<?php

use Livewire\Volt\Component;
use App\Models\Event;
use App\Livewire\Forms\EventForm;
use Livewire\WithFileUploads;

new class extends Component {
    use WithFileUploads;

    public EventForm $form;

    public function mount(Event $event): void
    {
        if (auth()->user()->cannot('update', $event)) {
            redirect(route('events.show', ['event' => $event]));
        }
        $this->form->setEvent($event);
    }

    public function update()
    {
        if (auth()->user()->cannot('update', $this->form->event)) {
            abort(403);
        }
        $this->form->update();
        return $this->redirect(route('events.show', ['event' => $this->form->event]));
    }
}; ?>

<div>
    <flux:heading level="1" size="xl">Edit event</flux:heading>
    <form method="post" wire:submit="update">
        <x-events._form :form="$form"/>
        <div class="flex justify-between">
            <flux:button variant="primary" type="submit">
                Save
            </flux:button>
            <flux:button wire:navigate onclick="window.history.back()" variant="ghost">
                Cancel
            </flux:button>
        </div>
    </form>
</div>

<?php

use Livewire\Volt\Component;
use App\Models\Event;
use App\Livewire\Forms\EventForm;

new class extends Component {
    public EventForm $form;

    public function mount(Event $event): void
    {
        $this->form->setEvent($event);
    }

    public function update()
    {
        $this->form->update();
        return $this->redirect(route('events.show', ['event' => $this->form->event]));
    }
}; ?>

<div>
    <flux:heading level="1" size="xl">Edit event</flux:heading>
    <x-events._form :form="$form"/>
    <flux:button variant="primary" wire:click="update">Save</flux:button>
</div>

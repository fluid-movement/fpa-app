<?php

use App\Livewire\Forms\EventForm;
use Livewire\Volt\Component;

new class extends Component {
    public EventForm $form;

    public function store()
    {
        $event = $this->form->store();
        return $this->redirect(route('events.show', ['event' => $event]));
    }
}; ?>

<div>
    <flux:heading level="1" size="xl">Create a new event</flux:heading>
    <x-events._form :form="$form"/>
    <flux:button variant="primary" wire:click="store">Create</flux:button>
</div>

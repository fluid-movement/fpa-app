<?php

use App\Livewire\Forms\EventForm;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;

new #[\Livewire\Attributes\Title('Create a new event')] class extends Component {
    use WithFileUploads;

    public EventForm $form;

    public function store()
    {
        $event = $this->form->store();
        return $this->redirect(route('events.show', ['event' => $event]));
    }
}; ?>

<form method="post" wire:submit="store">
    <x-events._form :form="$form"/>
    <flux:button variant="primary" type="submit">Create</flux:button>
</form>

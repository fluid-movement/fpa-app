<?php

use App\Livewire\Forms\PlayerForm;
use Livewire\Attributes\Title;
use Livewire\Volt\Component;

new #[Title('Add a new FPA member')]  class extends Component
{
    public PlayerForm $form;

    public function store()
    {
        $this->form->store();
        return $this->redirect(route('admin.index'));
    }
}; ?>

<div>
    <x-back-button href="{{ route('admin.index') }}"/>
    <form method="post" wire:submit="store">
        <x-players._form :form="$form"/>
        <flux:button variant="primary" type="submit">Create</flux:button>
    </form>

</div>

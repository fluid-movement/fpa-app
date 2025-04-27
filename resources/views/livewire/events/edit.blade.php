<?php

use App\Livewire\Forms\EventForm;
use App\Models\Event;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;

new class extends Component {
    use WithFileUploads;

    public EventForm $form;

    public function rendering(Illuminate\View\View $view): void
    {
        $view->title('Edit | ' . $this->form->event->name);
    }

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
    <x-history-back-button/>
    <form method="post" wire:submit="update">
        <x-events._form :$form/>
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

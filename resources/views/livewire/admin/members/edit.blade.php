<?php

use Livewire\Volt\Component;

new class extends Component
{
    public \App\Models\Player $player;
}; ?>

<div>
    <x-back-button href="{{ route('admin.index') }}"/>
</div>

<?php

use Livewire\Volt\Component;

new #[\Livewire\Attributes\Title('Profile')] class extends Component {}; ?>

<div>
    <flux:heading>Hey there {{auth()->user()->name}}!</flux:heading>

</div>

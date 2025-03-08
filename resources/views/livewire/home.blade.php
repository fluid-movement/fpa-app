<?php

use Livewire\Volt\Component;

new class extends Component {
    //
}; ?>

<x-slot name="breadcrumbs">
    {{ Breadcrumbs::render('home') }}
</x-slot>

<div>
    <flux:heading size="xl">Welcome to the FPA Event Calendar!</flux:heading>
</div>

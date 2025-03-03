<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Volt::route('/', 'home')->name('home');

// Events
Route::middleware(['auth'])->group(function () {
    Volt::route('events/{event}/edit', 'events.edit')
        ->name('events.edit');
    Volt::route('events/{event}/admin', 'events.admin')
        ->name('events.admin');
    Volt::route('organizers/invite/{id}', 'events.magic-link')
        ->name('events.admin.magic-link');
    Volt::route('events/create', 'events.create')
        ->name('events.create');
    Volt::route('events/{event}/schedule/edit', 'events.schedule.edit')
        ->name('events.schedule.edit');
});
Volt::route('events', 'events.index')
    ->name('events.index');
Volt::route('events/{event}', 'events.show')
    ->name('events.show');
Volt::route('archive/{year?}/', 'events.archive.index')
    ->name('events.archive.index');

// User pages
Route::middleware(['auth'])->group(function () {
    Volt::route('user/attending', 'user.attending')->name('user.attending');
    Volt::route('user/organizing', 'user.organizing')->name('user.organizing');
});

// User settings
Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__.'/auth.php';

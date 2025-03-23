<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Volt::route('/', 'home')->name('home');

// Events
Route::middleware(['auth'])->group(function () {
    // event create / edit
    Volt::route('events/{event}/edit', 'events.edit')
        ->name('events.edit');
    Volt::route('events/create', 'events.create')
        ->name('events.create');

    // event admin
    Volt::route('events/{event}/admin/{tab?}', 'events.admin.index')
        ->name('events.admin');
    Volt::route('events/{event}/admin/schedule/edit', 'events.schedule.edit')
        ->name('events.schedule.edit');

    // organizers magic link
    Volt::route('organizers/invite/{id}', 'events.magic-link')
        ->name('events.admin.magic-link');

    // Divisions setup
    Volt::route('division/{division}/edit/{step?}', 'events.division.edit')
        ->name('events.division.edit');
    Volt::route('division/{division}/run', 'events.division.run')
        ->name('events.division.run');
    Volt::route('division/judge/{pool}', 'events.division.judge')
        ->name('events.division.judge');

    // Admin
    Volt::route('admin/members', 'admin.index')
        ->name('admin.index');
    Volt::route('admin/dashboard', 'admin.dashboard')
        ->name('admin.dashboard');
});

// EVENT CALENDAR
Volt::route('events', 'events.index')
    ->name('events.index');
Volt::route('events/past/{year?}', 'events.index')
    ->name('events.index.past');
Volt::route('events/{event}', 'events.show')
    ->name('events.show');

Volt::route('geocoding', 'geocoding-test')
    ->name('geocoding');

Volt::route('maps-integration', 'maps-integration')
    ->name('maps-integration');

// User pages
Route::middleware(['auth'])->group(function () {
    Volt::route('user/profile', 'user.profile')->name('user.profile');
    Volt::route('user/attending', 'user.attending')->name('user.attending');
    Volt::route('user/organizing', 'user.organizing')->name('user.organizing');
});

Volt::route('imprint', 'imprint')->name('imprint');

// User settings
Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__.'/auth.php';

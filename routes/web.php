<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Volt::route('/', 'home')->name('home');
Volt::route('/rankings', 'rankings')->name('rankings');

// Events
Route::middleware(['auth'])->group(function () {
    // event create / edit
    Volt::route('events/{event}/edit', 'events.edit')
        ->whereUlid('event')
        ->name('events.edit');
    Volt::route('events/create', 'events.create')
        ->name('events.create');

    // event admin
    Volt::route('events/{event}/admin/{tab?}', 'events.admin.index')
        ->whereUlid('event')
        ->name('events.admin');
    Volt::route('events/{event}/admin/schedule/edit', 'events.schedule.edit')
        ->whereUlid('event')
        ->name('events.schedule.edit');

    // organizers magic link
    Volt::route('organizers/invite/{id}', 'events.magic-link')
        ->whereUlid('id')
        ->name('events.admin.magic-link');

    // Divisions setup
    Volt::route('division/{division}/edit/{step?}', 'events.division.edit')
        ->whereUlid('division')
        ->name('events.division.edit');
    Volt::route('division/{division}/run', 'events.division.run')
        ->whereUlid('division')
        ->name('events.division.run');
    Volt::route('division/judge/{pool}', 'events.division.judge')
        ->whereUlid('pool')
        ->name('events.division.judge');

    // Admin
    Volt::route('admin/members', 'admin.index')
        ->name('admin.index');
    Volt::route('admin/members/{player}', 'admin.members.edit')
        ->whereUlid('player')
        ->name('admin.members.edit');
    Volt::route('admin/dashboard', 'admin.dashboard')
        ->name('admin.dashboard');
});

// EVENT CALENDAR
Volt::route('events', 'events.index')
    ->name('events.index');
Volt::route('events/past/{year?}', 'events.index')
    ->where('year', '[0-9]{4}')
    ->name('events.index.past');
Volt::route('events/{event}', 'events.show')
    ->whereUlid('event')
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
Volt::route('contact', 'contact')->name('contact');

// User settings
Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__.'/auth.php';

<?php

// Note: Laravel will automatically resolve `Breadcrumbs::` without
// this import. This is nice for IDE syntax and refactoring.
use Diglactic\Breadcrumbs\Breadcrumbs;

// This import is also not required, and you could replace `BreadcrumbTrail $trail`
//  with `$trail`. This is nice for IDE type checking and completion.
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

// Home
Breadcrumbs::for('home', function (BreadcrumbTrail $trail) {
    $trail->push('Home', route('home'));
});

// Events
Breadcrumbs::for('events.index', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Upcoming Events', route('events.index'));
});

// Events > [Event]
Breadcrumbs::for('events.show', function (BreadcrumbTrail $trail, $event) {
    $trail->parent('events.index');
    $trail->push($event->name, route('events.show', $event));
});

// Events > [Event] > Edit
Breadcrumbs::for('events.edit', function (BreadcrumbTrail $trail, $event) {

    $trail->parent('events.index');
    $trail->push($event->name, route('events.show', $event));
    $trail->push('Edit', route('events.edit', $event));
});

// Events > [Event] > Admin
Breadcrumbs::for('events.admin', function (BreadcrumbTrail $trail, $event) {
    $trail->parent('home');
    $trail->parent('events.index');
    $trail->push($event->name, route('events.show', $event));
    $trail->push('Admin', route('events.admin', $event));
});

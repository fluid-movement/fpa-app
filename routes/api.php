<?php

use Illuminate\Support\Facades\Route;

Route::get('/events/upcoming', [\App\Http\Controllers\Api\EventController::class, 'upcoming']);

<?php

use IlluminateSupportFacadesRoute;

Route::get('/events/upcoming', [AppHttpControllersApiEventController::class, 'upcoming']);

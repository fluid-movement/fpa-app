<?php

namespace AppHttpControllersApi;

use AppHttpControllersController;
use AppModelsEvent;
use IlluminateHttpJsonResponse;

class EventController extends Controller
{
    /**
     * Get all upcoming events with full details
     *
     * @return JsonResponse
     */
    public function upcoming(): JsonResponse
    {
        $events = Event::with(['schedule', 'attending'])
            ->where('end_date', '>=', now())
            ->orderBy('start_date', 'asc')
            ->get();

        $response = $events->map(function (Event $event) {
            return [
                'id' => $event->id,
                'name' => $event->name,
                'start_date' => $event->start_date,
                'end_date' => $event->end_date,
                'location' => $event->location,
                'description' => $event->description,
                'image_url' => $event->picture_url,
                'detail_page_url' => route('events.show', ['event' => $event->id]),
                'attending_players' => $event->attending->map(function ($user) {
                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                    ];
                })->toArray(),
                'attending_count' => $event->attending_count,
                'schedule' => $event->schedule->map(function ($schedule) {
                    return [
                        'id' => $schedule->id,
                        'name' => $schedule->name,
                        'start_date' => $schedule->start_date,
                        'end_date' => $schedule->end_date,
                        'description' => $schedule->description,
                        'location' => $schedule->location,
                        'time' => $schedule->time,
                    ];
                })->toArray(),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $response,
            'count' => $response->count(),
        ]);
    }
}

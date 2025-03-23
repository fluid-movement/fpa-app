<?php

namespace App\Services;

use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class EventCalendarService
{
    public static function getFormattedCalendar(Collection $events): array
    {
        $groupedEvents = [];
        foreach ($events as $event) {
            $date = Carbon::parse($event->start_date); // Assuming 'date' is your field
            $year = $date->format('Y');
            $month = $date->format('F');
            $groupedEvents[$year][$month][] = $event;
        }

        return $groupedEvents;
    }

    public static function getArchiveYears(): array
    {
        return DB::table('events')
            ->selectRaw('EXTRACT(YEAR FROM start_date) as year')
            ->distinct()
            ->where('start_date', '<', now())
            ->orderByDesc('year')
            ->get()
            ->pluck('year')
            ->toArray();
    }
}

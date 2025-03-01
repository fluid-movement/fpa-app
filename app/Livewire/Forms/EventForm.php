<?php

namespace App\Livewire\Forms;

use App\Models\Event;
use Flux\DateRange;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Form;
use Livewire\WithFileUploads;

class EventForm extends Form
{
    use WithFileUploads;

    public Event $event;

    public DateRange $dateRange;

    #[Validate('required|string|max:50')]
    public string $name = '';

    #[Validate('required|date|after_or_equal:today')]
    public string $start_date = '';

    #[Validate('required|date|after_or_equal:start_date')]
    public string $end_date = '';

    #[Validate('required|string|max:255')]
    public string $location = '';

    #[Validate('required|string')]
    public string $description = '';

    #[Validate('image')]
    public string $banner = '';

    #[Validate('image')]
    public string $icon = '';

    public function setEvent(Event $event): void
    {
        $this->event = $event;
        $this->name = $event->name;
        $this->start_date = $event->start_date;
        $this->end_date = $event->end_date;
        $this->dateRange = new DateRange($event->start_date, $event->end_date);

        $this->location = $event->location;
        $this->description = $event->description;
        $this->banner = $event->banner;
        $this->icon = $event->icon;
    }

    public function store(): Event
    {
        $this->start_date = $this->dateRange->start->format('Y-m-d');
        $this->end_date = $this->dateRange->end->format('Y-m-d');

        if ($this->banner) {
            $this->banner = $this->banner->store('banners');
        }

        if ($this->icon) {
            $this->icon = $this->icon->store('icons');
        }

        return Auth::user()->events()->create($this->only([
            'name',
            'start_date',
            'end_date',
            'location',
            'description',
            'banner',
            'icon',
        ]));
    }

    public function update(): void
    {
        $this->start_date = $this->dateRange->start;
        $this->end_date = $this->dateRange->end;

        if ($this->banner) {
            $this->banner = $this->banner->store('banners');
        }

        if ($this->icon) {
            $this->icon = $this->icon->store('icons');
        }

        $this->event->update($this->only([
            'name',
            'start_date',
            'end_date',
            'location',
            'description',
            'banner',
            'icon',
        ]));
    }
}

<?php

namespace App\Livewire\Forms;

use App\Enums\AssetType;
use App\Models\Event;
use Flux\DateRange;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Validate;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\Form;

class EventForm extends Form
{
    public Event $event;

    #[Validate('required')]
    public DateRange $dateRange;

    #[Validate('required|string|max:50', message: 'Your event needs a name.')]
    public string $name = '';

    #[Validate('nullable|date')]
    public string $start_date = '';

    #[Validate('nullable|date')]
    public string $end_date = '';

    #[Validate('required|string|max:255', message: 'Where is the event taking place?')]
    public string $location = '';

    #[Validate('required|string')]
    public string $description = '';

    #[Validate('nullable|image')]
    public $pictureUpload;

    public ?string $picture = '';

    public function setEvent(Event $event): void
    {
        $this->event = $event;
        $this->name = $event->name;
        $this->start_date = $event->start_date;
        $this->end_date = $event->end_date;
        $this->dateRange = new DateRange($event->start_date, $event->end_date);

        $this->location = $event->location;
        $this->description = $event->description;
        $this->picture = $event->picture;
    }

    public function store(): Event
    {
        $this->validate();

        $this->setDatesFromRange();

        $this->uploadImages();

        $event = Auth::user()->events()->create($this->only([
            'name',
            'start_date',
            'end_date',
            'location',
            'description',
            'picture',
        ]));

        $event->users()->attach(Auth::id(), ['status' => 'organizing']);

        return $event;
    }

    public function update(): void
    {
        $this->validate();

        $this->setDatesFromRange();
        $this->uploadImages();

        $this->event->update($this->only([
            'name',
            'start_date',
            'end_date',
            'location',
            'description',
            'picture',
        ]));
    }

    public function uploadImages(): void
    {
        if ($this->pictureUpload instanceof TemporaryUploadedFile) {
            $path = AssetType::Picture->getPath();
            if ($newPicture = basename($this->pictureUpload->store($path, 'public'))) {
                if ($this->picture) {
                    Storage::disk('public')->delete($path.'/'.$this->picture);
                }
                $this->picture = $newPicture;
            }
        }
    }

    public function setDatesFromRange(): void
    {
        if (! isset($this->dateRange)) {
            throw ValidationException::withMessages([
                'form.dateRange' => 'When is your event taking place?',
            ]);
        }

        $this->start_date = $this->dateRange->start?->format('Y-m-d');
        $this->end_date = $this->dateRange->end?->format('Y-m-d');
    }
}

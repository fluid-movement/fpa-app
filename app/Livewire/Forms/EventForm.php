<?php

namespace App\Livewire\Forms;

use App\Enums\AssetType;
use App\Models\Event;
use App\Services\AssetManagerService;
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
    public string $start_date = ''; // don't need to validate this, as it is set from the dateRange

    #[Validate('nullable|date')]
    public string $end_date = ''; // don't need to validate this, as it is set from the dateRange

    #[Validate('required|string|max:255', message: 'Where is the event taking place?')]
    public string $location = '';

    #[Validate('required|string')]
    public string $description = '';

    #[Validate('nullable|image')]
    public $pictureUpload;

    public ?string $picture = '';

    public ?int $picture_width = null;

    public ?int $picture_height = null;

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
        $this->picture_width = $event->picture_width;
        $this->picture_height = $event->picture_height;
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
            'picture_width',
            'picture_height',
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
            'picture_width',
            'picture_height',
        ]));
    }

    public function uploadImages(): void
    {
        if ($this->pictureUpload instanceof TemporaryUploadedFile) {
            // Get image dimensions before uploading
            $imagePath = $this->pictureUpload->getRealPath();
            [$width, $height] = getimagesize($imagePath);

            $assetManager = app(AssetManagerService::class);
            if ($newPicture = $assetManager->storeTemporary(AssetType::Picture, $this->pictureUpload)) {
                if ($this->picture) {
                    $assetManager->delete(AssetType::Picture, $this->picture);
                }
                $this->picture = $newPicture;
                $this->picture_width = $width;
                $this->picture_height = $height;
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

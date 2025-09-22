<?php

namespace App\Livewire\Forms;

use App\Enums\AssetType;
use App\Models\Event;
use App\Services\AssetManagerService;
use Exception;
use Flux\DateRange;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Validate;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\Form;

class EventForm extends Form
{
    private array $fields = [
        'name',
        'start_date',
        'end_date',
        'location',
        'description',
        'picture',
        'picture_width',
        'picture_height',
    ];

    public Event $event;

    #[Validate('required')]
    public DateRange $dateRange;

    #[Validate('required|string|max:80', message: 'Your event needs a name.')]
    public string $name = '';

    #[Validate('nullable|date')]
    public string $start_date = ''; // don't need to validate this, as it is set from the dateRange

    #[Validate('nullable|date')]
    public string $end_date = ''; // don't need to validate this, as it is set from the dateRange

    #[Validate('required|string|max:30', message: 'Where is the event taking place?')]
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
        $this->dateRange = new DateRange($event->start_date, $event->end_date);
        foreach ($this->fields as $field) {
            $this->$field = $event->$field;
        }
    }

    /**
     * @throws Exception
     */
    public function store(): Event
    {
        $this->validate();

        $this->setDatesFromRange();
        $this->uploadImages();

        $event = Auth::user()->events()->create($this->only($this->fields));
        $event->users()->attach(Auth::id(), ['status' => 'organizing']);

        return $event;
    }

    /**
     * @throws Exception
     */
    public function update(): void
    {
        $this->validate();

        $this->setDatesFromRange();
        $this->uploadImages();

        $this->event->update($this->only($this->fields));
    }

    /**
     * @throws Exception
     */
    public function uploadImages(): void
    {
        if (!($this->pictureUpload instanceof TemporaryUploadedFile)) {
            \Log::debug('EventForm: No picture upload or invalid file type');
            return;
        }

        // Get image dimensions before uploading
        $imagePath = $this->pictureUpload->getRealPath();

        [$width, $height] = getimagesize($imagePath);

        $assetManager = app(AssetManagerService::class);

        try {
            $newPicture = $assetManager->storeTemporary(AssetType::Picture, $this->pictureUpload);

            if ($newPicture) {
                if ($this->picture) {
                    $assetManager->delete(AssetType::Picture, $this->picture);
                }

                $this->picture = $newPicture;
                $this->picture_width = $width;
                $this->picture_height = $height;
            } else {
                \Log::error('EventForm: Picture upload failed - storeTemporary returned false');
            }
        } catch (Exception $e) {
            \Log::error('EventForm: Exception during picture upload', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function setDatesFromRange(): void
    {
        if (!isset($this->dateRange)) {
            throw ValidationException::withMessages([
                'form.dateRange' => 'When is your event taking place?',
            ]);
        }

        $this->start_date = $this->dateRange->start?->format('Y-m-d');
        $this->end_date = $this->dateRange->end?->format('Y-m-d');
    }
}

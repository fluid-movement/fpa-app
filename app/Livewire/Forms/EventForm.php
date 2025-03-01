<?php

namespace App\Livewire\Forms;

use App\Core\Enum\AssetType;
use App\Core\Service\AssetManagerService;
use App\Models\Event;
use Flux\DateRange;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Validate;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\Form;

class EventForm extends Form
{
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
    public $bannerUpload;
    public ?string $banner = '';

    #[Validate('image')]
    public $iconUpload;
    public ?string $icon = '';

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
        $this->setDatesFromRange();
        $this->uploadImages();

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
        $this->setDatesFromRange();
        $this->uploadImages();

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


    public function uploadImages(): void
    {
        if ($this->bannerUpload instanceof TemporaryUploadedFile) {
            $path = AssetType::BANNER->getPath();
            if ($newBanner = basename($this->bannerUpload->store($path, 'public'))) {
                if ($this->banner) {
                    Storage::disk('public')->delete($path.'/'.$this->banner);
                }
                $this->banner = $newBanner;
            }
        }
        if ($this->iconUpload instanceof TemporaryUploadedFile) {
            $path = AssetType::ICON->getPath();
            if ($newIcon = basename($this->iconUpload->store($path, 'public'))) {
                if ($this->icon) {
                    Storage::disk('public')->delete($path.'/'.$this->icon);
                }
                $this->icon = $newIcon;
            }
        }
    }

    public function setDatesFromRange(): void
    {
        $this->start_date = $this->dateRange->start->format('Y-m-d');
        $this->end_date = $this->dateRange->end->format('Y-m-d');
    }
}

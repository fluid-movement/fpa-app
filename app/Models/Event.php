<?php

namespace App\Models;

use App\Enums\AssetType;
use App\Enums\EventUserStatus;
use App\Models\Scopes\OrderByStartAsc;
use App\Observers\EventObserver;
use App\Services\AssetManagerService;
use Database\Factories\EventFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

/**
 * Event model
 *
 * @property string $id
 * @property string $user_id
 * @property-read User $user
 * @property string $name
 * @property string $start_date
 * @property string $end_date
 * @property string $location
 * @property string $description
 * @property string $banner
 * @property string $icon
 * @property-read string $banner_url
 * @property-read array $banner_width_height
 * @property-read string $icon_url
 * @property-read string $day
 * @property-read string $month
 * @property-read string $year
 * @property-read EventMagicLink $magic_link
 * @property-read Schedule[] $schedule
 * @property-read Division[] $divisions
 * @property-read User[] $users
 * @property-read User[] $attending
 * @property-read User[] $organizers
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
#[ObservedBy(EventObserver::class)]
#[ScopedBy(OrderByStartAsc::class)]
class Event extends Model
{
    /** @use HasFactory<EventFactory> */
    use HasFactory, HasUlids;

    // add description manually so the rich text editor can save it
    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'location',
        'description',
        'banner',
        'icon',
    ];

    protected $casts = [
        'start_date' => 'datetime:Y-m-d',
        'end_date' => 'datetime:Y-m-d',
    ];

    public function getBannerUrlAttribute(): ?string
    {
        return $this->banner
            ? app(AssetManagerService::class)
                ->url(AssetType::Banner, $this->banner)
            : null;
    }

    public function getBannerWidthHeight(): array
    {
        return app(AssetManagerService::class)
            ->dimensions(AssetType::Banner, $this->banner);
    }

    public function getIconUrlAttribute(): ?string
    {
        return $this->icon
            ? app(AssetManagerService::class)
                ->url(AssetType::Icon, $this->icon)
            : null;
    }

    public function getDayAttribute(): string
    {
        return date('j', strtotime($this->start_date));
    }

    public function getMonthAttribute(): string
    {
        return date('F', strtotime($this->start_date));
    }

    public function getYearAttribute(): string
    {
        return date('Y', strtotime($this->start_date));
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function magicLink(): HasOne
    {
        return $this->hasOne(EventMagicLink::class);
    }

    public function schedule(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }

    public function divisions(): HasMany
    {
        return $this->hasMany(Division::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot('status')
            ->withTimestamps()
            ->orderByDesc('event_user.updated_at');
    }

    public function attending(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->wherePivotIn('status', [EventUserStatus::Attending, EventUserStatus::Organizing])
            ->withTimestamps()
            ->orderByDesc('event_user.updated_at');
    }

    public function organizers(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->wherePivot('status', EventUserStatus::Organizing)
            ->withTimestamps()
            ->orderByDesc('event_user.updated_at');
    }
}

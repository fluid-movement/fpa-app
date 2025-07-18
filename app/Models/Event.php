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
 * @property string $picture
 * @property-read string $picture_url
 * @property-read array $picture_width_height
 * @property-read string $day
 * @property-read string $month
 * @property-read string $year
 * @property-read EventMagicLink $magic_link
 * @property-read Schedule[] $schedule
 * @property-read Division[] $divisions
 * @property-read User[] $users
 * @property-read User[] $attending
 * @property-read int $attending_count
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

    protected $guarded = ['id'];

    protected $casts = [
        'start_date' => 'datetime:Y-m-d',
        'end_date' => 'datetime:Y-m-d',
    ];

    public int $attending_count {
        get => $this->belongsToMany(User::class)
            ->wherePivotIn('status', [EventUserStatus::Attending, EventUserStatus::Organizing])
            ->count();
    }

    public ?string $picture_url {
        get => $this->picture
            ? app(AssetManagerService::class)->url(AssetType::Picture, $this->picture)
            : null;
    }

    public string $day {
        get => date('j', strtotime($this->start_date));
    }

    public string $month {
        get => date('F', strtotime($this->start_date));
    }

    public string $year {
        get => date('Y', strtotime($this->start_date));
    }

    public function getPictureWidthHeight(): array
    {
        return app(AssetManagerService::class)->dimensions(AssetType::Picture, $this->picture);
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

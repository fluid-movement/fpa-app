<?php

namespace App\Models;

use App\Models\Scopes\OrderByStartAsc;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * Schedule model
 *
 * @property string $id
 * @property string $event_id
 * @property string $name
 * @property string $start_date
 * @property string $end_date
 * @property string $description
 * @property string $location
 * @property float $longitude
 * @property float $latitude
 * @property-read string $time
 * @property-read Event $event
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
#[ScopedBy(OrderByStartAsc::class)]
class Schedule extends Model
{
    use HasUlids;

    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'description',
        'location',
        'longitude',
        'latitude',
    ];

    protected $casts = [
        'start_date' => 'datetime:Y-m-d H:i',
        'end_date' => 'datetime:Y-m-d H:i',
    ];

    public function getTimeAttribute(): string
    {
        return $this->start_date->format('H:i').' - '.$this->end_date->format('H:i');
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
}

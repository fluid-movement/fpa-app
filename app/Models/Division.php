<?php

namespace App\Models;

use App\Enums\Division\DivisionType;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * Division model
 *
 * @property string $id
 * @property string $event_id
 * @property-read Event $event
 * @property DivisionType $type
 * @property int $teams_per_pool
 * @property int $advance_per_pool
 * @property-read Round[] $rounds
 * @property-read Team[] $teams
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Division extends Model
{
    use HasUlids;

    protected $guarded = ['id'];

    protected $casts = [
        'type' => DivisionType::class,
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function rounds(): HasMany
    {
        return $this->hasMany(Round::class);
    }

    public function teams(): HasMany
    {
        return $this->hasMany(Team::class);
    }
}

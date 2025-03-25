<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * Round model
 *
 * @property string $id
 * @property string $division_id
 * @property-read Division $division
 * @property string $name
 * @property-read Pool[] $pools
 * @property-read Event $event
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Round extends Model
{
    use HasUlids;

    protected $guarded = ['id'];

    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class);
    }

    public function pools(): HasMany
    {
        return $this->hasMany(Pool::class);
    }

    public function event(): BelongsTo
    {
        return $this->division()->belongsTo(Event::class);
    }
}

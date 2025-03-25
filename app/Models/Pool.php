<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Pool model
 *
 * @property string $id
 * @property string $round_id
 * @property-read Round $round
 * @property string $name
 * @property-read Team[] $teams
 * @property-read Event $event
 */
class Pool extends Model
{
    use HasUlids;

    protected $guarded = ['id'];

    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class);
    }

    public function round(): BelongsTo
    {
        return $this->belongsTo(Round::class);
    }

    public function event(): BelongsTo
    {
        return $this->round()->division()->belongsTo(Event::class);
    }
}

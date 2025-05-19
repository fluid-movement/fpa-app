<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Team model
 *
 * @property string $id
 * @property string $division_id
 * @property-read Division $division
 * @property-read Player[] $players
 * @property-read Event $event
 */
class Team extends Model
{
    use HasUlids;

    protected $guarded = ['id'];

    protected $with = ['players']; // we will never use teams without players

    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class);
    }

    public function players(): belongsToMany
    {
        return $this->belongsToMany(Player::class);
    }

    public function event()
    {
        return $this->division->event;
    }
}

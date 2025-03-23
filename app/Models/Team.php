<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Team extends Model
{
    use HasUlids;

    protected $guarded = ['id'];

    protected $with = ['players'];

    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class);
    }

    public function players(): belongsToMany
    {
        return $this->belongsToMany(Player::class);
    }

    public function event(): BelongsTo
    {
        return $this->division()->belongsTo(Event::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

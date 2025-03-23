<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActiveYear extends Model
{
    protected $fillable = ['year'];

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }
}

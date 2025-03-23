<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Player extends Model
{
    use HasFactory, HasUlids;

    protected $guarded = ['id'];

    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class);
    }

    public function activeYears(): hasMany
    {
        return $this->hasMany(ActiveYear::class);
    }
}

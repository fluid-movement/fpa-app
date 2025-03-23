<?php

namespace App\Models;

use App\Enums\Division\DivisionType;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Division extends Model
{
    use HasUlids;

    protected $guarded = ['id'];

    public function event(): belongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function rounds(): hasMany
    {
        return $this->hasMany(Round::class);
    }

    public function teams(): hasMany
    {
        return $this->hasMany(Team::class);
    }

    protected function casts(): array
    {
        return [
            'type' => DivisionType::class,
        ];
    }
}

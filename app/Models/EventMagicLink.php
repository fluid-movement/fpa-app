<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventMagicLink extends Model
{
    use HasUlids;

    protected $fillable = [
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
        ];
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function isActive(): bool
    {
        return $this->expires_at > now();
    }

    public function getLinkAttribute(): string
    {
        return route(
            'events.admin.magic-link', ['id' => $this->id]
        );
    }
}

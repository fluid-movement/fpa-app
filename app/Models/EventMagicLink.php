<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * EventMagicLink model
 *
 * @property string $id
 * @property string $event_id
 * @property string $expires_at
 * @property-read string $link
 * @property-read bool $is_active
 * @property-read Event $event
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class EventMagicLink extends Model
{
    use HasUlids;

    protected $fillable = [
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function isActive(): bool
    {
        return $this->expires_at > now();
    }
}

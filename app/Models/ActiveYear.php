<?php

namespace App\Models;

use App\Enums\MembershipType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * ActiveYear model
 *
 * @property string $id
 * @property string $player_id
 * @property-read Player $player
 * @property int year
 * @property MembershipType $membership_type
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class ActiveYear extends Model
{
    protected $fillable = ['year'];

    protected $casts = [
        'year' => 'integer',
        'membership_type' => MembershipType::class,
    ];

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }
}

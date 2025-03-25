<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * Player model
 *
 * @property string $id
 * @property string $user_id
 * @property-read User $user
 * @property string $name
 * @property string $surname
 * @property string $email
 * @property int $year_of_birth
 * @property string $gender
 * @property string $country
 * @property string $city
 * @property int $freestyling_since
 * @property int $first_competition
 * @property int $member_number
 * @property string $notes
 * @property-read Team[] $teams
 * @property-read ActiveYear[] $activeYears
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
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

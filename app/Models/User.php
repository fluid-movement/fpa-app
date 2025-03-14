<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Core\Enum\EventUserStatus;
use App\Core\Enum\UserRole;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, HasUlids, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->map(fn (string $name) => Str::of($name)->substr(0, 1))
            ->implode('');
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    public function organizingEvents(): BelongsToMany
    {
        return $this->belongsToMany(Event::class)
            ->wherePivot('status', EventUserStatus::ORGANIZING)
            ->whereDate('events.end_date', '>=', now());
    }

    public function organizedEvents(): BelongsToMany
    {
        return $this->belongsToMany(Event::class)
            ->wherePivot('status', EventUserStatus::ORGANIZING)
            ->whereDate('events.end_date', '<', now());
    }

    public function interestedEvents(): BelongsToMany
    {
        return $this->belongsToMany(Event::class)
            ->wherePivotIn('status', [EventUserStatus::INTERESTED, EventUserStatus::ATTENDING])
            ->whereDate('events.end_date', '>=', now());
    }

    public function attendingEvents(): BelongsToMany
    {
        return $this->belongsToMany(Event::class)
            ->wherePivot('status', EventUserStatus::ATTENDING)
            ->whereDate('events.end_date', '>=', now());
    }

    public function isAdmin(): bool
    {
        return $this->role->isAdmin();
    }
}

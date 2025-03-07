<?php

namespace App\Models;

use App\Models\Scopes\OrderByStartAsc;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


#[ScopedBy(OrderByStartAsc::class)]
class Schedule extends Model
{
    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'description',
        'location',
        'longitude',
        'latitude',
    ];

    public function casts()
    {
        return [
            'start_date' => 'datetime:Y-m-d H:i',
            'end_date' => 'datetime:Y-m-d H:i',
        ];
    }

    public function getTimeAttribute(): string
    {
        return $this->start_date->format('H:i') . ' - ' . $this->end_date->format('H:i');
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
}

<?php

namespace App\Models;

use App\Enums\Division\JudgingSystemType;
use App\Services\Division\JudgingStrategyFactory;
use App\Services\Division\JudgingStrategyInterface;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Result extends Model
{
    use hasUlids;

    protected $guarded = ['id'];

    protected $casts = [
        'data' => 'array',
        'judging_type' => JudgingSystemType::class,
    ];

    public function pool(): belongsTo
    {
        return $this->belongsTo(Pool::class);
    }

    public function user(): belongsTo
    {
        return $this->belongsTo(User::class, 'id');
    }

    public function team(): belongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function getStrategy(): JudgingStrategyInterface
    {
        return JudgingStrategyFactory::make($this->judging_type, $this);
    }

    public function calculateScore(): float
    {
        return $this->getStrategy()->calculateScore();
    }
}

<?php

namespace App\Enums\Division;

enum JudgingSystemType: string
{
    case SIMPLE = 'simple'; // scores from 1-9
    case CUSTOM_FIELDS = 'custom_fields'; // arbitrary fields
    case VIBES = 'vibes'; // thumbs up or down
}

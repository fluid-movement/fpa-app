<?php

namespace App\Enums\Division;

enum JudgingSystemType: string
{
    case Simple = 'simple'; // scores from 1-9
    case CustomFields = 'custom_fields'; // arbitrary fields
    case Vibes = 'vibes'; // thumbs up or down
}

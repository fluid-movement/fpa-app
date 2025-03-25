<?php

namespace App\Enums;

enum EventUserStatus: string
{
    case Attending = 'attending';
    case Organizing = 'organizing';
}

<?php

namespace App\Enums;

enum AssetType: string
{
    case BANNER = 'banners';
    case ICON = 'icons';

    public function getPath(): string
    {
        return $this->value;
    }
}

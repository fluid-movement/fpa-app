<?php

namespace App\Enums;

enum AssetType: string
{
    case Banner = 'banners';
    case Icon = 'icons';

    public function getPath(): string
    {
        return $this->value;
    }
}

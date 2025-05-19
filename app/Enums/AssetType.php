<?php

namespace App\Enums;

enum AssetType: string
{
    case Picture = 'pictures';

    public function getPath(): string
    {
        return $this->value;
    }
}

<?php

declare(strict_types=1);

namespace App\Services\Catalog\Onliner\Enum;

enum ImageTypeEnum: int
{
    case Good = 1;

    public function getLabel(): string
    {
        return match ($this) {
            self::Good => 'Good',
        };
    }
}

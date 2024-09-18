<?php

declare(strict_types=1);

namespace App\Services\Catalog\Enum;

enum CatalogTypeEnum: int
{
    case CUSTOM = 1;
    case ONLINER = 2;
    case WILDBERRIES = 3;

    public static function getSelectList(): array
    {
        return [
            self::CUSTOM->value      => 'Свой',
            self::ONLINER->value     => 'Onliner',
            self::WILDBERRIES->value => 'Wildberries',
        ];
    }
}

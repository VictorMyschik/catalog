<?php

declare(strict_types=1);

namespace App\Orchid\Screens\System\Enum;

enum CronKeyEnum: string
{
    case UPDATE_CATALOG_GOODS = 'update_catalog_goods';

    public static function getSelectList(): array
    {
        return [
            self::UPDATE_CATALOG_GOODS->value => 'Обновление каталога товаров',
        ];
    }
}

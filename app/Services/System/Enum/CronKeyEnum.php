<?php

declare(strict_types=1);

namespace App\Services\System\Enum;

enum CronKeyEnum: string
{
    case OnlinerCatalogGoods = 'onliner_update_catalog_goods';
    case WildberriesCatalogStructure = 'wb_update_catalog_structure';
    case ClearLogs = 'clear_logs';

    public static function getSelectList(): array
    {
        return [
            self::OnlinerCatalogGoods->value => 'Обновление каталога товаров Onliner',
            self::WildberriesCatalogStructure->value => 'Обновление структуры каталога Wildberries',
            self::ClearLogs->value => 'Очистка логов',
        ];
    }
}

<?php

declare(strict_types=1);

namespace App\Jobs;

enum JobsEnum: string
{
    case UpdateCatalog = 'update_catalog';
    case WBUpdateCatalogGroups = 'wb_update_catalog_groups';
}

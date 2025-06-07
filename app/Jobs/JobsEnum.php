<?php

declare(strict_types=1);

namespace App\Jobs;

enum JobsEnum: string
{
    case OnlinerCatalog = 'onliner_catalog';
    case WBCatalog = 'wb_catalog';
}

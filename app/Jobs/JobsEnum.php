<?php

declare(strict_types=1);

namespace App\Jobs;

enum JobsEnum: string
{
    case UpdateCatalog = 'update_catalog';
}

<?php

declare(strict_types=1);

namespace App\Models\Catalog\Wildberries;

use App\Models\ORM\ORM;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class WBCountry extends ORM
{
    use AsSource;
    use Filterable;

    protected $table = 'wb_catalog_countries';
}

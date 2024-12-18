<?php

declare(strict_types=1);

namespace App\Models\Catalog\Wildberries;

use App\Models\Lego\Fields\NameFieldTrait;
use App\Models\ORM\ORM;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class WBCatalogBrand extends ORM
{
    use AsSource;
    use Filterable;
    use NameFieldTrait;

    protected $table = 'wb_catalog_brands';

    protected $fillable = [
        'name',
    ];
}

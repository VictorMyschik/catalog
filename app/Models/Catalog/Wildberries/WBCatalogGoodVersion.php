<?php

declare(strict_types=1);

namespace App\Models\Catalog\Wildberries;

use App\Models\ORM\ORM;

class WBCatalogGoodVersion extends ORM
{
    public const null UPDATED_AT = null;

    protected $table = 'wb_catalog_good_versions';

    protected $casts = [
        'created_at' => 'datetime',
    ];
}
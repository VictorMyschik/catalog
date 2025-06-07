<?php

declare(strict_types=1);

namespace App\Models\Catalog\Wildberries;

use App\Models\ORM\ORM;

class WBCatalogNotFound extends ORM
{
    protected $table = 'wb_catalog_not_found';

    protected $fillable = [
        'wb_id',
    ];

    public $timestamps = false;
}

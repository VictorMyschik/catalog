<?php

declare(strict_types=1);

namespace App\Models\Catalog\Wildberries;

use App\Models\Lego\Fields\NameFieldTrait;
use App\Models\ORM\ORM;

class WBCatalogGroup extends ORM
{
    use NameFieldTrait;

    protected $table = 'wb_catalog_groups';

    public const UPDATED_AT = null;

    protected $fillable = [
        'id',
        'name',
        'parent_id',
    ];

    public function getParentId(): ?int
    {
        return $this->parent_id;
    }
}

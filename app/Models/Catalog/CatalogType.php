<?php

declare(strict_types=1);

namespace App\Models\Catalog;

use App\Models\Lego\Fields\JsonFieldTrait;
use App\Models\Lego\Fields\NameFieldTrait;
use App\Models\ORM\ORM;

class CatalogType extends ORM
{
    use NameFieldTrait;
    use JsonFieldTrait;

    protected $table = 'catalog_types';

    protected $casts = [
        'id'        => 'int',
        'name'      => 'string',
        'sl'        => 'json',
        'json_link' => 'string',
    ];

    public function getJonLink(): ?string
    {
        return $this->json_link;
    }
}
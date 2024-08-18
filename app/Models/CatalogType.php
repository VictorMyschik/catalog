<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Lego\Traits\JsonFieldTrait;
use App\Models\Lego\Traits\NameFieldTrait;
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
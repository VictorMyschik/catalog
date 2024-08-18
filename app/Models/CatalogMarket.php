<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Lego\Traits\NameFieldTrait;
use App\Models\ORM\ORM;

class CatalogMarket extends ORM
{
    use NameFieldTrait;

    protected $table = 'catalog_markets';

    protected $casts = [
        'id'         => 'int',
        'name'       => 'string',
        'market_id'  => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function getMarketId(): string
    {
        return $this->market_id;
    }
}
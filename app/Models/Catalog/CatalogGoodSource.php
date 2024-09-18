<?php

declare(strict_types=1);

namespace App\Models\Catalog;

use App\Models\Lego\Fields\DescriptionNullableFieldTrait;
use App\Models\Lego\Fields\JsonFieldTrait;
use App\Models\Lego\Fields\MrGoodFieldTrait;
use App\Models\ORM\ORM;
use App\Services\Catalog\Enum\CatalogTypeEnum;

class CatalogGoodSource extends ORM
{
    use MrGoodFieldTrait;
    use DescriptionNullableFieldTrait;
    use JsonFieldTrait;

    protected $table = 'catalog_good_sources';

    protected $casts = [
        'id'          => 'int',
        'good_id'     => 'int',
        'type'        => 'int',
        'int_id'      => 'int',
        'string_id'   => 'string',
        'link'        => 'string',
        'description' => 'string',
        'sl'          => 'json',
        'created_at'  => 'datetime',
        'updated_at'  => 'datetime',
    ];

    public function getGoodId(): int
    {
        return $this->good_id;
    }

    public function getType(): CatalogTypeEnum
    {
        return CatalogTypeEnum::from($this->type);
    }

    public function getIntId(): int
    {
        return $this->int_id;
    }

    public function getStringId(): string
    {
        return $this->string_id;
    }

    public function getLink(): string
    {
        return $this->link;
    }
}
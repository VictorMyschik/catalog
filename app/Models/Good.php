<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Lego\Traits\DescriptionNullableFieldTrait;
use App\Models\Lego\Traits\JsonFieldTrait;
use App\Models\Lego\Traits\NameFieldTrait;
use App\Models\ORM\ORM;

class Good extends ORM
{
    use NameFieldTrait;
    use JsonFieldTrait;
    use DescriptionNullableFieldTrait;

    protected $table = 'goods';

    protected $casts = [
        'id'               => 'integer',
        'type_id'          => 'integer',
        'prefix'           => 'string',
        'name'             => 'string',
        'short_info'       => 'string',
        'description'      => 'string',
        'manufacturer_id'  => 'integer',
        'parent_good_id'   => 'integer',
        'is_certification' => 'boolean',
        'sl'               => 'json',
        'int_id'           => 'integer',
        'string_id'        => 'string',
        'link'             => 'string',
        'created_at'       => 'datetime',
        'updated_at'       => 'datetime',
    ];

    public function getTypeId(): int
    {
        return $this->type_id;
    }

    public function getPrefix(): ?string
    {
        return $this->prefix;
    }

    public function getShortInfo(): ?string
    {
        return $this->short_info;
    }

    public function getManufacturerId(): int
    {
        return $this->manufacturer_id;
    }

    public function getParentGoodId(): ?int
    {
        return $this->parent_good_id;
    }

    public function isCertification(): bool
    {
        return $this->is_certification;
    }
}
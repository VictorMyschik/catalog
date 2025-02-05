<?php

declare(strict_types=1);

namespace App\Models\Catalog;

use App\Models\Lego\Fields\DescriptionNullableFieldTrait;
use App\Models\Lego\Fields\JsonFieldTrait;
use App\Models\Lego\Fields\NameFieldTrait;
use App\Models\ORM\ORM;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class Good extends ORM
{
    use AsSource;
    use Filterable;
    use NameFieldTrait;
    use JsonFieldTrait;
    use DescriptionNullableFieldTrait;

    protected $table = 'goods';

    protected array $allowedSorts = [
        'id',
        'type_id',
        'prefix',
        'name',
        'parent_good_id',
        'int_id',
        'string_id',
        'link',
        'created_at',
        'updated_at',
    ];

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
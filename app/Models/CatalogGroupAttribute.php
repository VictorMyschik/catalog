<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Lego\Traits\NameFieldTrait;
use App\Models\Lego\Traits\SortFieldTrait;
use App\Models\ORM\ORM;

class CatalogGroupAttribute extends ORM
{
    use NameFieldTrait;
    use SortFieldTrait;

    protected $table = 'catalog_group_attributes';

    public $timestamps = false;
    protected $fillable = [
        'type_id',
        'name',
        'sort',
    ];

    protected $casts = [
        'id'      => 'int',
        'type_id' => 'int',
        'name'    => 'string',
        'sort'    => 'int',
    ];

    public function getTypeId(): int
    {
        return $this->type_id;
    }
}
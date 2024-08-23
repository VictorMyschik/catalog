<?php

declare(strict_types=1);

namespace App\Models\Catalog;

use App\Models\Lego\Fields\NameFieldTrait;
use App\Models\ORM\ORM;

class Manufacturer extends ORM
{
    use NameFieldTrait;

    protected $table = 'manufacturers';

    protected $fillable = [
        'name',
        'address',
    ];

    public const null UPDATED_AT = null;

    protected $casts = [
        'id'         => 'int',
        'name'       => 'string',
        'address'    => 'string',
        'created_at' => 'datetime',
    ];

    public function getAddress(): ?string
    {
        return $this->address;
    }
}
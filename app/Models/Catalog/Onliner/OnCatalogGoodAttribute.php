<?php

declare(strict_types=1);

namespace App\Models\Catalog\Onliner;

use App\Models\ORM\ORM;

class OnCatalogGoodAttribute extends ORM
{
    protected $table = 'on_good_attributes';

    protected $casts = [
        'good_id'            => 'int',
        'attribute_value_id' => 'int',
        'bool_value'         => 'bool',
    ];

    public function getGoodId(): int
    {
        return $this->good_id;
    }

    public function getAttributeValueId(): int
    {
        return $this->attribute_value_id;
    }

    public function getBoolValue(): ?bool
    {
        return $this->bool_value;
    }
}

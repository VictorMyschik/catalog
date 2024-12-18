<?php

declare(strict_types=1);

namespace App\Models\Catalog\Onliner;

use App\Models\ORM\ORM;

class OnCatalogAttributeValue extends ORM
{
    protected $table = 'on_catalog_attribute_values';

    protected $fillable = [
        'catalog_attribute_id',
        'text_value',
    ];

    public $timestamps = false;

    public function getTextValue(): string
    {
        return $this->text_value;
    }

    public function getCatalogAttributeId(): int
    {
        return $this->catalog_attribute_id;
    }
}

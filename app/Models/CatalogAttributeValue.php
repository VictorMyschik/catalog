<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\ORM\ORM;

class CatalogAttributeValue extends ORM
{
    protected $table = 'catalog_attribute_values';

    public function getTextValue(): string
    {
        return $this->text_value;
    }

    public function getCatalogAttributeId(): int
    {
        return $this->catalog_attribute_id;
    }
}
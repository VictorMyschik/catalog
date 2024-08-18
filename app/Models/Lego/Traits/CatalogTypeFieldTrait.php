<?php

namespace App\Models\Lego\Traits;


/**
 * Типы товаров
 */
trait CatalogTypeFieldTrait
{
    public function getKind(): CatalogType
    {
        return CatalogType::loadByOrDie($this->KindID);
    }

    public function setKindID(int $value): void
    {
        $this->KindID = $value;
    }
}
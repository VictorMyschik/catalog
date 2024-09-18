<?php

namespace App\Models\Lego\Fields;

use App\Models\Catalog\CatalogGood;

trait MrGoodFieldTrait
{
    public function getGood(): CatalogGood
    {
        return CatalogGood::loadByOrDie($this->good_id);
    }

    public function setGoodID(int $value): void
    {
        $this->good_id = $value;
    }
}
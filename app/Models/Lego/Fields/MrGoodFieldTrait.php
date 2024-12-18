<?php

namespace App\Models\Lego\Fields;

use App\Models\Catalog\OnCatalogGood;

trait MrGoodFieldTrait
{
    public function getGood(): OnCatalogGood
    {
        return OnCatalogGood::loadByOrDie($this->good_id);
    }

    public function setGoodID(int $value): void
    {
        $this->good_id = $value;
    }
}

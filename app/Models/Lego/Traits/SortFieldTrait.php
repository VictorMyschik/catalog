<?php

namespace App\Models\Lego\Traits;

trait SortFieldTrait
{
    public function getSort(): int
    {
        return $this->sort;
    }

    public function setSort(int $value): void
    {
        $this->sort = $value;
    }
}
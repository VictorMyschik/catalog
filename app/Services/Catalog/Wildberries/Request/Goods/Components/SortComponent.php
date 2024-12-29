<?php

declare(strict_types=1);

namespace App\Services\Wildberries\Import\Catalog\Request\Goods\Components;

final readonly class SortComponent
{
    public function __construct(
        public bool $ascending
    ) {}
}

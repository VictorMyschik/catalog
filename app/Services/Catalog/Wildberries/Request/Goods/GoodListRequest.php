<?php

declare(strict_types=1);

namespace App\Services\Wildberries\Import\Catalog\Request\Goods;

use App\Services\Wildberries\Import\Catalog\Request\Goods\Components\SettingsComponent;

final readonly class GoodListRequest
{
    public function __construct(
        public SettingsComponent $settings
    ) {}
}

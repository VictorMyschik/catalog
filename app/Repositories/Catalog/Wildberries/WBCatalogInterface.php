<?php

declare(strict_types=1);

namespace App\Repositories\Catalog\Wildberries;

use App\Services\Catalog\Wildberries\API\Response\Components\ChildGroupComponent;
use App\Services\Catalog\Wildberries\API\Response\Components\GroupComponent;

interface WBCatalogInterface
{
    public function getBaseGroups(): array;

    /**
     * @param GroupComponent[] $groups
     */
    public function saveBaseGroups(array $groups): void;

    public function saveChildGroup(ChildGroupComponent $group): void;
}

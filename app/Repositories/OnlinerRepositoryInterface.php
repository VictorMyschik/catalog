<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\CatalogType;

interface OnlinerRepositoryInterface
{
    public function isGoodExist(string $stringId): bool;

    public function getCatalogTypeById(int $id): CatalogType;
}
<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\CatalogType;
use App\Models\Good;

readonly class OnlinerDBRepository extends DBRepository implements OnlinerRepositoryInterface
{
    public function isGoodExist(string $stringId): bool
    {
        return $this->db->table(Good::getTableName())->where('string_id', $stringId)->exists();
    }

    public function getCatalogTypeById(int $id): CatalogType
    {
        return CatalogType::loadByOrDie($id);
    }
}
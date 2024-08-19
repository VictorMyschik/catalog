<?php

declare(strict_types=1);

namespace App\Services\Onliner;

use App\Models\CatalogType;
use App\Repositories\OnlinerRepositoryInterface;

final readonly class OnlinerService
{
    public function __construct(private OnlinerRepositoryInterface $repository) {}

    public function isGoodExist(string $stringId): bool
    {
        return $this->repository->isGoodExist($stringId);
    }

    public function getCatalogTypeById(int $id): CatalogType
    {
        return $this->repository->getCatalogTypeById($id);
    }
}
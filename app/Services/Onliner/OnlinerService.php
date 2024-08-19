<?php

declare(strict_types=1);

namespace App\Services\Onliner;

use App\Models\CatalogAttribute;
use App\Models\CatalogAttributeValue;
use App\Models\CatalogGroupAttribute;
use App\Models\CatalogType;
use App\Models\Manufacturer;
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

    public function saveGood(int $id, array $data): int
    {
        return $this->repository->saveGood($id, $data);
    }

    public function getGroupAttributeOrCreateNew(int $id, string $groupName, int $sortOrder): CatalogGroupAttribute
    {
        return $this->repository->getGroupAttributeOrCreateNew($id, $groupName, $sortOrder);
    }

    public function getCatalogAttributeOrCreateNew(CatalogGroupAttribute $group, string $title): CatalogAttribute
    {
        return $this->repository->getCatalogAttributeOrCreateNew($group, $title);
    }

    public function getCatalogAttributeValueOrCreateNew(CatalogAttribute $attribute, ?string $value): CatalogAttributeValue
    {
        return $this->repository->getCatalogAttributeValueOrCreateNew($attribute, $value);
    }

    public function createGoodAttributes(array $goodAttributes): void
    {
        $this->repository->createGoodAttributes($goodAttributes);
    }

    public function getManufacturerOrCreateNew(array $data): Manufacturer
    {
        return $this->repository->getManufacturerOrCreateNew($data);
    }
}
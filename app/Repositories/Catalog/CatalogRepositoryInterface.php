<?php

declare(strict_types=1);

namespace App\Repositories\Catalog;

use App\Models\Catalog\CatalogAttribute;
use App\Models\Catalog\CatalogAttributeValue;
use App\Models\Catalog\CatalogGroupAttribute;
use App\Models\Catalog\CatalogType;
use App\Models\Catalog\Image;
use App\Models\Catalog\Manufacturer;

interface CatalogRepositoryInterface
{
    public function isGoodExist(string $stringId): bool;

    public function getCatalogTypeById(int $id): CatalogType;

    public function saveGood(int $id, array $data): int;

    public function getGroupAttributeOrCreateNew(int $typeId, string $groupName, int $sortOrder): CatalogGroupAttribute;

    public function getCatalogAttributeOrCreateNew(CatalogGroupAttribute $group, string $title): CatalogAttribute;

    public function getCatalogAttributeValueOrCreateNew(CatalogAttribute $attribute, ?string $value): CatalogAttributeValue;

    public function createGoodAttributes(array $goodAttributes): void;

    public function getManufacturerOrCreateNew(array $data): Manufacturer;

    public function deleteGood(int $id): void;

    public function getGoodLogo(int $goodId): ?Image;

    public function getManufacturer(int $id): ?Manufacturer;
}
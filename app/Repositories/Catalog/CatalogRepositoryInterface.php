<?php

declare(strict_types=1);

namespace App\Repositories\Catalog;

use App\Models\Catalog\Onliner\OnCatalogAttribute;
use App\Models\Catalog\Onliner\OnCatalogAttributeValue;
use App\Models\Catalog\Onliner\OnCatalogGood;
use App\Models\Catalog\Onliner\OnCatalogGroup;
use App\Models\Catalog\Onliner\OnCatalogGroupAttribute;
use App\Models\Catalog\Onliner\OnCatalogImage;
use App\Models\Catalog\Onliner\OnManufacturer;

interface CatalogRepositoryInterface
{
    public function isGoodExist(string $stringId): bool;

    public function getCatalogGroupList(): array;

    public function getCatalogGroupById(int $id): OnCatalogGroup;

    public function saveGood(int $id, array $data): int;

    public function getGroupAttributeOrCreateNew(int $groupId, string $groupName, int $sortOrder): OnCatalogGroupAttribute;

    public function getCatalogAttributeOrCreateNew(OnCatalogGroupAttribute $group, string $title): OnCatalogAttribute;

    public function getCatalogAttributeValueOrCreateNew(OnCatalogAttribute $attribute, ?string $value): OnCatalogAttributeValue;

    public function createGoodAttributes(array $goodAttributes): void;

    public function getManufacturerOrCreateNew(array $data): OnManufacturer;

    public function deleteGood(int $id): void;

    public function getGoodLogo(int $goodId): ?OnCatalogImage;

    public function getManufacturer(int $id): ?OnManufacturer;

    public function hasGoodByStringId(string $stringId): bool;

    public function deleteManufacturer(int $manufacturerId): void;

    public function deleteCatalogGroup(int $groupId): void;

    public function saveCatalogGroup(int $id, array $data): void;

    public function getGoodById(int $id): ?OnCatalogGood;

    public function getGoodImages(int $goodId): array;

    public function getGoodImageById(int $catalogImageId): ?OnCatalogImage;

    public function getGoodAttributes(int $goodId): array;

    public function getGoodsByIds(array $ids): array;

    public function saveManufacturer(int $id, $data): int;

    public function saveGoodImage(int $id, array $data): int;
}

<?php

declare(strict_types=1);

namespace App\Services\Catalog;

use App\Models\Catalog\CatalogAttribute;
use App\Models\Catalog\CatalogAttributeValue;
use App\Models\Catalog\CatalogGroupAttribute;
use App\Models\Catalog\CatalogType;
use App\Models\Catalog\Manufacturer;
use App\Repositories\Catalog\CatalogRepositoryInterface;
use App\Services\ImageUploader\Enum\ImageTypeEnum;
use App\Services\ImageUploader\ImageUploadService;

final readonly class CatalogService
{
    public function __construct(
        private CatalogRepositoryInterface $repository,
        private ImageUploadService         $imageUploader,
    ) {}

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

    public function deleteGood(int $id): void
    {
        $this->imageUploader->deleteImagesWithModels($id, ImageTypeEnum::Good);
        $this->repository->deleteGood($id);
    }
}
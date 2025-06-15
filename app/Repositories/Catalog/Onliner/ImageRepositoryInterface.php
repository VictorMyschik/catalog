<?php

declare(strict_types=1);

namespace App\Repositories\Catalog\Onliner;

use App\Models\Catalog\Onliner\OnCatalogImage;

interface ImageRepositoryInterface
{
    public function getImageById(int $imageId): ?OnCatalogImage;

    public function getImageListByGoodId(int $goodId): array;

    public function deleteImage(OnCatalogImage $image): void;

    public function addImagesBulk(array $imageDtos): void;
}

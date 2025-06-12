<?php

declare(strict_types=1);

namespace App\Repositories\Catalog\Wildberries;

use App\Models\Catalog\Wildberries\WBCatalogImage;

interface ImageRepositoryInterface
{
    public function getImageById(int $imageId): ?WBCatalogImage;

    public function getImageListByGoodId(int $goodId): array;

    public function deleteImage(WBCatalogImage $image): void;
}

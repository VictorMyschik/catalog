<?php

declare(strict_types=1);

namespace App\Repositories\Images;

use App\Models\Catalog\Image;

interface ImageRepositoryInterface
{
    public function getImageById(int $imageId): ?Image;

    public function getImageListByGoodId(int $goodId): array;

    public function deleteImage(Image $image): void;
}

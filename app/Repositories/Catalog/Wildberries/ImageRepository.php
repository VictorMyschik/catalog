<?php

declare(strict_types=1);

namespace App\Repositories\Catalog\Wildberries;

use App\Models\Catalog\Wildberries\WBCatalogImage;
use App\Repositories\RepositoryBase;
use App\Services\Catalog\Wildberries\DTO\ImageDTO;

final readonly class ImageRepository extends RepositoryBase implements ImageRepositoryInterface
{
    public function addImage(ImageDTO $dto): WBCatalogImage
    {
        return WBCatalogImage::firstOrCreate([
            'hash'    => $dto->hash,
            'good_id' => $dto->good_id,
        ], $dto->toArray());
    }

    public function getImageById(int $imageId): ?WBCatalogImage
    {
        return WBCatalogImage::loadBy($imageId);
    }

    public function getImageListByGoodId(int $goodId): array
    {
        return WBCatalogImage::where('good_id', $goodId)->get()->all();
    }

    public function deleteImage(WBCatalogImage $image): void
    {
        $image->delete();
    }
}

<?php

declare(strict_types=1);

namespace App\Repositories\Images;

use App\Models\Catalog\CatalogImage;
use App\Repositories\RepositoryBase;
use App\Services\ImageUploader\DTO\ImageDTO;

final readonly class ImageRepository extends RepositoryBase implements ImageRepositoryInterface
{
    public function addImage(ImageDTO $dto): CatalogImage
    {
        return CatalogImage::firstOrCreate([
            'hash'    => $dto->hash,
            'good_id' => $dto->good_id,
        ], $dto->toArray());
    }

    public function getImageById(int $imageId): ?CatalogImage
    {
        return CatalogImage::loadBy($imageId);
    }

    public function getImageListByGoodId(int $goodId): array
    {
        return CatalogImage::where('good_id', $goodId)->get()->all();
    }

    public function deleteImage(CatalogImage $image): void
    {
        $image->delete();
    }
}

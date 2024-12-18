<?php

declare(strict_types=1);

namespace App\Repositories\Images;

use App\Models\Catalog\OnCatalogImage;
use App\Repositories\RepositoryBase;
use App\Services\ImageUploader\DTO\ImageDTO;

final readonly class ImageRepository extends RepositoryBase implements ImageRepositoryInterface
{
    public function addImage(ImageDTO $dto): OnCatalogImage
    {
        return OnCatalogImage::firstOrCreate([
            'hash'    => $dto->hash,
            'good_id' => $dto->good_id,
        ], $dto->toArray());
    }

    public function getImageById(int $imageId): ?OnCatalogImage
    {
        return OnCatalogImage::loadBy($imageId);
    }

    public function getImageListByGoodId(int $goodId): array
    {
        return OnCatalogImage::where('good_id', $goodId)->get()->all();
    }

    public function deleteImage(OnCatalogImage $image): void
    {
        $image->delete();
    }
}

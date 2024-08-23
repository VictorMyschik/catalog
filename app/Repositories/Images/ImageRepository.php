<?php

declare(strict_types=1);

namespace App\Repositories\Images;

use App\Models\Catalog\Image;
use App\Repositories\RepositoryBase;
use App\Services\ImageUploader\DTO\ImageDTO;

final readonly class ImageRepository extends RepositoryBase implements ImageRepositoryInterface
{
    public function addImage(ImageDTO $dto): Image
    {
        return Image::firstOrCreate([
            'hash'    => $dto->hash,
            'good_id' => $dto->good_id,
        ], (array)$dto);
    }

    public function getImageById(int $imageId): ?Image
    {
        return Image::loadBy($imageId);
    }

    public function getImageListByGoodId(int $goodId): array
    {
        return Image::where('good_id', $goodId)->get()->all();
    }

    public function deleteImage(Image $image): void
    {
        $image->delete();
    }
}

<?php

declare(strict_types=1);

namespace App\Services\ImageUploader;

use App\Services\ImageUploader\Enum\ImageTypeEnum;

interface ImageUploaderInterface
{
    public function uploadImage(int $goodId, string $imageUrl): void;

    public function deleteImageWithModel(int $objectId, ImageTypeEnum $imageType): void;
}

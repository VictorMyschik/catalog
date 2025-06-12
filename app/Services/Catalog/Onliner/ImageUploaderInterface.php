<?php

declare(strict_types=1);

namespace App\Services\Catalog\Onliner;

use App\Models\Catalog\Onliner\OnCatalogImage;
use App\Services\Catalog\Enum\CatalogImageTypeEnum;
use App\Services\Catalog\Onliner\Enum\ImageTypeEnum;
use Illuminate\Http\UploadedFile;

interface ImageUploaderInterface
{
    public function uploadImage(UploadedFile $image, int $goodId, CatalogImageTypeEnum $type): OnCatalogImage;

    public function deleteImageWithModel(int $objectId, ImageTypeEnum $imageType): void;
}

<?php

declare(strict_types=1);

namespace App\Services\ImageUploader;

use App\Models\Image;

interface ImageUploaderInterface
{
    public function uploadImage(int $goodId, string $imageUrl): void;
}

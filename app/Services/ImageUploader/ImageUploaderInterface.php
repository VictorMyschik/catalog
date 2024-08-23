<?php

declare(strict_types=1);

namespace App\Services\ImageUploader;

interface ImageUploaderInterface
{
    public function uploadImage(int $goodId, string $imageUrl): void;
}

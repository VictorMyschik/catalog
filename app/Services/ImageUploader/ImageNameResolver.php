<?php

declare(strict_types=1);

namespace App\Services\ImageUploader;

use Illuminate\Http\UploadedFile;

class ImageNameResolver
{
    public function getImageNameByType(string $type): string
    {
        return uniqid((string)time()) . $this->getImageExtensionByType($type);
    }

    private function getImageExtensionByType(?string $type): string
    {
        return match ($type) {
            'image/pjpeg', 'image/jpeg' => '.jpg',
            'image/x-png', 'image/png' => '.png',
            'image/gif' => '.gif',
            'image/svg+xml' => '.svg',
            'image/webp' => '.webp',
            default => '',
        };
    }
}

<?php

declare(strict_types=1);

namespace App\Services\ImageUploader\DTO;

final readonly class ImageDTO
{
    public function __construct(
        public string $file_name,
        public int $good_id,
        public string $original_url,
        public string $path,
        public string $hash,
    ) {}
}
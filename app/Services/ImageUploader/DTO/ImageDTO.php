<?php

declare(strict_types=1);

namespace App\Services\ImageUploader\DTO;

use App\Services\Catalog\Enum\CatalogImageTypeEnum;
use App\Services\Catalog\Enum\MediaTypeEnum;

final readonly class ImageDTO
{
    public function __construct(
        public string               $file_name,
        public int                  $good_id,
        public ?string              $original_url,
        public string               $path,
        public string               $hash,
        public CatalogImageTypeEnum $type,
        public MediaTypeEnum        $media_type,
    ) {}

    public function toArray(): array
    {
        return [
            'file_name'    => $this->file_name,
            'good_id'      => $this->good_id,
            'original_url' => $this->original_url,
            'path'         => $this->path,
            'hash'         => $this->hash,
            'type'         => $this->type->value,
            'media_type'   => $this->media_type->value,
        ];
    }
}
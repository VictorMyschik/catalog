<?php

declare(strict_types=1);

namespace App\Services\ImageUploader;

use App\Repositories\Images\ImageRepository;
use Illuminate\Contracts\Filesystem\Filesystem;

final readonly class ImageUploadService implements ImageUploaderInterface
{
    public function __construct(
        private Filesystem        $filesystem,
        private ImageNameResolver $imageNameResolver,
        private ImageRepository   $imageRepository,
        private array             $storageConfig
    ) {}

    public function uploadImage(int $goodId, string $imageUrl): void
    {
        $image = getimagesize($imageUrl);

        $fileName = $this->imageNameResolver->getImageNameByType($image['mime']);

        $filePathWithName = $this->storageConfig['images'] . '/' . $goodId;

        $image = $this->imageRepository->addImage(
            new ImageDTO(
                file_name: $fileName,
                good_id: $goodId,
                original_url: $imageUrl,
                path: $filePathWithName,
                hash: md5_file($imageUrl),
            )
        );

        $this->filesystem->put($filePathWithName . '/' . $image->getFileName(), file_get_contents($imageUrl));
    }
}

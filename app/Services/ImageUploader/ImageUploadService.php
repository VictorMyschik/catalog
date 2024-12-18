<?php

declare(strict_types=1);

namespace App\Services\ImageUploader;

use App\Models\Catalog\OnCatalogImage;
use App\Repositories\Images\ImageRepositoryInterface;
use App\Services\Catalog\Enum\CatalogImageTypeEnum;
use App\Services\Catalog\Enum\ImageExtensionEnum;
use App\Services\Catalog\Enum\MediaTypeEnum;
use App\Services\ImageUploader\DTO\ImageDTO;
use App\Services\ImageUploader\Enum\ImageTypeEnum;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

final readonly class ImageUploadService
{
    public function __construct(
        private Filesystem               $filesystem,
        private ImageRepositoryInterface $imageRepository,
        private array                    $storageConfig
    ) {}

    public function uploadImageByURL(int $goodId, string $imageUrl): void
    {
        try {
            $image = getimagesize($imageUrl);

            $fileName = $this->getImageNameByType($image['mime']);

            $path = $this->getPathToSave($goodId);

            $image = $this->imageRepository->addImage(
                new ImageDTO(
                    file_name: $fileName,
                    good_id: $goodId,
                    original_url: $imageUrl,
                    path: $path,
                    hash: md5_file($imageUrl),
                    type: CatalogImageTypeEnum::PHOTO,
                    media_type: MediaTypeEnum::IMAGE,
                )
            );

            $this->filesystem->put($path . '/' . $image->getFileName(), file_get_contents($imageUrl));
        } catch (\Exception $e) {
            Log::error('Error upload image: ' . $e->getMessage(), ['good_id' => $goodId, 'image_url' => $imageUrl]);
        }
    }

    private function getPathToSave(int $goodId): string
    {
        return $this->storageConfig['images'] . '/' . $goodId;
    }

    public function uploadImage(UploadedFile $image, int $goodId, CatalogImageTypeEnum $type): OnCatalogImage
    {
        $path = $this->getPathToSave($goodId);
        $this->filesystem->put($path . '/' . $image->getClientOriginalName(), $image->getContent());

        return $this->imageRepository->addImage(
            new ImageDTO(
                file_name: $image->getClientOriginalName(),
                good_id: $goodId,
                original_url: null,
                path: $path,
                hash: md5_file($image->getPathname()),
                type: $type,
                media_type: $this->getMediaType($image->getClientMimeType()),
            )
        );
    }

    private function getMediaType(string $mime): MediaTypeEnum
    {
        if (in_array($mime, ImageExtensionEnum::getList())) {
            return MediaTypeEnum::IMAGE;
        }

        if ($mime === 'video/mp4') {
            return MediaTypeEnum::VIDEO;
        }

        throw new \Exception('Unknown media type');
    }

    public function deleteImagesWithModels(int $objectId, ImageTypeEnum $imageType): void
    {
        $images = match ($imageType) {
            ImageTypeEnum::Good => $this->imageRepository->getImageListByGoodId($objectId),
            default => throw new ModelNotFoundException($imageType->getLabel() . ' type unknown'),
        };

        if (empty($images)) {
            return;
        }

        $dir = '';
        /** @var OnCatalogImage[] $images */
        foreach ($images as $image) {
            $dir = $image->getPath();
            $this->deleteImage($image);
        }

        $dir && $this->filesystem->deleteDirectory($dir);
    }

    public function deleteFile(string $path): void
    {
        $this->filesystem->delete($path);
    }

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

    private function deleteImage(OnCatalogImage $image): void
    {
        $this->deleteFile($image->getFilePathWithName());
        $this->imageRepository->deleteImage($image);
    }

    public function deleteImageById(int $id): void
    {
        $image = $this->imageRepository->getImageById($id);
        $image && $this->deleteImage($image);
    }
}

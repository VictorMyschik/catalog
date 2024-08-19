<?php

declare(strict_types=1);

namespace App\Repositories\Images;

use App\Models\Image;
use App\Repositories\DBRepository;
use App\Services\ImageUploader\ImageDTO;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Database\DatabaseManager;

final readonly class ImageRepository extends DBRepository implements ImageRepositoryInterface
{
    public function __construct(private Filesystem $filesystem, DatabaseManager $db)
    {
        parent::__construct($db);
    }

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

    public function deleteImage(int $goodId): void
    {
        $image = Image::where('good_id', $goodId)->first();

        $image && $this->filesystem->delete($image->getPath() . '/' . $image->getName());

        $image && $image->delete();
    }
}

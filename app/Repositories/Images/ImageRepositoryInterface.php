<?php

declare(strict_types=1);

namespace App\Repositories\Images;

interface ImageRepositoryInterface
{
    public function deleteImage(int $goodId): void;
}

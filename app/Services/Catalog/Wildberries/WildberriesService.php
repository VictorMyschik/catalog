<?php

declare(strict_types=1);

namespace App\Services\Catalog\Wildberries;

use App\Models\Catalog\Wildberries\WBCatalogGood;
use App\Repositories\Catalog\Wildberries\WBGoodsInterface;

final readonly class WildberriesService
{
    public function __construct(private WBGoodsInterface $repository) {}

    public function getGoodById(int $goodId): WBCatalogGood
    {
        return $this->repository->getGoodById($goodId);
    }

    public function deleteGood(int $id): void
    {
        $this->repository->deleteGood($id);
    }
}
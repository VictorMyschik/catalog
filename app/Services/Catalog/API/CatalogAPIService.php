<?php

declare(strict_types=1);

namespace App\Services\Catalog\API;

use App\Services\Catalog\API\DTO\SearchDTO;

final readonly class CatalogAPIService
{
    public function __construct(private CatalogAPIInterface $repository) {}

    public function searchGoods(SearchDTO $dto): array
    {
        return $this->repository->searchGoods($dto);
    }
}
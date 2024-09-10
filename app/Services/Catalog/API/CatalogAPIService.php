<?php

declare(strict_types=1);

namespace App\Services\Catalog\API;

use App\Services\Catalog\API\DTO\SearchDTO;

final readonly class CatalogAPIService
{
    public function searchGoods(SearchDTO $dto): array
    {
        return (array)$dto;
    }
}
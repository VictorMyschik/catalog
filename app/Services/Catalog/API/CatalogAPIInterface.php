<?php

declare(strict_types=1);

namespace App\Services\Catalog\API;

use App\Services\Catalog\API\DTO\SearchDTO;

interface CatalogAPIInterface
{
    public function searchGoods(SearchDTO $dto): array;
}
<?php

declare(strict_types=1);

namespace App\Services\Catalog\API;

use App\Services\Catalog\API\DTO\SearchDTO;
use Illuminate\Cache\Repository;
use Illuminate\Support\Facades\Cache;

final readonly class CatalogAPICache implements CatalogAPIInterface
{
    public const string CACHE_TAG = 'search_goods_';

    public function __construct(private CatalogAPIResponse $repository, private Repository $cache) {}

    public function searchGoods(SearchDTO $dto): array
    {
        $key = 'search_' . md5(implode('.', (array)$dto));

        return $this->cache->tags([self::CACHE_TAG])->rememberForever($key, function () use ($dto) {
            return $this->repository->searchGoods($dto);
        });
    }

    public static function clearCache(): void
    {
        Cache::tags([self::CACHE_TAG])->flush();
    }
}
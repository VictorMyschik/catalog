<?php

declare(strict_types=1);

namespace App\Repositories\Catalog;

use Psr\SimpleCache\CacheInterface;

class CatalogCacheRepository
{
    public const string CACHE_CATALOG_GROUPS = 'catalog_groups';

    public function __construct(private readonly CatalogDBRepository $service, private readonly CacheInterface $cache) {}

    private array $catalogGroupList = [];

    public function getCatalogGroupList(): array
    {
        if (!empty($this->catalogGroupList)) {
            return $this->catalogGroupList;
        }

        $list = $this->cache->rememberForever(self::CACHE_CATALOG_GROUPS, function () {
            return $this->service->getCatalogGroupList();
        });

        return $this->catalogGroupList = $list;
    }
}
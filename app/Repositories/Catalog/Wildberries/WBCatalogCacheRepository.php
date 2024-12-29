<?php

declare(strict_types=1);

namespace App\Repositories\Catalog\Wildberries;

use App\Services\Wildberries\Import\Catalog\Response\Catalog\Components\ChildGroupComponent;
use Psr\SimpleCache\CacheInterface;

final readonly class WBCatalogCacheRepository implements WBCatalogInterface
{
    private const string CACHE_KEY_BASE_GROUPS = 'base_groups';

    public function __construct(
        private WBCatalogInterface $repository,
        private CacheInterface     $cache
    ) {}

    public function saveBaseGroups(array $groups): void
    {
        $this->repository->saveBaseGroups($groups);
        $this->clearCache();
    }

    public function saveChildGroup(ChildGroupComponent $group): void
    {
        $this->repository->saveChildGroup($group);
        $this->clearCache();
    }

    public function getBaseGroups(): array
    {
        return $this->cache->rememberForever(self::CACHE_KEY_BASE_GROUPS, fn() => $this->repository->getBaseGroups());
    }

    private function clearCache(): void
    {
        $this->cache->delete(self::CACHE_KEY_BASE_GROUPS);
    }
}

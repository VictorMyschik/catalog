<?php

declare(strict_types=1);

namespace App\Repositories\Catalog\Wildberries;

use App\Models\Catalog\Wildberries\WBCatalogGood;
use App\Models\Catalog\Wildberries\WBCatalogGroup;
use App\Services\Catalog\Wildberries\API\Response\Components\AttributeComponent;
use App\Services\Catalog\Wildberries\API\Response\Components\ChildGroupComponent;
use App\Services\Catalog\Wildberries\DTO\WBGoodDto;
use App\Services\Catalog\Wildberries\Enum\WBCatalogAttributeGroupEnum;
use Illuminate\Cache\Repository;

final readonly class WBGoodsCacheRepository implements WBGoodsInterface
{
    private const string KEY_GOODS_BY_MARKET_ID = 'goods_by_market_id';
    private const string KEY_BRAND_LIST = 'brand_list';
    private const string KEY_GROUP_LIST = 'group_list';

    public function __construct(
        private WBGoodsInterface $repository,
        private Repository       $cache
    ) {}

    public function getExistingGoods(int $marketId): array
    {
        return $this->cache->rememberForever(self::KEY_GOODS_BY_MARKET_ID . $marketId, fn() => $this->repository->getExistingGoods($marketId));
    }

    public function saveGoods(int $marketId, array $data): void
    {
        $this->repository->saveGoods($marketId, $data);
    }

    public function clearCacheForShop(int $marketId): void
    {
        $this->cache->forget(self::KEY_GOODS_BY_MARKET_ID . $marketId);
    }

    public function createBrand(string $name): int
    {
        $id = $this->repository->createBrand($name);
        $this->cache->forget(self::KEY_BRAND_LIST);

        return $id;
    }

    public function getBrandList(): array
    {
        return $this->cache->rememberForever(self::KEY_BRAND_LIST, fn() => $this->repository->getBrandList());
    }

    public function saveGood(int $goodId, WBGoodDto $data): int
    {
        return $this->repository->saveGood($goodId, $data);
    }

    public function createImageModel($imageDto): void
    {
        $this->repository->createImageModel($imageDto);
    }

    public function getBaseGroups(): array
    {
        return $this->repository->getBaseGroups();
    }

    public function saveBaseGroups(array $groups): void
    {
        $this->repository->saveBaseGroups($groups);
        $this->clearGroupCache();
    }

    public function saveChildGroup(ChildGroupComponent $group): void
    {
        $this->repository->saveChildGroup($group);
        $this->clearGroupCache();
    }

    public function clearGroupCache(): void
    {
        $this->cache->forget(self::KEY_GROUP_LIST);
    }

    public function getFullGroups(): array
    {
        return $this->cache->rememberForever(self::KEY_GROUP_LIST, fn() => $this->repository->getFullGroups());
    }

    public function saveAttribute(int $groupId, AttributeComponent $attributeComponent, WBCatalogAttributeGroupEnum $type): void
    {
        $this->repository->saveAttribute($groupId, $attributeComponent, $type);
    }

    public function saveReferenceAttribute(AttributeComponent $attributeComponent): void
    {
        $this->repository->saveReferenceAttribute($attributeComponent);
    }

    public function getOrCreate(array $selling): int
    {
        return $this->repository->getOrCreate($selling);
    }

    public function getGroupById(int $id): ?WBCatalogGroup
    {
        return $this->getFullGroups()[$id] ?? null;
    }

    public function getGoodById(int $goodId): ?WBCatalogGood
    {
        return $this->repository->getGoodById($goodId);
    }

    public function deleteGood(int $id): void
    {
        $this->repository->deleteGood($id);
        $this->clearGroupCache();
    }
}

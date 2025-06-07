<?php

declare(strict_types=1);

namespace App\Repositories\Catalog\Wildberries;

use App\Models\Catalog\Wildberries\WBCatalogGroup;
use App\Services\Catalog\Wildberries\API\Response\Components\AttributeComponent;
use App\Services\Catalog\Wildberries\API\Response\Components\ChildGroupComponent;
use App\Services\Catalog\Wildberries\API\Response\Components\GroupComponent;
use App\Services\Catalog\Wildberries\DTO\WBGoodDto;
use App\Services\Catalog\Wildberries\Enum\WBCatalogAttributeGroupEnum;

interface WBGoodsInterface
{
    public function getExistingGoods(int $marketId): array;

    public function saveGoods(int $marketId, array $data): void;

    public function saveGood(int $goodId, WBGoodDto $data): int;

    public function getBrandList(): array;

    public function createBrand(string $name): int;

    public function createImageModel($imageDto): void;

    public function getBaseGroups(): array;

    public function getFullGroups(): array;

    /**
     * @param GroupComponent[] $groups
     */
    public function saveBaseGroups(array $groups): void;

    public function saveChildGroup(ChildGroupComponent $group): void;

    public function saveAttribute(int $groupId, AttributeComponent $attributeComponent, WBCatalogAttributeGroupEnum $type): void;

    public function saveReferenceAttribute(AttributeComponent $attributeComponent): void;

    public function getOrCreate(array $selling): int;

    public function getGroupById(int $id): ?WBCatalogGroup;
}

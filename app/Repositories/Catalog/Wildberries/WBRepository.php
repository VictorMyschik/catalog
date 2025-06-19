<?php

declare(strict_types=1);

namespace App\Repositories\Catalog\Wildberries;

use App\Models\Catalog\Onliner\OnCatalogImage;
use App\Models\Catalog\Wildberries\WBCatalogAttribute;
use App\Models\Catalog\Wildberries\WBCatalogBrand;
use App\Models\Catalog\Wildberries\WBCatalogGood;
use App\Models\Catalog\Wildberries\WBCatalogGroup;
use App\Models\Catalog\Wildberries\WBCatalogImage;
use App\Models\Catalog\Wildberries\WBCatalogReferenceAttribute;
use App\Services\Catalog\Wildberries\API\Response\Components\AttributeComponent;
use App\Services\Catalog\Wildberries\API\Response\Components\ChildGroupComponent;
use App\Services\Catalog\Wildberries\DTO\WBGoodDto;
use App\Services\Catalog\Wildberries\Enum\WBCatalogAttributeGroupEnum;
use Illuminate\Database\DatabaseManager;

readonly class WBRepository implements WBCatalogInterface, WBGoodsInterface
{
    public function __construct(
        private DatabaseManager $db,
    ) {}

    public function saveBaseGroups(array $groups): void
    {
        $data = [];
        foreach ($groups as $group) {
            $data[] = [
                'id'   => $group->id,
                'name' => $group->name,
            ];
        }

        $this->db->table(WBCatalogGroup::getTableName())->insert($data);
    }

    public function saveChildGroup(ChildGroupComponent $group): void
    {
        $this->db->table(WBCatalogGroup::getTableName())
            ->updateOrInsert(['id' => $group->subjectID, 'parent_id' => $group->parentID], [
                    'name'      => $group->subjectName,
                    'parent_id' => $group->parentID,
                ]
            );
    }

    public function saveAttribute(int $groupId, AttributeComponent $attributeComponent, WBCatalogAttributeGroupEnum $type): void
    {
        $this->db->table(WBCatalogAttribute::getTableName())
            ->updateOrInsert(
                ['attribute_id' => $attributeComponent->charcID, 'catalog_group_id' => $groupId, 'type' => $type->value],
            );
    }

    public function getBaseGroups(): array
    {
        return $this->db->table(WBCatalogGroup::getTableName())
            ->whereNull('parent_id')
            ->get()->mapWithKeys(fn($group) => [$group->id => $group])->all();
    }

    public function getExistingGoods(int $marketId): array
    {
        return $this->db->table(WBCatalogGood::getTableName())
            ->where('market_id', $marketId)
            ->get()->mapWithKeys(fn($goods) => [$goods->nm_id => $goods])->all();
    }

    public function saveGoods(int $marketId, array $data): void
    {
        $this->db->table(WBCatalogGood::getTableName())->insert($data);
    }

    public function createBrand(string $name): int
    {
        return $this->db->table(WBCatalogBrand::getTableName())->insertGetId(['name' => $name]);
    }

    public function getBrandList(): array
    {
        return $this->db->table(WBCatalogBrand::getTableName())
            ->get()->mapWithKeys(fn($brand) => [$brand->name => $brand->id])->all();
    }

    public function saveGood(int $goodId, WBGoodDto $data): int
    {
        if ($goodId > 0) {
            $this->db->table(WBCatalogGood::getTableName())->where('id', $goodId)->update((array)$data);

            return $goodId;
        }

        return $this->db->table(WBCatalogGood::getTableName())->insertGetId((array)$data);
    }

    public function createImageModel($imageDto): void
    {
        $this->db->table(WBCatalogImage::getTablename())->insert((array)$imageDto);
    }

    public function getFullGroups(): array
    {
        return WBCatalogGroup::all()->mapWithKeys(fn($group) => [$group->id => $group])->all();
    }

    public function saveReferenceAttribute(AttributeComponent $attributeComponent): void
    {
        $this->db->table(WBCatalogReferenceAttribute::getTableName())
            ->updateOrInsert(['id' => $attributeComponent->charcID], ['name' => $attributeComponent->name]);
    }

    public function getOrCreate(array $selling): int
    {
        $this->db->table(WBCatalogBrand::getTableName())->updateOrInsert(
            ['supplier_id' => $selling['supplier_id']],
            ['name' => $selling['brand_name'], 'supplier_id' => $selling['supplier_id']]
        );

        return $this->db->table(WBCatalogBrand::getTableName())->where('supplier_id', $selling['supplier_id'])->value('id');
    }

    public function getGroupById(int $id): ?WBCatalogGroup
    {
        return WBCatalogGroup::loadBy($id);
    }

    public function getGoodById(int $goodId): ?WBCatalogGood
    {
        return WBCatalogGood::loadBy($goodId);
    }

    public function deleteGood(int $id): void
    {
        $this->db->table(WBCatalogImage::getTableName())->where('good_id', $id)->delete();
        $this->db->table(WBCatalogGood::getTableName())->where('id', $id)->delete();
    }

    public function getGoodImages(int $goodId): array
    {
        return WBCatalogImage::where('good_id', $goodId)->get()->all();
    }
}

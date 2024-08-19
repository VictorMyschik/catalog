<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\CatalogAttribute;
use App\Models\CatalogAttributeValue;
use App\Models\CatalogGroupAttribute;
use App\Models\CatalogType;
use App\Models\Good;
use App\Models\GoodAttribute;
use App\Models\Manufacturer;

readonly class OnlinerDBRepository extends DBRepository implements OnlinerRepositoryInterface
{
    public function isGoodExist(string $stringId): bool
    {
        return $this->db->table(Good::getTableName())->where('string_id', $stringId)->exists();
    }

    public function getCatalogTypeById(int $id): CatalogType
    {
        return CatalogType::loadByOrDie($id);
    }

    public function saveGood(int $id, array $data): int
    {
        if ($id > 0) {
            $this->db->table(Good::getTableName())->where('id', $id)->update($data);

            return $id;
        }

        return $this->db->table(Good::getTableName())->insertGetId($data);
    }

    public function getGroupAttributeOrCreateNew(int $typeId, string $groupName, int $sortOrder): CatalogGroupAttribute
    {
        return CatalogGroupAttribute::firstOrCreate(['type_id' => $typeId, 'name' => $groupName], ['sort' => $sortOrder]);
    }

    public function getCatalogAttributeOrCreateNew(CatalogGroupAttribute $group, string $title): CatalogAttribute
    {
        return CatalogAttribute::firstOrCreate(['group_attribute_id' => $group->id(), 'name' => $title], ['sort' => 1000]);
    }

    public function getCatalogAttributeValueOrCreateNew(CatalogAttribute $attribute, ?string $value): CatalogAttributeValue
    {
        return CatalogAttributeValue::firstOrCreate(['catalog_attribute_id' => $attribute->id(), 'text_value' => $value]);
    }

    public function createGoodAttributes(array $goodAttributes): void
    {
        $this->db->table(GoodAttribute::getTableName())->insert($goodAttributes);
    }

    public function getManufacturerOrCreateNew(array $data): Manufacturer
    {
        return Manufacturer::firstOrCreate($data);
    }
}
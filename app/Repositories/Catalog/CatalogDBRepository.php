<?php

declare(strict_types=1);

namespace App\Repositories\Catalog;

use App\Models\Catalog\CatalogAttribute;
use App\Models\Catalog\CatalogAttributeValue;
use App\Models\Catalog\CatalogGroupAttribute;
use App\Models\Catalog\CatalogType;
use App\Models\Catalog\Good;
use App\Models\Catalog\GoodAttribute;
use App\Models\Catalog\Image;
use App\Models\Catalog\Manufacturer;
use App\Repositories\RepositoryBase;

readonly class CatalogDBRepository extends RepositoryBase implements CatalogRepositoryInterface
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

    public function deleteGood(int $id): void
    {
        $this->db->table(Good::getTableName())->where('id', $id)->delete();
    }

    public function getGoodLogo(int $goodId): ?Image
    {
        return Image::where('good_id', $goodId)->first();
    }

    public function getManufacturer(int $id): ?Manufacturer
    {
        return Manufacturer::loadBy($id);
    }

    public function getCatalogTypeList(): array
    {
        return CatalogType::get()->all();
    }

    public function hasGoodByIntId(int $intId): bool
    {
        return $this->db->table(Good::getTableName())->where('int_id', $intId)->exists();
    }

    public function deleteManufacturer(int $manufacturerId): void
    {
        $this->db->table(Manufacturer::getTableName())->where('id', $manufacturerId)->delete();
    }

    public function deleteCatalogType(int $typeId): void
    {
        $this->db->table(CatalogType::getTableName())->where('id', $typeId)->delete();
    }

    public function saveCatalogType(int $id, array $type): void
    {
        if ($id > 0) {
            $this->db->table(CatalogType::getTableName())->where('id', $id)->update($type);
        } else {
            $this->db->table(CatalogType::getTableName())->insert($type);
        }
    }

    public function getGoodById(int $id): ?Good
    {
        return Good::loadBy($id);
    }

    public function getGoodImages(int $goodId): array
    {
        return Image::where('good_id', $goodId)->get()->all();
    }
}
<?php

declare(strict_types=1);

namespace App\Repositories\Catalog;

use App\Models\Catalog\CatalogAttribute;
use App\Models\Catalog\CatalogAttributeValue;
use App\Models\Catalog\CatalogGood;
use App\Models\Catalog\CatalogGoodAttribute;
use App\Models\Catalog\CatalogGroup;
use App\Models\Catalog\CatalogGroupAttribute;
use App\Models\Catalog\CatalogImage;
use App\Models\Catalog\Manufacturer;
use App\Repositories\RepositoryBase;
use Illuminate\Support\Facades\DB;

readonly class CatalogDBRepository extends RepositoryBase implements CatalogRepositoryInterface
{
    public function isGoodExist(string $stringId): bool
    {
        return $this->db->table(CatalogGood::getTableName())->where('string_id', $stringId)->exists();
    }

    public function getCatalogGroupById(int $id): CatalogGroup
    {
        return CatalogGroup::loadByOrDie($id);
    }

    public function saveGood(int $id, array $data): int
    {
        if ($id > 0) {
            $data['updated_at'] = now();
            $this->db->table(CatalogGood::getTableName())->where('id', $id)->update($data);

            return $id;
        }

        return $this->db->table(CatalogGood::getTableName())->insertGetId($data);
    }

    public function getGroupAttributeOrCreateNew(int $groupId, string $groupName, int $sortOrder): CatalogGroupAttribute
    {
        return CatalogGroupAttribute::firstOrCreate(['group_id' => $groupId, 'name' => $groupName], ['sort' => $sortOrder]);
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
        $this->db->table(CatalogGoodAttribute::getTableName())->insert($goodAttributes);
    }

    public function getManufacturerOrCreateNew(array $data): Manufacturer
    {
        return Manufacturer::firstOrCreate($data);
    }

    public function deleteGood(int $id): void
    {
        $this->db->table(CatalogGood::getTableName())->where('id', $id)->delete();
    }

    public function getGoodLogo(int $goodId): ?CatalogImage
    {
        return CatalogImage::where('good_id', $goodId)->first();
    }

    public function getManufacturer(int $id): ?Manufacturer
    {
        return Manufacturer::loadBy($id);
    }

    public function getCatalogGroupList(): array
    {
        return CatalogGroup::get()->keyBy('id')->all();
    }

    public function hasGoodByIntId(int $intId): bool
    {
        return $this->db->table(CatalogGood::getTableName())->where('int_id', $intId)->exists();
    }

    public function deleteManufacturer(int $manufacturerId): void
    {
        $this->db->table(Manufacturer::getTableName())->where('id', $manufacturerId)->delete();
    }

    public function deleteCatalogGroup(int $groupId): void
    {
        $this->db->table(CatalogGroup::getTableName())->where('id', $groupId)->delete();
    }

    public function saveCatalogGroup(int $id, array $data): void
    {
        if ($id > 0) {
            $this->db->table(CatalogGroup::getTableName())->where('id', $id)->update($data);
        } else {
            $this->db->table(CatalogGroup::getTableName())->insert($data);
        }
    }

    public function getGoodById(int $id): ?CatalogGood
    {
        return CatalogGood::loadBy($id);
    }

    public function getGoodImages(int $goodId): array
    {
        return CatalogImage::where('good_id', $goodId)->get()->all();
    }

    public function getGoodImageById(int $catalogImageId): ?CatalogImage
    {
        return CatalogImage::loadBy($catalogImageId);
    }

    public function getGoodAttributes(int $goodId): array
    {
        $query = DB::table(CatalogGoodAttribute::getTableName());

        $query->join(CatalogAttributeValue::getTableName(),
            CatalogAttributeValue::getTableName() . '.id', '=',
            CatalogGoodAttribute::getTableName() . '.attribute_value_id');

        $query->join(CatalogAttribute::getTableName(),
            CatalogAttribute::getTableName() . '.id', '=',
            CatalogAttributeValue::getTableName() . '.catalog_attribute_id');

        $query->join(CatalogGroupAttribute::getTableName(),
            CatalogGroupAttribute::getTableName() . '.id', '=',
            CatalogAttribute::getTableName() . '.group_attribute_id');

        $query->where(CatalogGoodAttribute::getTableName() . '.good_id', $goodId);

        $query->orderBy(CatalogGroupAttribute::getTableName() . '.sort', 'ASC');
        $query->orderBy(CatalogAttribute::getTableName() . '.sort', 'ASC');

        return $query->select([
            CatalogGroupAttribute::getTableName() . '.name as group_name',
            CatalogAttribute::getTableName() . '.name as attribute_name',
            CatalogAttribute::getTableName() . '.sort as attribute_sort',
            CatalogAttribute::getTableName() . '.description as attribute_description',
            CatalogAttributeValue::getTableName() . '.text_value as attribute_value',
            CatalogAttributeValue::getTableName() . '.id as attribute_value_id',
            CatalogGoodAttribute::getTableName() . '.bool_value as bool_value',
            CatalogGoodAttribute::getTableName() . '.id as good_attribute_id',
            CatalogGroupAttribute::getTableName() . '.sort as group_sort',
        ])->get()->all();
    }

    public function getGoodsByIds(array $ids): array
    {
        return CatalogGood::whereIn('id', $ids)->get()->keyBy('id')->all();
    }

    public function saveManufacturer(int $id, $data): int
    {
        if ($id > 0) {
            $this->db->table(Manufacturer::getTableName())->where('id', $id)->update($data);

            return $id;
        }

        return $this->db->table(Manufacturer::getTableName())->insertGetId($data);
    }

    public function saveGoodImage(int $id, array $data): int
    {
        if ($id > 0) {
            $this->db->table(CatalogImage::getTableName())->where('id', $id)->update($data);

            return $id;
        }

        return $this->db->table(CatalogImage::getTableName())->insertGetId($data);
    }
}

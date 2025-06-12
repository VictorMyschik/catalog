<?php

declare(strict_types=1);

namespace App\Repositories\Catalog\Onliner;

use App\Models\Catalog\Onliner\OnCatalogAttribute;
use App\Models\Catalog\Onliner\OnCatalogAttributeValue;
use App\Models\Catalog\Onliner\OnCatalogGood;
use App\Models\Catalog\Onliner\OnCatalogGoodAttribute;
use App\Models\Catalog\Onliner\OnCatalogGroup;
use App\Models\Catalog\Onliner\OnCatalogGroupAttribute;
use App\Models\Catalog\Onliner\OnCatalogImage;
use App\Models\Catalog\Onliner\OnManufacturer;
use App\Repositories\RepositoryBase;
use Illuminate\Support\Facades\DB;

readonly class CatalogDBRepository extends RepositoryBase implements CatalogRepositoryInterface
{
    public function isGoodExist(string $stringId): bool
    {
        return $this->db->table(OnCatalogGood::getTableName())->where('string_id', $stringId)->exists();
    }

    public function getCatalogGroupById(int $id): OnCatalogGroup
    {
        return OnCatalogGroup::loadByOrDie($id);
    }

    public function saveGood(int $id, array $data): int
    {
        if ($id > 0) {
            $data['updated_at'] = now();
            $this->db->table(OnCatalogGood::getTableName())->where('id', $id)->update($data);

            return $id;
        }

        return $this->db->table(OnCatalogGood::getTableName())->insertGetId($data);
    }

    public function getGroupAttributeOrCreateNew(int $groupId, string $groupName, int $sortOrder): OnCatalogGroupAttribute
    {
        return OnCatalogGroupAttribute::firstOrCreate(['group_id' => $groupId, 'name' => $groupName], ['sort' => $sortOrder]);
    }

    public function getCatalogAttributeOrCreateNew(OnCatalogGroupAttribute $group, string $title): OnCatalogAttribute
    {
        return OnCatalogAttribute::firstOrCreate(['group_attribute_id' => $group->id(), 'name' => $title], ['sort' => 1000]);
    }

    public function getCatalogAttributeValueOrCreateNew(OnCatalogAttribute $attribute, ?string $value): OnCatalogAttributeValue
    {
        return OnCatalogAttributeValue::firstOrCreate(['catalog_attribute_id' => $attribute->id(), 'text_value' => $value]);
    }

    public function createGoodAttributes(array $goodAttributes): void
    {
        $this->db->table(OnCatalogGoodAttribute::getTableName())->insert($goodAttributes);
    }

    public function getManufacturerOrCreateNew(array $data): OnManufacturer
    {
        return OnManufacturer::firstOrCreate($data);
    }

    public function deleteGood(int $id): void
    {
        $this->db->table(OnCatalogGood::getTableName())->where('id', $id)->delete();
    }

    public function getGoodLogo(int $goodId): ?OnCatalogImage
    {
        return OnCatalogImage::where('good_id', $goodId)->first();
    }

    public function getManufacturer(int $id): ?OnManufacturer
    {
        return OnManufacturer::loadBy($id);
    }

    public function getCatalogGroupList(): array
    {
        return OnCatalogGroup::get()->keyBy('id')->all();
    }

    public function hasGoodByStringId(string $stringId): bool
    {
        return $this->db->table(OnCatalogGood::getTableName())->where('string_id', $stringId)->exists();
    }

    public function deleteManufacturer(int $manufacturerId): void
    {
        $this->db->table(OnManufacturer::getTableName())->where('id', $manufacturerId)->delete();
    }

    public function deleteCatalogGroup(int $groupId): void
    {
        $this->db->table(OnCatalogGroup::getTableName())->where('id', $groupId)->delete();
    }

    public function saveCatalogGroup(int $id, array $data): void
    {
        if ($id > 0) {
            $this->db->table(OnCatalogGroup::getTableName())->where('id', $id)->update($data);
        } else {
            $this->db->table(OnCatalogGroup::getTableName())->insert($data);
        }
    }

    public function getGoodById(int $id): ?OnCatalogGood
    {
        return OnCatalogGood::loadBy($id);
    }

    public function getGoodImages(int $goodId): array
    {
        return OnCatalogImage::where('good_id', $goodId)->get()->all();
    }

    public function getGoodImageById(int $catalogImageId): ?OnCatalogImage
    {
        return OnCatalogImage::loadBy($catalogImageId);
    }

    public function getGoodAttributes(int $goodId): array
    {
        $query = DB::table(OnCatalogGoodAttribute::getTableName());

        $query->join(OnCatalogAttributeValue::getTableName(),
            OnCatalogAttributeValue::getTableName() . '.id', '=',
            OnCatalogGoodAttribute::getTableName() . '.attribute_value_id');

        $query->join(OnCatalogAttribute::getTableName(),
            OnCatalogAttribute::getTableName() . '.id', '=',
            OnCatalogAttributeValue::getTableName() . '.catalog_attribute_id');

        $query->join(OnCatalogGroupAttribute::getTableName(),
            OnCatalogGroupAttribute::getTableName() . '.id', '=',
            OnCatalogAttribute::getTableName() . '.group_attribute_id');

        $query->where(OnCatalogGoodAttribute::getTableName() . '.good_id', $goodId);

        $query->orderBy(OnCatalogGroupAttribute::getTableName() . '.sort', 'ASC');
        $query->orderBy(OnCatalogAttribute::getTableName() . '.sort', 'ASC');

        return $query->select([
            OnCatalogGroupAttribute::getTableName() . '.name as group_name',
            OnCatalogAttribute::getTableName() . '.name as attribute_name',
            OnCatalogAttribute::getTableName() . '.sort as attribute_sort',
            OnCatalogAttribute::getTableName() . '.description as attribute_description',
            OnCatalogAttributeValue::getTableName() . '.text_value as attribute_value',
            OnCatalogAttributeValue::getTableName() . '.id as attribute_value_id',
            OnCatalogGoodAttribute::getTableName() . '.bool_value as bool_value',
            OnCatalogGoodAttribute::getTableName() . '.id as good_attribute_id',
            OnCatalogGroupAttribute::getTableName() . '.sort as group_sort',
        ])->get()->all();
    }

    public function getGoodsByIds(array $ids): array
    {
        return OnCatalogGood::whereIn('id', $ids)->get()->keyBy('id')->all();
    }

    public function saveManufacturer(int $id, $data): int
    {
        if ($id > 0) {
            $this->db->table(OnManufacturer::getTableName())->where('id', $id)->update($data);

            return $id;
        }

        return $this->db->table(OnManufacturer::getTableName())->insertGetId($data);
    }

    public function saveGoodImage(int $id, array $data): int
    {
        if ($id > 0) {
            $this->db->table(OnCatalogImage::getTableName())->where('id', $id)->update($data);

            return $id;
        }

        return $this->db->table(OnCatalogImage::getTableName())->insertGetId($data);
    }
}

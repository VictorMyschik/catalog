<?php

declare(strict_types=1);

namespace App\Models\Catalog\Wildberries;

use App\Models\Lego\Fields\DescriptionNullableFieldTrait;
use App\Models\Lego\Fields\TitleFieldTrait;
use App\Models\ORM\ORM;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class WBCatalogGood extends ORM
{
    use AsSource;
    use Filterable;
    use TitleFieldTrait;
    use DescriptionNullableFieldTrait;

    protected $table = 'wb_catalog_goods';

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $fillable = [
        'id',
        'nm_id',
        'imt_id',
        'subject_id',
        'vendor_code',
        'brand_id',
        'title',
        'description',
        'downloaded_at',
        'created_at',
    ];

    protected array $allowedSorts = [
        'id',
        'nm_id',
        'imt_id',
        'subject_id',
        'vendor_code',
        'brand_id',
        'title',
        'description',
        'downloaded_at',
        'created_at',
        'group_name',
    ];

    public function getMarketId(): int
    {
        return $this->market_id;
    }

    public function getNmId(): int
    {
        return $this->nm_id;
    }

    public function getImtId(): int
    {
        return $this->imt_id;
    }

    public function getSubjectId(): int
    {
        return $this->subject_id;
    }

    public function getVendorCode(): string
    {
        return $this->vendor_code;
    }

    public function getBrandId(): int
    {
        return $this->brand_id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getDownloadedAt(): string
    {
        return $this->downloaded_at;
    }

    public function getGroup(): WBCatalogGroup
    {
        return WBCatalogGroup::loadByOrDie($this->getSubjectId());
    }

    public function getImage(): ?WBCatalogImage
    {
        return WBCatalogImage::where('good_id', $this->id())->first();
    }

    public function getMarket(): Market
    {
        return Market::findOrFail($this->getMarketId());
    }
}

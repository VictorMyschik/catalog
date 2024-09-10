<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\v1\Catalog\Response;

use App\Http\Controllers\Api\v1\Catalog\Response\Components\CatalogGroupComponent;
use App\Http\Controllers\Api\v1\Catalog\Response\Components\ManufacturerComponent;

final readonly class CatalogGoodResponse
{
    public function __construct(
        public int                   $id,
        public CatalogGroupComponent $group,
        public ?string               $prefix,
        public ?string               $name,
        public ?string               $short_info,
        public ?string               $description,
        public ManufacturerComponent $manufacturer,
        public ?int                  $parent_good_id,
        public ?bool                 $is_certification,
        public array                 $attributes, // AttributeComponent[]
        public string                $created_at, // Atom format
        public ?string               $updated_at, // Atom format
    ) {}
}
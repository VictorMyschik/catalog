<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Catalog\Response;

use App\Http\Controllers\Api\V1\Catalog\Response\Components\CatalogGroupComponent;
use App\Http\Controllers\Api\V1\Catalog\Response\Components\ManufacturerComponent;

/**
 * @OA\Schema(
 *     schema="CatalogGoodResponse",
 *     type="object",
 *     title="Catalog Good Response",
 *     description="Catalog good response model",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="ID of the catalog good"
 *     ),
 *     @OA\Property(
 *         property="group",
 *         ref="#/components/schemas/CatalogGroupComponent",
 *         description="Group of the catalog good"
 *     ),
 *     @OA\Property(
 *         property="prefix",
 *         type="string",
 *         nullable=true,
 *         description="Prefix of the catalog good"
 *     ),
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         nullable=true,
 *         description="Name of the catalog good"
 *     ),
 *     @OA\Property(
 *         property="short_info",
 *         type="string",
 *         nullable=true,
 *         description="Short information about the catalog good"
 *     ),
 *     @OA\Property(
 *         property="description",
 *         type="string",
 *         nullable=true,
 *         description="Description of the catalog good"
 *     ),
 *     @OA\Property(
 *         property="manufacturer",
 *         ref="#/components/schemas/ManufacturerComponent",
 *         description="Manufacturer of the catalog good"
 *     ),
 *     @OA\Property(
 *         property="parent_good_id",
 *         type="integer",
 *         nullable=true,
 *         description="Parent good ID of the catalog good"
 *     ),
 *     @OA\Property(
 *         property="is_certification",
 *         type="boolean",
 *         nullable=true,
 *         description="Certification status of the catalog good"
 *     ),
 *     @OA\Property(
 *         property="attributes",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/AttributeComponent"),
 *         description="List of attributes of the catalog good"
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         description="Creation date of the catalog good in Atom format"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         nullable=true,
 *         format="date-time",
 *         description="Last update date of the catalog good in Atom format"
 *     )
 * )
 */
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
        public ?string               $parent_good_id,
        public ?bool                 $is_certification,
        public array                 $attributes, // AttributeComponent[]
        public string                $created_at, // Atom format
        public ?string               $updated_at, // Atom format
    ) {}
}

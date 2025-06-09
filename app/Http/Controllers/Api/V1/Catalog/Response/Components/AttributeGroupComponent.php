<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Catalog\Response\Components;

/**
 * @OA\Schema(
 *     schema="AttributeGroupComponent",
 *     type="object",
 *     title="Attribute Group Component",
 *     description="Attribute group component model",
 *     @OA\Property(
 *         property="title",
 *         type="string",
 *         description="Title of the attribute group"
 *     ),
 *     @OA\Property(
 *         property="sort",
 *         type="integer",
 *         description="Sort order of the attribute group"
 *     ),
 *     @OA\Property(
 *         property="attributes",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/AttributeComponent"),
 *         description="List of attributes in the group"
 *     )
 * )
 */
final readonly class AttributeGroupComponent
{
    public function __construct(
        public string $title,
        public int    $sort,
        public array  $attributes, // AttributeComponent[]
    ) {}
}

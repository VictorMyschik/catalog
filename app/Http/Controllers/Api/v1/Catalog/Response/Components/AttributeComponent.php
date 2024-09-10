<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\v1\Catalog\Response\Components;

/**
 * @OA\Schema(
 *     schema="AttributeComponent",
 *     type="object",
 *     title="Attribute Component",
 *     description="Attribute component model",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="ID of the attribute"
 *     ),
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         description="Name of the attribute"
 *     ),
 *     @OA\Property(
 *         property="description",
 *         type="string",
 *         nullable=true,
 *         description="Description of the attribute"
 *     ),
 *     @OA\Property(
 *         property="sort",
 *         type="integer",
 *         description="Sort order of the attribute"
 *     ),
 *     @OA\Property(
 *         property="value",
 *         ref="#/components/schemas/AttributeValueComponent",
 *         description="Value of the attribute"
 *     )
 * )
 */
final readonly class AttributeComponent
{
    public function __construct(
        public int                     $id,
        public string                  $name,
        public ?string                 $description,
        public int                     $sort,
        public AttributeValueComponent $value,
    ) {}
}
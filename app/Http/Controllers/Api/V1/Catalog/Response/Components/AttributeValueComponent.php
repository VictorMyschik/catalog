<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Catalog\Response\Components;

/**
 * @OA\Schema(
 *     schema="AttributeValueComponent",
 *     type="object",
 *     title="Attribute Value Component",
 *     description="Attribute value component model",
 *     @OA\Property(
 *         property="value",
 *         type="string",
 *         nullable=true,
 *         description="String value of the attribute"
 *     ),
 *     @OA\Property(
 *         property="bool_value",
 *         type="boolean",
 *         nullable=true,
 *         description="Boolean value of the attribute"
 *     )
 * )
 */
final readonly class AttributeValueComponent
{
    public function __construct(
        public ?string $value,
        public ?bool  $bool_value,
    ) {}
}

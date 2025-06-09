<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Catalog\Response\Components;

/**
 * @OA\Schema(
 *     schema="ManufacturerComponent",
 *     type="object",
 *     title="Manufacturer Component",
 *     description="Manufacturer component model",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="ID of the manufacturer"
 *     ),
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         description="Name of the manufacturer"
 *     ),
 *     @OA\Property(
 *         property="address",
 *         type="string",
 *         nullable=true,
 *         description="Address of the manufacturer"
 *     )
 * )
 */
final readonly class ManufacturerComponent
{
    public function __construct(
        public int     $id,
        public string  $name,
        public ?string $address,
    ) {}
}

<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\v1\Catalog\Response\Components;

/**
 * @OA\Schema(
 *     schema="CatalogGroupComponent",
 *     type="object",
 *     title="Catalog Group Component",
 *     description="Catalog group component model",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="ID of the catalog group"
 *     ),
 *     @OA\Property(
 *         property="title",
 *         type="string",
 *         description="Title of the catalog group"
 *     )
 * )
 */
final readonly class CatalogGroupComponent
{
    public function __construct(
        public int    $id,
        public string $title,
    ) {}
}
<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\v1\Catalog\Response\Components;

final readonly class AttributeGroupComponent
{
    public function __construct(
        public string $title,
        public int    $sort,
        public array  $attributes, // AttributeComponent[]
    ) {}
}
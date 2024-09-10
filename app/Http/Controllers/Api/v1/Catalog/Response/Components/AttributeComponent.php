<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\v1\Catalog\Response\Components;

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
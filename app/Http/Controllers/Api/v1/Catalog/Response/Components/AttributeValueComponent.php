<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\v1\Catalog\Response\Components;

final readonly class AttributeValueComponent
{
    public function __construct(
        public ?string $value,
        public ?bool  $bool_value,
    ) {}
}
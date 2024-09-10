<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\v1\Catalog\Response\Components;

final readonly class ManufacturerComponent
{
    public function __construct(
        public int     $id,
        public string  $name,
        public ?string $address,
    ) {}
}
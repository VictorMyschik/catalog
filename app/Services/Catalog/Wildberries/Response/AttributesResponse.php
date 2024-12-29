<?php

declare(strict_types=1);

namespace App\Services\Catalog\Wildberries\Response;


use App\Services\Catalog\Wildberries\API\WBClientResponseInterface;
use App\Services\Catalog\Wildberries\Response\Components\AttributeComponent;

final readonly class AttributesResponse implements WBClientResponseInterface
{
    public function __construct(
        public array $data, // AttributeComponent[]
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            data: array_map(
                callback: fn(array $item) => AttributeComponent::fromArray($item),
                array: $data['data'],
            ),
        );
    }
}

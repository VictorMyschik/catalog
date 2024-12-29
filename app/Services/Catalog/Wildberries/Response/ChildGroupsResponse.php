<?php

declare(strict_types=1);

namespace App\Services\Catalog\Wildberries\Response;


use App\Services\Catalog\Wildberries\API\WBClientResponseInterface;
use App\Services\Catalog\Wildberries\Response\Components\ChildGroupComponent;

final readonly class ChildGroupsResponse implements WBClientResponseInterface
{
    /**
     * @param ChildGroupComponent[] $data
     */
    public function __construct(
        public array $data, // ChildGroupComponent[]
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            data: array_map(
                callback: fn(array $item) => ChildGroupComponent::fromArray($item),
                array: $data['data'],
            ),
        );
    }
}

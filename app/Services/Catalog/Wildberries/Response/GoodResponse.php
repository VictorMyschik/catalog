<?php

declare(strict_types=1);

namespace App\Services\Catalog\Wildberries\Response;

use App\Services\Catalog\Wildberries\API\WBClientResponseInterface;

final readonly class GoodResponse implements WBClientResponseInterface
{
    public function __construct(
        public array $data,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self($data);
    }
}

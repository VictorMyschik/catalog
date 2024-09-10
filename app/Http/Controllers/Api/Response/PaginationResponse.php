<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Response;

final readonly class PaginationResponse
{
    public function __construct(
        public int $quantity,
        public int $totalQuantity,
        public int $currentPage,
        public int $pages,
    ) {}

    public static function empty(): self
    {
        return new self(0, 0, 0, 0);
    }
}

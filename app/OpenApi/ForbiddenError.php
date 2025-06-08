<?php

declare(strict_types=1);

namespace App\OpenApi;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "ForbiddenError",
    properties: [
        new OA\Property(property: "status", type: "string", example: "error"),
        new OA\Property(property: "content", type: "string", example: "Forbidden"),
    ],
    type: "object"
)]
class ForbiddenError {}

<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\User\Response;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "UserProfileResponse",
    properties: [
        new OA\Property(property: "name", description: "Имя пользователя", type: "string"),
        new OA\Property(property: "email", description: "Адрес электронной почты пользователя", type: "string"),
        new OA\Property(property: "isVerified", description: "Указывает, подтверждена ли электронная почта пользователя", type: "boolean"),
    ],
    type: "object"
)]
final readonly class UserProfileResponse
{
    public function __construct(
        public string $name,
        public string $email,
        public bool   $isVerified,
    ) {}
}

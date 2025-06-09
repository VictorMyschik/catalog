<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Response;

use App\Http\Controllers\Api\V1\User\Response\UserProfileResponse;
use App\Models\User;

final readonly class ResponseFactory
{
    public function getUserResponse(User $user): UserProfileResponse
    {
        return new UserProfileResponse(
            name: $user->name,
            email: $user->email,
            isVerified: (bool)$user->email_verified_at,
        );
    }
}

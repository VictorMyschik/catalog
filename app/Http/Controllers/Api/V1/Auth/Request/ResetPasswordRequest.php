<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth\Request;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "ResetPasswordRequest",
    required: ["email"],
    properties: [
        new OA\Property(property: "email", type: "string", format: "email", example: "user@example.com")
    ],
    type: "object"
)]
class ResetPasswordRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => 'required|email',
        ];
    }
}

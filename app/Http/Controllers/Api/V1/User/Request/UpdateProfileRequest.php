<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\User\Request;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "UpdateProfileRequest",
    required: ["name", "email"],
    properties: [
        new OA\Property(property: "name", type: "string", maxLength: 255, example: "Doe", nullable: true),
        new OA\Property(property: "email", type: "string", format: "email", maxLength: 255, example: "user@example.com", nullable: true),
    ],
    type: "object"
)]
class UpdateProfileRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name'  => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'email', 'max:255', Rule::unique('users')->ignore(Auth::id())],
        ];
    }

    public function getName(): ?string
    {
        return $this->input('name');
    }

    public function getEmail(): ?string
    {
        return $this->input('email');
    }
}

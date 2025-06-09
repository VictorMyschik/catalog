<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Request;

use App\Exceptions\ExceptionAPI;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="PaginationRequest",
 *     type="object",
 *     title="Pagination Request",
 *     description="Request model for pagination",
 *     @OA\Property(
 *         property="page",
 *         type="integer",
 *         description="Page number"
 *     ),
 *     @OA\Property(
 *         property="per_page",
 *         type="integer",
 *         description="Number of items per page"
 *     ),
 *     @OA\Property(
 *         property="sort",
 *         type="string",
 *         description="Sort order"
 *     )
 * )
 */
class PaginationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'page'     => 'int',
            'per_page' => 'int',
            'sort'     => 'string',
        ];
    }

    public function failedValidation(Validator $validator): void
    {
        throw new ExceptionAPI($validator->errors()->first());
    }

    public function getPage(int $default): int
    {
        return (int)$this->get('page', $default);
    }

    public function getPerPage(int $default): int
    {
        return (int)$this->get('per_page', $default);
    }

    public function getSort(string $default): string
    {
        if (!preg_match('/^[a-z0-9\-_]*$/', $this->get('sort', $default))) {
            throw new ExceptionAPI('Invalid sort field');
        }

        return (string)$this->get('sort', $default);
    }

    public function getKey(): string
    {
        return $this->getPage(0) . $this->getPerPage(50) . $this->getSort('name');
    }
}

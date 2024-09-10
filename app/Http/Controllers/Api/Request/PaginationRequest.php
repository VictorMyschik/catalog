<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Request;

use App\Exceptions\ExceptionAPI;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

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

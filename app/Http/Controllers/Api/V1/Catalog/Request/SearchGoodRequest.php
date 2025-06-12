<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Catalog\Request;

use Illuminate\Foundation\Http\FormRequest;

class SearchGoodRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'query' => 'required|string',
            'limit' => 'integer|min:1|max:100'
        ];
    }

    public function getLimit(): int
    {
        return (int)$this->get('limit', 10);
    }

    public function getSearch(): string
    {
        return (string)$this->get('query');
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\v1\Catalog\Request;

use Illuminate\Foundation\Http\FormRequest;

class SearchGoodRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'q' => 'required|string',
        ];
    }

    public function getSearch(): string
    {
        return (string)$this->get('q');
    }
}
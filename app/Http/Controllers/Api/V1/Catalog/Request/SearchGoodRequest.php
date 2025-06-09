<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Catalog\Request;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="SearchGoodRequest",
 *     type="object",
 *     title="SearchGoodRequest",
 *     description="Request parameters for searching goods",
 *     required={"q"},
 *     @OA\Property(
 *         property="q",
 *         type="string",
 *         description="Search query",
 *         example="laptop"
 *     ),
 *     @OA\Property(
 *         property="limit",
 *         type="integer",
 *         description="Limit the number of results",
 *         example=10,
 *         minimum=1,
 *         maximum=100
 *     )
 * )
 */
class SearchGoodRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'q'     => 'required|string',
            'limit' => 'integer|min:1|max:100'
        ];
    }

    public function getLimit(): int
    {
        return (int)$this->get('limit', 10);
    }

    public function getSearch(): string
    {
        return (string)$this->get('q');
    }
}

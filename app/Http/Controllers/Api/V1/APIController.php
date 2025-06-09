<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\Response\PaginationResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use OpenApi\Attributes as OA;

#[OA\Info(
    version: "1.0.0",
    description: "Для действий требуется авторизация с помощью метода Bearer Token",
    title: "Cataloger API",
)]
abstract class APIController extends Controller
{
    public function apiResponse(array|object $data = [], int $code = 200): JsonResponse
    {
        $output = $this->buildResponse((array)$data);

        return response()->json($output, $code);
    }

    public function withPaginate(LengthAwarePaginator $paginator): JsonResponse
    {
        return response()->json(
            $this->buildResponse([
                'items'      => $paginator->items(),
                'pagination' => new PaginationResponse(
                    quantity: $paginator->count(),
                    totalQuantity: $paginator->total(),
                    currentPage: $paginator->currentPage(),
                    pages: $paginator->lastPage()
                ),
            ])
        );
    }

    private function buildResponse(array $content): array
    {
        return [
            'status'  => 'ok',
            'content' => $content,
        ];
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Catalog;

use App\Http\Controllers\Api\V1\APIController;
use App\Http\Controllers\Api\V1\Catalog\Request\SearchGoodRequest;
use App\Http\Controllers\Api\V1\Catalog\Response\CatalogGoodResponse;
use App\Http\Controllers\Api\V1\Response\ApiResponse;
use App\Services\Catalog\API\CatalogAPIService;

/**
 * @OA\Tag(
 *     name="Catalog",
 *     description="Catalog related operations"
 * )
 */
class CatalogController extends ApiController
{
    public function __construct(public readonly CatalogAPIService $service) {}

    /**
     * @OA\Get(
     *     path="/api/v1/catalog/search",
     *     summary="Search for goods",
     *     description="Search for goods based on a query string and limit the number of results.",
     *     tags={"Catalog"},
     *     @OA\Parameter(
     *         name="q",
     *         in="query",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         description="The search query string"
     *     ),
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="integer", default=10),
     *         description="The maximum number of results to return"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/CatalogGoodResponse")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid request"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */
    public function searchGoods(SearchGoodRequest $request): ApiResponse
    {
        /** @var CatalogGoodResponse[] $goods */
        $goods = $this->service->searchGoods($request->getSearch(), $request->getLimit());

        return new ApiResponse($goods);
    }
}

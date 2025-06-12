<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Catalog;

use App\Http\Controllers\Api\V1\APIController;
use App\Http\Controllers\Api\V1\Catalog\Request\SearchGoodRequest;
use App\Http\Controllers\Api\V1\Catalog\Response\CatalogGoodResponse;
use App\Http\Controllers\Api\V1\Response\ApiResponse;
use App\Services\Catalog\API\CatalogAPIService;

class CatalogController extends ApiController
{
    public function __construct(public readonly CatalogAPIService $service) {}

    public function searchGoods(SearchGoodRequest $request): ApiResponse
    {
        /** @var CatalogGoodResponse[] $goods */
        $goods = $this->service->searchGoods($request->getSearch(), $request->getLimit());

        return new ApiResponse($goods);
    }
}

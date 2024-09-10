<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\v1\Catalog;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\Request\PaginationRequest;
use App\Http\Controllers\Api\Response\ApiResponse;
use App\Http\Controllers\Api\v1\Catalog\Request\SearchGoodRequest;
use App\Services\Catalog\API\CatalogAPIService;
use App\Services\Catalog\API\DTO\SearchDTO;

class CatalogController extends ApiController
{
    public function __construct(public readonly CatalogAPIService $service) {}

    public function searchGoods(PaginationRequest $paginationRequest, SearchGoodRequest $request): ApiResponse
    {
        $goods = $this->service->searchGoods(new SearchDTO(
            search: $request->getSearch(),
            page: $paginationRequest->getPage(1),
            perPage: $paginationRequest->getPerPage(10),
            sort: $paginationRequest->getSort('id'),
        ));

        return new ApiResponse($goods);
    }
}
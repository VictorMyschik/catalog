<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Response;

use Symfony\Component\HttpFoundation\JsonResponse;

class ApiResponse extends JsonResponse
{
    private const string DATA = 'data';
    private const string PAGINATION = 'pagination';
    private const string META = 'meta';
    private array $rawData;

    public function __construct(mixed $data = [], int $status = self::HTTP_OK, array $headers = [])
    {
        $this->rawData = [self::DATA => $data];
        parent::__construct([self::DATA => $data], $status, $headers);
    }

    public function withPagination(PaginationResponse $paginationResponse): self
    {
        $this->rawData[self::PAGINATION] = $paginationResponse;
        $this->setData($this->rawData);

        return $this;
    }

    public function withMeta(mixed $meta): self
    {
        $this->rawData[self::META] = $meta;
        $this->setData($this->rawData);

        return $this;
    }

    public function getRawData(): array
    {
        return $this->rawData;
    }
}

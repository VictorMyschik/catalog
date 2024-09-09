<?php

declare(strict_types=1);

namespace App\Services\Elasticsearch;

use App\Models\Catalog\Good;

final readonly class ESArticlesService
{
    private const string INDEX = 'catalog';

    public function __construct(private ESClient $client) {}

    public function addBulkIndex(Good $good): void
    {
        $body = [
            'prefix'       => $good->getPrefix(),
            'name'         => $good->getName(),
            'short_info'   => $good->getShortInfo(),
            'description'  => $good->getDescription(),
            'manufacturer' => $good->getManufacturer()->getName(),
        ];

        $this->client->single(self::INDEX, $body);
    }
}

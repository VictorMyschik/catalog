<?php

declare(strict_types=1);

namespace App\Services\Elasticsearch;

use Elasticsearch\Client;

final readonly class ESClient
{
    public function __construct(private Client $client) {}

    public function ping(): bool
    {
        return $this->client->ping();
    }

    public function getById(string $index, int $id): array
    {
        return $this->client->get([
            'index' => $index,
            'id'    => $id,
        ]);
    }

    /**
     * Generate index with many articles
     */
    public function bulk(string $index, array $data): array
    {
        $params = [];

        foreach ($data as $item) {
            $params['body'][] = ['index' => ['_index' => $index]];
            $params['body'][] = $item;
        }

        return $this->client->bulk($params);
    }

    public function single(string $index, array $data): array
    {
        $params = [
            'index' => $index,
            'id'    => $data['id'],
            'body'  => $data
        ];


        return $this->client->index($params);
    }

    public function search(string $query, string $index): array
    {
        $params = [
            "index" => $index,
            "from"  => 0, "size" => 10, // Elastic use pagination, get first page
            "body"  => [
                "query" => [
                    "bool" => [
                        "must"   => [
                            [
                                'multi_match' => [
                                    'query' => $query,
                                ],
                            ]
                        ],
                        "filter" => []
                    ]
                ]
            ]
        ];

        return $this->client->search($params);
    }
}

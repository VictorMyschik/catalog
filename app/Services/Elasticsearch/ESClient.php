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
        return $this->client->index([
            'index' => $index,
            'body'  => $data,
        ]);
    }

    public function search(string $hash, string $query, array $fields, string $index): array
    {
        $params = [
            "index" => $index,
            "from"  => 0, "size" => 100, // Elastic use pagination, get first page
            "body"  => [
                "query" => [
                    "bool" => [
                        "must"   => [
                            [
                                'multi_match' => [
                                    'fields' => $fields,
                                    'query'  => $query,
                                ],
                            ]
                        ],
                        "filter" => [
                            [
                                "match" => [
                                    "hash" => $hash
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        return $this->client->search($params);
    }

    public function deleteByQuery(string $index, string $hash): void
    {
        $params = [
            "index" => $index,
            "body"  => [
                "query" => [
                    "bool" => [
                        "filter" => [
                            [
                                "match" => [
                                    "hash" => $hash
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $this->client->deleteByQuery($params);
    }
}

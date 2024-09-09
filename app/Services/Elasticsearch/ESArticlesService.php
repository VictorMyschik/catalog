<?php

declare(strict_types=1);

namespace App\Services\Elasticsearch;

use Elasticsearch\Client;

class ESArticlesService
{
    private const string INDEX = 'articles';

    public function __construct(private readonly Client $client) {}

    public function ping(): bool
    {
       return $this->client->ping();
    }
}

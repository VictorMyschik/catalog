<?php

declare(strict_types=1);

namespace App\Services\Elasticsearch;

use Elasticsearch\Client;

class ESArticlesService
{
    private const string INDEX = 'catalog';

    public function __construct(private readonly ESClient $client) {}

    public function addBulkIndex(string $hash, array $articles): void
    {
        $body = [];

        foreach ($articles as $article) {
            $article = (array)$article;

            $body[] = [
                'uid'      => $article['uid'],
                'title'    => $article['title'],
                'abstract' => $article['abstract'],
                'hash'     => $hash,
            ];
        }

        $this->bulk(self::INDEX, $body);
        sleep(1);
    }
}

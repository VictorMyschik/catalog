<?php

declare(strict_types=1);

namespace Elasticsearch;

use App\Models\Catalog\CatalogGood;
use Exception;
use Tests\TestCase;

class ESClientTest extends TestCase
{
    private ?Client $client;

    public function setUp(): void
    {
        parent::setUp();

        $login = env('ELASTICSEARCH_LOGIN');
        $password = env('ELASTICSEARCH_PASSWORD');

        // HTTP Basic Authentication
        $hosts = [
            "http://{$login}:{$password}@elasticsearch:9200",
        ];

        $this->client = ClientBuilder::create()->setHosts($hosts)->build();
        $this->assertIsObject($this->client);
    }

    public function tearDown(): void
    {
        parent::tearDown();

        // Elastic delete all documents from "catalog" index
        $params = [
            'index' => 'catalog',
            'body'  => [
                'query' => [
                    'match_all' => new \stdClass()
                ]
            ],
            'conflicts' => 'proceed'
        ];
        $this->client->deleteByQuery($params);
    }

    public function testConnection(): void
    {
        $info = $this->client->info();
        $this->assertIsArray($info);
    }

    public function testCreateBulkIndex(): void
    {
        $limit = 100;
        $goods = CatalogGood::limit($limit)->get();

        $params = [];
        foreach ($goods as $good) {
            $params['body'][] = [
                'index' => [
                    '_index' => 'catalog',
                ]
            ];

            $params['body'][] = [
                'prefix'      => $good->getPrefix(),
                'name'        => $good->getName(),
                'short_info'  => $good->getShortInfo(),
                'description' => $good->getDescription(),
            ];
        }

        $responses = $this->client->bulk($params);

        $this->assertIsArray($responses);
        self::assertCount($limit, $responses['items']);
    }

    public function testCRUDSingle(): void
    {
        $good = CatalogGood::loadByOrDie(1);

        // Create
        $params = [
            'index' => 'catalog',
            'id'    => $good->id(),
            'body'  => [
                'prefix'      => $good->getPrefix(),
                'name'        => $good->getName(),
                'short_info'  => $good->getShortInfo(),
                'description' => $good->getDescription(),
            ]
        ];

        $responses = $this->client->index($params);

        $this->assertIsArray($responses);
        self::assertEquals($good->id(), $responses['_id']);

        // Read
        $params = [
            'index' => 'catalog',
            'id'    => $good->id()
        ];

        $responses = $this->client->get($params);

        $this->assertIsArray($responses);
        self::assertEquals($good->getPrefix(), $responses['_source']['prefix']);
        self::assertEquals($good->getName(), $responses['_source']['name']);
        self::assertEquals($good->getShortInfo(), $responses['_source']['short_info']);
        self::assertEquals($good->getDescription(), $responses['_source']['description']);


        // Update
        $good->name = 'New title';
        $good->short_info = 'New short text';

        $params = [
            'index' => 'catalog',
            'id'    => $good->id(),
            'body'  => [
                'name'      => $good->name,
                'short_info' => $good->short_info,
            ]
        ];

        $responses = $this->client->index($params);

        $this->assertIsArray($responses);
        self::assertEquals($good->id(), $responses['_id']);

        $params = [
            'index' => 'catalog',
            'id'    => $good->id()
        ];

        $responses = $this->client->get($params);

        $this->assertIsArray($responses);
        self::assertEquals($good->name, $responses['_source']['name']);
        self::assertEquals($good->short_info, $responses['_source']['short_info']);


        // Delete
        $responses = $this->client->delete($params);

        $this->assertIsArray($responses);
        self::assertEquals($good->id(), $responses['_id']);

        try {
            $this->client->get($params);
            self::fail('Document not deleted');
        } catch (Exception $e) {
            self::assertEquals(404, $e->getCode());
            self::assertEquals(
                '{"_index":"catalog","_type":"_doc","_id":"' . $good->id() . '","found":false}',
                $e->getMessage()
            );
        }
    }
}
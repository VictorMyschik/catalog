<?php

declare(strict_types=1);

namespace Elasticsearch;

use App\Models\Catalog\Onliner\OnCatalogGood;
use App\Services\Elasticsearch\ESService;
use Tests\TestCase;

class ESGoodTest extends TestCase
{
    public function testCreateBulkIndex(): void
    {
        /** @var ESService $service */
        $service = app(ESService::class);

        $good = new OnCatalogGood();
        $good->id = 1;
        $good->prefix = 'prefix';
        $good->name = 'name';
        $good->short_info = 'short_info';
        $good->description = 'description';
        $good->manufacturer = null;

        $service->addGood($good);

        $result = $service->getByGoodId(1);

        $this->assertIsArray($result);
        $this->assertEquals(1, $result['_id']);
        $this->assertEquals('prefix', $result['_source']['prefix']);
        $this->assertEquals('name', $result['_source']['name']);
        $this->assertEquals('short_info', $result['_source']['short_info']);
        $this->assertEquals('description', $result['_source']['description']);
        $this->assertNull($result['_source']['manufacturer']);
    }
}
